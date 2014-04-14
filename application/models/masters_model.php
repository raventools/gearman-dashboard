<?php

class Masters_model extends MY_Model {

	public function __construct() {
		$this->servers = $this->loadConfig("masters");
		if($this->servers === false) {
			$this->refresh();
		}
	}

	public function get($id=null) {
		if(!is_null($id) && is_object($this->servers->$id)) {
			return $this->servers->$id;
		} elseif(is_object($this->servers)) {
			return $this->servers;
		}
		return false;
	}

	public function graphs($id) {
		$this->load->model("rightscale_model");
		return $this->rightscale_model->masterGraphs($id);
	}

	public function refresh() {
		$this->load->model("rightscale_model");
		$this->servers = $this->rightscale_model->masters();
		$this->saveConfig("masters",$this->servers);
	}
}
