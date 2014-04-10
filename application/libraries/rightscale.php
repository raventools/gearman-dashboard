<?php

	class RightScale extends RestApi
	{
		protected $api_version = '1.0';
		protected $url_base;
		protected $cache_ext = 'rs';
		protected $format = 'js';
		protected $account;
		protected $cache_life = 0;
		protected $get_location = false;
		protected $location;

		public $ec2_obj;
		
		function __construct($account, $username, $password)
		{
			$this->account = $account;
			$this->login($username, $password);
			$this->url_base = "https://my.rightscale.com/api/acct/{$this->account}/";

			parent::__construct();
		}

		function initEC2($aws_key,$aws_secret,$zone="us")
		{
			$this->ec2_obj = new EC2($aws_key,$aws_secret);
			if($zone == "eu") $this->ec2_obj->_server = 'http://eu-west-1.ec2.amazonaws.com';
			if($zone == "ap") $this->ec2_obj->_server = 'http://ap-southeast-1.ec2.amazonaws.com';
			if($zone == "us-west") $this->ec2_obj->_server = 'http://us-west-1.ec2.amazonaws.com';
		}
		
		function request($url, $extra = array(), $force_post=false)
		{
			$url = $this->url_base . $url;
			if (!isset($extra['headers']))
				$extra['headers'] = array();
			$extra['headers'][] = 'X-API-VERSION: ' . $this->api_version;
			return parent::request($url, $extra, $force_post);
		}

		function objectify($response)
		{
			if ($this->format=='js')
			{
				$response = str_replace('NaN', 'null', $response);
			}
			return parent::objectify($response);
		}
		
		function setCurlOpts($ch)
		{
			parent::setCurlOpts($ch);
			if ($this->get_location == true)
			{
				curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(&$this,'getLocation'));
			}
		}
		
		function getLocation($ch, $header)
		{
			if (preg_match('/^Location: (.+)$/', $header, $m))
				$this->location = $m[1];

			return strlen($header);
		}
		
		/****** SERVER METHODS *******/
		function getServers($filter = false)
		{
			if ($filter)
				$url = "servers.{$this->format}?filter=".urlencode($filter);
			else
				$url = "servers.{$this->format}";
			return $this->request($url);
		}
		function getServer($id)
		{
			$url = "servers/$id.{$this->format}";
			return $this->request($url);
		}
		function getServerSettings($id)
		{
			$url = "servers/$id/settings.{$this->format}";
			return $this->request($url);
		}
		function setSpotBid($id,$bid)
		{
			$url = "servers/$id";
			$put = array('server[max_spot_price]'=>".03");
			$this->request($url, array('put'=>$put));
			return $this->getSpotBid($id);
		}
		function getSpotBid($id)
		{
			$settings = $this->getServerSettings($id);
			if ($settings->{'max-spot-price'}) {
				return $settings->{'max-spot-price'};
			}
			return 0;
		}

		function getServerByTags($tags) {
			/*
			if(is_string($tags)) {
				$tags = array($tags);
			}
			*/
			$extra['get']['resource_type'] = "ec2_instance";
			$extra['get']['tags'] = $tags;
			$url = "tags/search.{$this->format}";
			return $this->request($url,$extra);
		}

		function getArrayByTags($tags) {
			$extra['get']['resource_type'] = "server_array";
			$extra['get']['tags'] = $tags;
			$url = "tags/search.{$this->format}";
			return $this->request($url,$extra);
		}

		function getEC2Instance($id)
		{
			$url = "ec2_instances/{$id}.{$this->format}";
			return $this->request($url);
		}

		function runScript($server_id, $script_id, $ignore_lock = false, $inputs = null)
		{
			$tmp = $this->get_location;
			$this->get_location = true;
			$url = "servers/$server_id/run_script";
			if ($ignore_lock) {
				$post = array(
						'server[right_script_href]'=>$this->url_base."right_scripts/$script_id", 
						'server[ignore_lock]'=>'true'
						);
			} else {
				$post = array('right_script'=>$this->url_base."right_scripts/$script_id");
			}

			if(is_array($inputs) && count($inputs) > 0) {
				foreach($inputs as $k=>$v) {
					$pname = "server[parameters][$k]";
					$post[$pname] = $v;
				}
			}

			$this->request($url, array('post'=>$post));
			$this->get_location = $tmp;
			$result = $this->location;
			$this->location = null;
			return $result;
		}

		function runChefRecipe($server_id,$cookbook_recipe,$attributes=null) {
			$tmp = $this->get_location;
			$this->get_location = true;
			$url = "servers/$server_id/run_executable";

			$post['server[recipe]'] = $cookbook_recipe;

			if(is_array($attributes) && count($attributes) > 0) {
				foreach($attributes as $k=>$v) {
					$pname = "server[parameters][$k]";
					$post[$pname] = $v;
				}
			}

			$this->request($url, array('post'=>$post));
			$this->get_location = $tmp;
			$result = $this->location;
			$this->location = null;
			return $result;
		}

		function runScriptOnArray($array_id, $template_id, $script_id, $inputs = null)
		{
			$url = "server_arrays/$array_id/run_script_on_all.{$this->format}";
			$post = array(
				'server_array[right_script_href]' => $this->url_base."right_scripts/$script_id",
				'server_array[server_template_hrefs]' => $this->url_base . "ec2_server_templates/$template_id"
				);

			if(is_array($inputs) && count($inputs) > 0) {
				foreach($inputs as $k=>$v) {
					$pname = "server_array[parameters][$k]";
					$post[$pname] = $v;
				}
			}

			return $this->request($url, array('post'=>$post));
		}

		function runScriptOnArrayInstance($array_id, $instance_id, $script_id)
		{
			$url = "server_arrays/$array_id/run_script_on_instances.{$this->format}";
			$post = array(
				'server_array[right_script_href]' => $this->url_base."right_scripts/$script_id",
				'server_array[ec2_instance_hrefs]' => $this->url_base . "ec2_instances/$instance_id"
			);
			return $this->request($url, array('post'=>$post));
		}

		function newIP($nickname)
		{
			$url = "ec2_elastic_ips";
			$post = array('ec2_elastic_ip[nickname]'=>$nickname);
			$tmp = $this->get_location;
			$this->get_location = true;
			$this->request($url, array('post'=>$post));
			$this->get_location = $tmp;
			$result = $this->location;
			$this->location = null;
			$result = trim($result);
			if (preg_match("/\/(\d+)$/", $result, $m)) {
				$ip = $this->getIP($m[1]);
				return $ip->public_ip;
			}
		}
		function getIP($id)
		{
			$url = "ec2_elastic_ips/{$id}.{$this->format}";
			return $this->request($url);
		}
		function getCredentials()
		{
			$url = "credentials.{$this->format}";
			return $this->request($url);
		}
		function getCredential($id)
		{
			$url = "credentials/{$id}.{$this->format}";
			return $this->request($url);
		}
		function createCredential($name, $value, $desc="")
		{
			$url = "credentials";
			$post = array('credential[name]'=>$name, 'credential[description]'=>$desc, 'credential[value]'=>$value);
			$this->request($url, array('post'=>$post));
		}
		function getDeployments()
		{
			$url = "deployments.{$this->format}";
			return $this->request($url);
		}
		function getDeployment($id)
		{
			$url = "deployments/$id.{$this->format}";
			return $this->request($url);
		}
		function getServerArrays()
		{
			$url = "server_arrays.{$this->format}";
			return $this->request($url,array(),false);
		}
		function getServerArray($id)
		{
			$url = "server_arrays/$id.{$this->format}";
			return $this->request($url);
		}

		function getServerArraySettings($id) {
			$url = "server_arrays/$id/settings.{$this->format}";
			return $this->request($url);
		}

		function getAuditEntry($id)
		{
			$url = "audit_entries/$id.{$this->format}";
			return $this->request($url);
		}
		function launchServerArrayInstance($id,$get_instance_info=false)
		{ 
			// save some options
			$tmp = $this->get_location;
			$tmp_timeout = $this->curlopt_timeout;

			// override options
			$this->get_location = true;
			$this->curlopt_timeout = 20; // wait longer for server to launch so that we get instance href
			
			$this->request("server_arrays/$id/launch", array('headers' => array('Content-Length: 0')), true);
			$url = $this->location;

			// reset overridden options
			$this->get_location = $tmp;
			$this->curlopt_timeout = $tmp_timeout;
			$this->location = null;

			if($get_instance_info == true)
			{
				$tmp = $this->format;
				preg_match("#\/([^/]+)$#",$url,$m);
				$instance_id = trim($m[1]);
				$this->format = "xml";
				$response = $this->request("server_arrays/{$id}/instances");
				$this->format = $tmp;
				if(is_object($response->instance)) {
					foreach($response->instance as $instance) {
						$href = trim($instance->href);
						if(preg_match("#\/{$instance_id}$#",$href)) {
							return $instance;
						}
					}
				} else {
					return false;
				}

			}
			return $url;
		}
		function getServerArrayInstances($id)
		{
			$url = "server_arrays/$id/instances.{$this->format}";
			$instances = $this->request($url);
			$init_data = array(
					"account"=>$this->account,
					"username"=>$this->username,
					"password"=>$this->password,
					"ec2_obj"=>$this->ec2_obj,
					"array_id"=>$id
					);
			if(is_object($this->ec2_obj)) {
				$func = create_function(
						 '&$v,$k,$i',
						 '$v = new RightGridWorker($i["account"],$i["username"],$i["password"],$i["array_id"],$v);'
						.'$v->ec2_obj = $i["ec2_obj"];'
						.'$v->ec2_data = array_shift($v->ec2_obj->getInstance($v->resource_uid));'
						.'$v->calculateRuntime();'
						);
				array_walk($instances,$func,$init_data);
			}
			if(count($instances) > 0)
				return $instances;
			else
				return array();
		}
		function requestArrayReduction($array_id,$reduce_by,$low_threshold=45,$high_threshold=55)
		{
			$instances = $this->getServerArrayInstances($array_id);
			$filter = create_function('$ob','$rt=$ob->runtime%60;if($rt > '.$low_threshold.' && $rt< '.$high_threshold.') return true;');
			$shutdown = array_filter($instances,$filter);
			$shutdown = array_slice($shutdown,0,$reduce_by);
			foreach($shutdown as $inst) {
				$inst->terminate();
			}
			return count($shutdown);
		}

		function getRightScripts()
		{
			$url = "right_scripts.{$this->format}";
			return $this->request($url);
		}
		function getRightScript($id)
		{
			$url = "right_scripts/$id.{$this->format}";
			return $this->request($url);
		}

		function getServerTemplates()
		{
			$url = "server_templates.{$this->format}";
			return $this->request($url);
		}

		function getServerTemplate($id)
		{
			$url = "server_templates/{$id}/executables.{$this->format}";
			return $this->request($url);
		}
		
		function getStatus($id)
		{
			$url = "statuses/$id.{$this->format}";
			return $this->request($url);
		}
		
		
		function getSketchy($id, $start, $end, $name, $type)
		{
			$params['get'] = array('start'=>$start, 'end'=>$end, 'plugin_name'=>$name, 'plugin_type'=>$type);
			$url = "servers/$id/get_sketchy_data.{$this->format}";
			return $this->request($url,$params);
		}
		
		function getGraphs($id)
		{
			$url = "servers/$id/monitoring";
			return $this->request($url);
		}

		function updateInputs($level,$id,$inputs,$current=false)
		{
			switch($level)
			{
				case "server":
					$url = "servers/$id".($current?"/current":"");
					break;
				case "deployment":
					$url = "deployments/$id".($current?"/current":"");
					break;

			}

			$put = array();
			foreach($inputs as $k=>$v) {
				$idx = "{$level}[parameters][$k]";
				$put[$idx] = $v;
			}
			
			return $this->request($url, array('put'=>$put));
		}

		public function parseServer($raw) {
			preg_match("#ec2_server_templates/([0-9]+)$#",$raw->server_template_href,$matches);
			$server_template_id = $matches[1];
			preg_match("#deployments/([0-9]+)$#",$raw->deployment_href,$matches);
			$deployment_id = $matches[1];
			preg_match("#/([0-9]+)(/current)?$#",$raw->href,$matches);
			$id = $matches[1];
			$out = (object) array(
					"server_template_id" => $server_template_id,
					"deployment_id" => $deployment_id,
					"name" => $raw->nickname,
					"state" => (isset($raw->state) ? $raw->state : null),
					"inputs" => $this->parseInputs($raw),
					"id" => $id
					);
			return $out;
		}

		public function parseInputs($raw) {
			if(isset($raw->parameters) && is_array($raw->parameters)) {
				$inputs = new StdClass();
				foreach($raw->parameters as $input) {
					$inputs->{$input->name} = $input->value;
				}
				return $inputs;
			}
			return false;
		}
	}
