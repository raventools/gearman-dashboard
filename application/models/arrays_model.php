<?php

class Arrays_model extends MY_Model {

	public function __construct() {
		$this->arrays = $this->loadConfig("arrays");
		if($this->arrays === false) {
			$this->refresh();
		}
	}

	public function get($array_id=null) {
		if(!is_null($array_id) && is_object($this->arrays) && is_object($this->arrays->$array_id)) {
			return $this->arrays->$array_id;
		} elseif(is_object($this->arrays)) {
			return $this->arrays;
		}
		return false;
	}

	public function refresh() {
		$this->load->model("rightscale_model");
		$this->arrays = $this->rightscale_model->arrays();
		$this->saveConfig("arrays",$this->arrays);
	}
}
