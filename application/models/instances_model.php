<?php

class Instances_model extends CI_Model {

	public function __construct() {
		$this->load();	
	}

	public function get($array_id,$instance_id=null) {
		if(!is_null($instance_id) && is_object($this->instances->$array_id) 
				&& is_object($this->instances->$array_id->$instance_id)) {

			return $this->instances->$array_id->$instance_id;
		} elseif(is_object($this->instances) && is_object($this->instances->$array_id)) {
			return $this->instances->$array_id;
		}
		return false;
	}

	private function load() {
		if(($raw_instances = file_get_contents("application/config/instances.json")) === false) {
			throw new Exception("failed to load master instance config");
		}
		if(($this->instances = json_decode($raw_instances)) === false) {
			throw new Exception("invalid json detected in master instance config");
		}
	}
}
