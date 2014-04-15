<?php

class Rightscale_model extends MY_Model {

	private $rs = null;
	
	public function __construct() {
		$config = $this->loadConfig("rightscale");
		$this->rs = new Rightscale($config->account_id,$config->username,$config->password);
	}

	/**
	 * retrieve servers running gearmand
	 */
	public function masters() {
		$masters = new StdClass();
		$raw_servers = $this->rs->getServerByTags("server:type=gearman_master");
		if($raw_servers === false) {
			return array();
		}

		foreach($raw_servers as $raw) {
			$parsed = $this->rs->parseServer($raw);
			$details = $this->rs->getServerSettings($parsed->id);
			$master = (object) array(
					"name" => $parsed->name,
					"id" => $parsed->id,
					"public_ip" => $details->{"ip-address"},
					"private_ip" => $details->{"private-ip-address"},
					"port" => 4730
					);
			$masters->{$parsed->id} = $master;
		}

		return $masters;
	}

	/**
	 * retrieve server arrays containing gearman instances
	 */
	public function arrays() {
		$arrays = new StdClass();
		$raw_arrays = $this->rs->getArrayByTags("server:type=gearman_instance");
		if($raw_arrays === false) {
			return array();
		}

		foreach($raw_arrays as $raw) {
			$parsed = $this->rs->parseServer($raw);
			$array = (object) array(
					"name" => $parsed->name,
					"array_id" => $parsed->id,
					"scaling" => ($raw->active ? "automatic" : "manual"),
					"deployment_id" => $parsed->deployment_id,
					"server_template_id" => $parsed->server_template_id,
					"cur_instances" => $raw->active_instances_count,
					"min_instances" => $raw->elasticity->min_count,
					"max_instances" => $raw->elasticity->max_count
					);
			$arrays->{$parsed->id} = $array;
		}
		return $arrays;
	}

	/**
	 * retrieve server instances running gearman workers
	 */
	public function instances() {
		$instances = new StdClass();
		$ungrouped = new StdClass();

		// list all instances in arrays, necessary to get ip addresses
		$this->load->model("arrays_model");
		$arrays = $this->arrays_model->get();
		foreach($arrays as $a) {
			$array_instances = $this->rs->getServerArrayInstances($a->array_id);
			foreach($array_instances as $i) {
				$i->array_id = $a->array_id;
				$i->array_name = $a->name;
				$ungrouped->{$i->nickname} = $i;
			}
		}

		// get instances by server tag, necessary to get input variables
		$raw_instances = $this->rs->getServerByTags("server:type=gearman_instance");
		if($raw_instances === false) {
			return array();
		}

		// parse and combine instance data
		foreach($raw_instances as $raw) {
			$parsed = $this->rs->parseServer($raw);
			$parsed->master_ip = gethostbyname(str_replace("text:","",$parsed->inputs->GEARMAN_SERVER_IP));
			$parsed->master_id = $this->getMasterID($parsed->master_ip);
			$parsed->metapackage = str_replace("text:","",$parsed->inputs->GEARMAN_WORKER_PACKAGE);

			if(isset($ungrouped->{$parsed->name})) {
				$parsed->private_ip_address = $ungrouped->{$parsed->name}->private_ip_address;
				$parsed->ip_address = $ungrouped->{$parsed->name}->ip_address;
				$parsed->array_id =  $ungrouped->{$parsed->name}->array_id;
				$parsed->array_name =  $ungrouped->{$parsed->name}->array_name;
				$parsed->sketchy_base = $ungrouped->{$parsed->name}->monitoring_base_url;
				$parsed->sketchy_token = $ungrouped->{$parsed->name}->monitoring_token;
			} else {
				error_log("tagged instance not found in any server array: {$parsed->name}");
			}
			$instances->{$parsed->master_id}->{$parsed->id} = $parsed;
		}
		return $instances;
	}

	/**
	 * get graph data for a master server
	 */
	public function masterGraphs($id) {

		$graphs = (object) array(
					"cpu" => $this->rs->getSketchy($id,-3600,0,"cpu-0","cpu-idle"),
					"memory" => $this->rs->getSketchy($id,-3600,0,"memory","memory-free"),
				);
		return $graphs;
	}

	/**
	 * get graph data for an array instance
	 */
	public function instanceGraphs($base_url,$token) {
		$graphs = (object) array(
					"cpu" => $this->rs->getArrayInstanceSketchy($base_url,$token,-3600,0,"cpu-0","cpu-idle"),
					"memory" => $this->rs->getArrayInstanceSketchy($base_url,$token,-3600,0,"memory","memory-free"),
				);
		return $graphs;
	}

	/**
	 * given a private master ip address, returns master server id
	 */
	private function getMasterID($master_ip) {
		$this->load->model("masters_model");
		$masters = $this->masters_model->get();
		foreach($masters as $m) {
			if($m->private_ip == $master_ip) {
				return $m->id;
			}
		}
		return false;
	}
}
