<?php

class Instances_model extends MY_Model {

	public function __construct() {
		$this->instances = $this->loadConfig("instances");
		if($this->instances === false) {
			$this->refresh();
		}
	}

	public function get($master_private_ip=null) {
		if(!is_null($master_private_ip) && is_object($this->instances->$master_private_ip)) {
			return $this->instances->$master_private_ip;
		} elseif(is_object($this->instances)) {
			return $this->instances;
		}
		return false;
	}

	public function refresh() {
		$this->load->model("rightscale_model");
		$this->instances = $this->rightscale_model->instances();
		$this->saveConfig("instances",$this->instances);
	}
}
