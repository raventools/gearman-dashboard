<?php

class Arrays_model extends CI_Model {

	public function __construct() {
		$this->load();	
	}

	public function get($master_id, $array_id=null) {
		if(!is_null($array_id) 
				&& is_object($this->arrays->$master_id) 
				&& is_object($this->arrays->$master_id->$array_id)) {
			return $this->arrays->$master_id->$array_id;
		} elseif(is_object($this->arrays->$master_id)) {
			return $this->arrays->$master_id;
		}
		return false;
	}

	private function load() {
		if(($raw_arrays = file_get_contents("application/config/arrays.json")) === false) {
			throw new Exception("failed to load master array config");
		}
		if(($this->arrays = json_decode($raw_arrays)) === false) {
			throw new Exception("invalid json detected in master array config");
		}
	}
}
