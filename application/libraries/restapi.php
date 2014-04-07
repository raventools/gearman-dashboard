<?php

if (class_exists('RestApi')) {
	return;
}

class RestApi {

	protected $username;
	protected $password;
	protected $format = "json";
	protected $cache_life = 3600;
	protected $cache_dir = "cache";
	protected $cache_slug;
	protected $cache_type = "file";
	protected $cache_ext;
	protected $curlLibrary = null;
	protected $last_url;
	private $debug = false;
	private $reuse_curl_handle = false;

	public $raw_response;
	public $info;

	public $last_http_code;

	public function __construct($curlLibrary = null)
	{
		global $docroot;
		$this->cache_slug = $this->cache_dir;
		$this->cache_dir = $docroot.'/'.$this->cache_dir;

		// allow for dependency injection, defaults to standard CurlLibrary
		if (isset($curlLibrary)) {
			$this->curlLibrary = $curlLibrary;
		} elseif (isset($this->curlLibrary) === false) {
			$this->curlLibrary = new CurlLibrary($this->debug);
		}
	}

	/**
	 * these previously public properties are utilized in CurlLibrary as well
	 * (not exclusively, but we do need a way to set them there)
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function __set($name, $value) {
		//
		if ($name === 'reuse_curl_handle') {
			$this->reuse_curl_handle = $value;
			$this->curlLibrary->reuse_curl_handle = $value;
		}

		if ($name === 'debug') {
			$this->debug = $value;
			$this->curlLibrary->debug = $value;
		}
	}

	/**
	 * 	doubt these were ever accessed in this manner, but just in case
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if ($name === 'reuse_curl_handle') {
			return $this->reuse_curl_handle;
		}

		if ($name === 'debug') {
			return $this->debug;
		}
	}

	public function login($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

	public function logout()
	{
		$this->username = null;
		$this->password = null;
	}

	public function setFormat($format)
	{
		$this->format = $format;
	}

	public function setCache($life, $dir, $ext)
	{
		global $docroot;
		$this->cache_life = $life;
		$this->cache_dir = $docroot.$dir;
		$this->cache_ext = $ext;
	}

	public function setCacheLife($life)
	{
		$this->cache_life = $life;
	}

	public function setCurlOpts(&$ch) {
		$this->curlLibrary->setCurlOpts();
	}


	public function request($url, $extra = array(), $force_post = false, $force_cache = false) {
		if ($this->cache_type == 'memcached' && class_exists('Raven')) {
			$raven = Raven::getRaven();
			$memcached = $raven->getMemcached('cache');
		}

		if ($this->debug) {
			Audit::Log("REQUEST: $url, ".serialize($extra)."\n");
		}

		$cache_life = (isset($extra['cache_life'])) ? $extra['cache_life'] : $this->cache_life;

		if (isset($extra['get']) && is_array($extra['get']) && count($extra['get']) > 0) {
			$url .= '?';
			$first = true;
			foreach ($extra['get'] as $param=>$value)	{
				if (!$first) {
					$url .= '&';
				} else {
					$first = false;
				}

				$url .= urlencode($param) . '=' . urlencode($value);
			}
		}

		// Allow timeout to be overridden
		if (isset($extra['timeout'])) {
			$this->curlLibrary->curlopt_timeout = (int) $extra['timeout'];
			unset($extra['timeout']);
		}

		if (isset($extra['post']) && is_array($extra['post']) && count($extra['post']) > 0) {
			$post = "";
			$first = true;
			foreach ($extra['post'] as $param=>$value) {
				if (!$first) {
					$post .= '&';
				} else {
					$first = false;
				}

				$post .= $param . '=' . $value;
			}
		} elseif (isset($extra['post']) && is_string($extra['post'])) {
			$post = $extra['post'];
		}	else {
			$post = false;
		}

		$this->cache_dir = rtrim($this->cache_dir, '/');

		$use_cache = (($post === false || $force_cache === true) && $force_post === false && $cache_life && $this->cache_dir && is_dir($this->cache_dir) && is_writable($this->cache_dir));

		if (isset($extra['put'])) {
			$use_cache = false;
		}

		if ($use_cache) {
			$cache_hash = md5($url.'|'.$post.'|'.$this->username.'|'.$this->password);
			$cache_file = $this->cache_dir . '/' .  $cache_hash;

			if ($this->cache_ext) {
				$cache_file .= ".{$this->cache_ext}";
			}

			if ($this->debug) {
				Audit::Log("CHECKING CACHE: $cache_file\n");
			}

			if ($this->cache_type == 'memcached' && $memcached !== false) {
				$from_memcached = $memcached->get("restapi.{$this->cache_slug}.{$cache_hash}");
				if ($from_memcached) {
					if ($this->debug) {
						Audit::Log("USING MEMCACHED DATA: restapi.{$this->cache_slug}.{$cache_hash}\n");
					}

					return $this->objectify($from_memcached);
				}
			} elseif (file_exists($cache_file) && ($cache_life < 0 || filemtime($cache_file) > time()-($this->cache_life))) {
				if ($this->debug) {
					Audit::Log("USING CACHED DATA: $cache_file\n");
				}

				return $this->objectify(file_get_contents($cache_file));
			}
		}

		if ($this->debug) {
			Audit::Log("REQUEST: $url\n");
		}

		if ($this->curlLibrary->reuse_curl_handle === false || ($this->curlLibrary->getCurl() === null)) {
			$this->curlLibrary->initCurl();
		}

		$this->curlLibrary->setCurlOption(CURLOPT_URL, $url);
		$this->last_url = $url;

		if ($post !== false || $force_post) {
			if ($this->debug) {
				Audit::Log("POST: $post\n");
			}

			$this->curlLibrary->setCurlOption(CURLOPT_POST, true);

			if ($post) {
				$this->curlLibrary->setCurlOption(CURLOPT_POSTFIELDS, $post);
			}
		}

		if (isset($extra['put'])) {
			if (is_string($extra['put'])) {
				$fh = fopen('php://memory', 'rw');
				fwrite($fh, $extra['put']);
				rewind($fh);

				$this->curlLibrary->setCurlOption(CURLOPT_INFILE, $fh);
				$this->curlLibrary->setCurlOption(CURLOPT_INFILESIZE, strlen($extra['put']));
				$this->curlLibrary->setCurlOption(CURLOPT_PUT, true);
			} else {
				if (is_array($extra['put']) && count($extra['put']) > 0) {
					$put = "";
					$first = true;
					foreach ($extra['put'] as $param=>$value) {
						if (!$first) {
							$put .= '&';
						} else {
							$first = false;
						}

						$put .= $param . '=' . $value;
					}
				}
				$this->curlLibrary->setCurlOption(CURLOPT_CUSTOMREQUEST, "PUT");
				$this->curlLibrary->setCurlOption(CURLOPT_POSTFIELDS, $put);
			}
		}

		if (isset($extra['request_method'])) {
			$this->curlLibrary->setCurlOption(CURLOPT_CUSTOMREQUEST, $extra['request_method']);
		}

		if (!is_null($this->username) && !is_null($this->password)) {
			if ($this->debug) {
				Audit::Log("AUTH: {$this->username}:{$this->password}\n");
			}

			$this->curlLibrary->setCurlOption(CURLOPT_USERPWD, $this->username.':'.$this->password);
		}

		if (isset($extra['headers']) && is_array($extra['headers']) && count($extra['headers']) > 0) {
			if ($this->debug) {
				Audit::Log("HEADERS: ".print_r($extra['headers'],true)."\n");
			}

			$this->curlLibrary->setCurlOption(CURLOPT_HTTPHEADER, $extra['headers']);
		}

		$this->setCurlOpts($this->curlLibrary->getCurl());

		$response = $this->curlLibrary->fetchResponse();
		$info = $this->curlLibrary->fetchInfo();

		$this->info = $info;
		$this->last_http_code = $this->curlLibrary->fetchHttpCode();

		$this->curlLibrary->closeCurl();

		$this->raw_response = $response;

		if ($this->debug)	{
			Audit::Log($info,"INFO");
			Audit::Log($response,"res");
			Audit::Log(htmlspecialchars($response), "RESPONSE");
		}

		$object = $this->verify($info, $response);

		if ($object !== false && !is_null($object)) {
			if ($use_cache) {
				if (isset($memcached) && $this->cache_type == 'memcached' && $memcached !== false) {
					if ($this->cache_life > 60*60*24*30) {
						$expiration = 60*60*24*30;
					}	elseif ($this->cache_life > 0) {
						$expiration = $this->cache_life;
					}	else {
						$expiration = 0;
					}

					$memcached->set("restapi.{$this->cache_slug}.{$cache_hash}", $response, $expiration);

					if ($this->debug) {
						Audit::Log("CACHE: writing to restapi.{$this->cache_slug}.{$cache_hash}\n");
					}
				} else {
					if ($this->debug) {
						Audit::Log("CACHE: writing to $cache_file\n");
					}

					file_put_contents($cache_file, $response);
				}
			}

			return $object;
		}

		if ($use_cache && file_exists($cache_file)) {
			return $this->objectify(file_get_contents($cache_file));
		} else {
			return false;
		}
	}

	public function verify($info, $response) {
		if (!preg_match('/^2[0-9]{2}$/', $info['http_code'])) {
			return false;
		}

		if ($response === false) {
			return false;
		}

		return $this->objectify($response);
	}

	public function objectify($response) {
		switch ($this->format) {
			case 'json':
			case 'js':
				$response = preg_replace("#[\x80-\xFF]+#","",$response);
				return json_decode($response);
				break;
			case 'json_array':
				return json_decode($response, true);
				break;
			case 'xml':
			case 'atom':
			case 'rss':
				return simplexml_load_string($response);
				break;
			case 'php':
			case 'php_serial':
				return unserialize($response);
			default:
				return $response;
		}
	}

	public function cleaner_xml($xml) {
		$xml = preg_replace("/\x07/", "", $xml); # 7
		$xml = preg_replace("/\x80\x98/", "'", $xml); # 128,152
		$xml = preg_replace("/\x80\x99/", "'", $xml); # 128,153
		$xml = preg_replace("/\x80\x9C/", "\"", $xml); # 128,156
		$xml = preg_replace("/\x80\x9D/", "\"", $xml); # 128,157
		$xml = preg_replace("/[^\x01-\x7F\x200-\xFFF]/","", $xml);
		return $xml;
	}
}

/* EOF: class.restapi.php */
