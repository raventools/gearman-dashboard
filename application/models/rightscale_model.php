<?php

class Rightscale_model extends MY_Model {

	private $rs = null;
	
	public function __construct() {
		$config = $this->loadConfig("rightscale");
		$this->rs = new Rightscale($config->account_id,$config->username,$config->password);
	}

	public function masters() {
		$masters = array();
		$raw_servers = $this->rs->getServerByTags("server:type=gearman_master");
		if($raw_servers === false) {
			return array();
		}
		foreach($raw_servers as $raw) {
			$parsed = $this->rs->parseServer($raw);
			$details = $this->rs->getServerSettings($parsed->id);
			$master = (object) array(
					"id" => $parsed->id,
					"public_ip" => $details->{"ip-address"},
					"private_ip" => $details->{"private-ip-address"},
					"port" => 4730
					);
			$masters[$parsed->name] = $master;
		}
		return $masters;
	}

	public function arrays() {
		$arrays = array();
		$raw_arrays = $this->rs->getArrayByTags("server:type=gearman_instance");
		if($raw_arrays === false) {
			return array();
		}
		foreach($raw_arrays as $raw) {
			$parsed = $this->rs->parseServer($raw);
			$array = (object) array(
					"name" => $parsed->name,
					"scaling" => ($raw->active ? "automatic" : "manual"),
					"deployment_id" => $parsed->deployment_id,
					"server_template_id" => $parsed->server_template_id,
					"cur_instances" => $raw->active_instances_count,
					"min_instances" => $raw->elasticity->min_count,
					"max_instances" => $raw->elasticity->max_count
					);
			$arrays[$parsed->name] = $array;
		}
		return $arrays;
	}

	public function instances() {
		$instances = array();
		$raw_instances = $this->rs->getServerByTags("server:type=gearman_instance");
		if($raw_instances === false) {
			return array();
		}
		foreach($raw_instances as $raw) {
			$parsed = $this->rs->parseServer($raw);
			$instances[$parsed->name] = $parsed;
		}
		return $instances;
	}
}
