<?php

use RavenTools\RightscaleAPIClient\Client as RSClient;

class Rightscale_model extends MY_Model {

	private $rs_client = null;
	
	public function __construct() {
		parent::__construct();
	}

	/**
	 * allows us to only init rs client if we need it
	 */
	public function RSClient() {
		if(is_null($this->rs_client)) {
			$config = $this->loadConfig("rightscale");
			$this->rs_client = new RSClient(array(
							"account_id" => $config->account_id,
							"email" => $config->username,
							"password" => $config->password
					));
		}
		return $this->rs_client;
	}

	/**
	 * retrieve servers running gearmand
	 */
	public function masters() {
		$masters = new StdClass();

		$tag = "server:type=gearman_master";
		$raw_servers = $this->getResourcesByTags("servers", array($tag));

		if(empty($raw_servers)) {
			return array();
		}

		foreach($raw_servers as $raw) {
			$detail = $raw->current_instance()->show();
			$master = (object) array(
					"name" => $raw->name,
					"href" => $raw->href,
					"public_ip" => $detail->public_ip_addresses[0],
					"private_ip" => $detail->private_ip_addresses[0],
					"port" => 4730
					);
			$masters->{$raw->href} = $master;
		}

		return $masters;
	}

	/**
	 * retrieve server arrays containing gearman instances
	 */
	public function arrays() {
		$arrays = new StdClass();
		$raw_arrays = $this->getArrayByTags(array("server:type=gearman_instance"));
		if(empty($raw_arrays)) {
			return array();
		}

		foreach($raw_arrays as $raw) {
			$detail = $raw->show(array("view"=>"instance_detail"));
			$array = (object) array(
					"name" => $raw->name,
					"href" => $raw->href,
					"scaling" => ($raw->state == "disabled" ? "automatic" : "manual"),
					"deployment" => $raw->deployment()->show()->href,
					"server_template" => $detail->next_instance()->show()->server_template()->show()->href,
					"cur_instances" => $raw->instances_count,
					"min_instances" => $raw->elasticity_params->bounds->min_count,
					"max_instances" => $raw->elasticity_params->bounds->max_count
					);
			$arrays->{$raw->href} = $array;
		}
		return $arrays;
	}

	/**
	 * retrieve server instances running gearman workers
	 */
	public function instances() {
		$instances = new StdClass();

		// list all instances in arrays, necessary to get ip addresses
		$this->load->model("arrays_model");
		$arrays = $this->arrays_model->get();
		foreach($arrays as $a) {
			$array_instances = $this->getArrayInstances($a->href);
			foreach($array_instances as $i) {
				$instance = new StdClass();
				$instance->name = $i->name;
				$instance->href = $i->href;
				$instance->private_ip = $i->private_ip_addresses[0];
				$instance->public_ip = $i->public_ip_addresses[0];
				$instance->created_at = $i->created_at;
				$instances->{$a->href}->{$i->href} = $instance;
			}
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

	private function getResourcesByTags($resource_type, Array $tags) {

		$response = $this->RSClient()->tags()->by_tag(array(
							"resource_type" => $resource_type,
							"tags" => $tags
						));
		$servers = array();
		foreach($response as $detail) {
			$resources = $detail->resource();
			foreach($resources as $r) {
				$rs_ob = $r->show();

				$servers[] = $rs_ob;
			}
		}
		return $servers;
	}

	private function getArrayByTags(Array $tags) {
		$response = $this->RSClient()->tags()->by_tag(array(
							"resource_type" => "server_arrays",
							"tags" => $tags
						));

		$arrays = array();
		foreach($response as $detail) {
			$resources = $detail->resource();
			foreach($resources as $r) {
				$rs_ob = $r->show();

				$arrays[] = $rs_ob;
			}
		}
		return $arrays;
	}

	private function getArrayInstances($href) {
		$id = basename($href);
		$response = $this->RSClient()->server_arrays(array("id"=>$id))->show()->current_instances()->index();
		$instances = array();
		foreach($response as $instance) {
			$instances[$instance->href] = $instance;
		}
		return $instances;
	}

}
