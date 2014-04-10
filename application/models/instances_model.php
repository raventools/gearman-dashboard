<?php

class Instances_model extends MY_Model {

	public function __construct() {
		$this->instances = $this->loadConfig("instances");
		if($this->instances === false) {
			$this->refresh();
		}
	}

	public function get($master_id=null,$instance_id=null) {
		if(!is_null($instance_id) && is_object($this->instances->$master_id)
				&& is_object($this->instances->$master_id->$instance_id)) {
			return $this->instances->$master_id->$instance_id;
		} elseif(!is_null($master_id) && is_object($this->instances->$master_id)) {
			return $this->instances->$master_id;
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
