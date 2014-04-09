<?php

class Masters_model extends MY_Model {

	public function __construct() {
		$this->servers = $this->loadConfig("masters");
	}

	public function get($id=null) {
		if(!is_null($id) && is_object($this->servers->$id)) {
			return $this->servers->$id;
		} elseif(is_object($this->servers)) {
			return $this->servers;
		}
		return false;
	}

	public function refresh() {
		$this->load->model("rightscale_model");
		$this->servers = $this->rightscale_model->masters();
		$this->saveConfig("masters",$this->servers);
	}
}
