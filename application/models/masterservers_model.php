<?php

class MasterServers_model extends CI_Model {

	public function __construct() {
		$this->load();	
	}

	public function get($id=null) {
		if(!is_null($id) && is_object($this->servers->$id)) {
			return $this->servers->$id;
		} elseif(is_object($this->servers)) {
			return $this->servers;
		}
		return false;
	}

	private function load() {
		if(($raw_servers = file_get_contents("application/config/master_servers.json")) === false) {
			throw new Exception("failed to load master server config");
		}
		if(($this->servers = json_decode($raw_servers)) === false) {
			throw new Exception("invalid json detected in master server config");
		}
	}
}
