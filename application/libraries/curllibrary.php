<?php

/**
 * Custom Curl class in use by RestApi - this allows for Mockage
 *
 * Class CurlLibrary
 * @author Kevin Crawley (kevin@raventools.com)
 */
class CurlLibrary {
	public $debug = false;
	public $curlopt_timeout = 8;
	public $reuse_curl_handle = false;
	protected $curl = null;

	public function __construct($debug = false) {
		$this->debug = $debug;
	}

	public function setCurlOpts()
	{
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->curlopt_timeout);
		if ($this->debug) {
			curl_setopt($this->curl, CURLOPT_VERBOSE, true);
		}
	}

	public function setCurlOption($opt, $var) {
		curl_setopt($this->curl,$opt,$var);
	}

	public function getCurl() {
		return $this->curl;
	}

	public function initCurl() {
		$this->curl = curl_init();
	}

	public function closeCurl() {
		if ($this->reuse_curl_handle === false) {
			curl_close($this->curl);
		}
	}

	public function fetchResponse() {
		return curl_exec($this->curl);
	}

	public function fetchInfo() {
		return curl_getinfo($this->curl);
	}

	public function fetchHttpCode() {
		return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
	}
}