<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GearmanDashboardAPI extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->format = "json";
	}

	public function index() {
		$this->json($this,false);
	}

	/**
	 * return list of gearmand master servers
	 */
	public function Masters() {
		try {
			$this->load->model("masters_model");
			$masters = $this->masters_model->get();
		} catch(Exception $e) {
			$this->ERROR($e->getMessage());
		}

		if($masters === false) {
			$this->ERROR("Masters: not found");
		} else {
			$this->OK("Masters",$masters);
		}
	}

	/**
	 * given a master server id, 
	 * return list of server arrays associated with a particular master server
	 */
	public function Arrays($master_id,$array_id=null) {
		// TODO
		// this is to allow spaces in id (just a name string).  Will probably need to short out
		// CI's input validation, or use query string vars
		$master_id = urldecode($master_id); 
		if(!is_null($array_id)) {
			$array_id = urldecode($array_id); 
		}

		try {
			$this->load->model("arrays_model");
			$arrays = $this->arrays_model->get($master_id,$array_id);

		} catch(Exception $e) {
			$this->ERROR($e->getMessage());
		}

		if($arrays === false) {
			$this->ERROR("Arrays: not found");
		} else {
			$this->OK("Arrays",$arrays);
		}
	}

	/**
	 * given an array id, returns a list of server instances associated with a particular array
	 */
	public function Instances($array_id,$instance_id=null) {
		// TODO
		// this is to allow spaces in id (just a name string).  Will probably need to short out
		// CI's input validation, or use query string vars
		$array_id = urldecode($array_id); 
		if(!is_null($instance_id)) {
			$instance_id = urldecode($instance_id); 
		}

		try {
			$this->load->model("instances_model");
			$instances = $this->instances_model->get($array_id,$instance_id);

		} catch(Exception $e) {
			$this->ERROR($e->getMessage());
		}

		if($instances === false) {
			$this->ERROR("Instances: not found");
		} else {
			$this->OK("Instances",$instances);
		}
	}

	/**
	 * given an instance id, returns list of gearman client workers 
	 */
	public function Workers($array_id,$instance_id,$worker_id=null) {
		$array_id = urldecode($array_id); 
		$instance_id = urldecode($instance_id); 
		if(!is_null($worker_id)) {
			$worker_id = urldecode($worker_id); 
		}

		try {
			$this->load->model("instances_model");
			$this->load->model("workers_model");
			$instance = $this->instances_model->get($array_id,$instance_id);
			$workers = $this->workers_model->get($instance->priv_ip,$instance->port,$worker_id);
		} catch(Exception $e) {
			$this->ERROR($e->getMessage());
		}

		if($workers === false) {
			$this->ERROR("Workers: not found",array("instance"=>$instance));
		} else {
			$this->OK("Workers",array("instance"=>$instance,"workers"=>$workers));
		}
	}

	protected function OK($details,$data=null) {
		$this->response("OK",$details,$data);
	}

	protected function ERROR($details,$data=null) {
		$this->response("ERROR",$details,$data);
	}

	protected function response($status,$details,$data) {
		$response = array(
			"status" => $status,
			"detail" => $details,
			"data" => $data
			);
		$this->json($response);
	}

	protected function json($data) {

		switch($this->format) {
			case "jsonp":
				header("Content-type: text/javascript");
				break;
			case "json":
				header("Content-type: application/json");
				break;
		}

		echo json_encode($data)."\n";
	}
}
