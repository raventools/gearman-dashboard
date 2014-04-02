<?php
require_once("application/libraries/Supervisorclient.php");

class Workers_model extends CI_Model {

	private $timeout = 10;

	public function __construct($timeout=null) {
		if(!is_null($timeout)) {
			$this->timeout = $timeout;
		}
	}

	public function get($ip,$port,$worker_id=null) {
		$this->load($ip,$port);

		if(!is_null($worker_id) && is_object($this->workers->$array_id)) {
			return $this->workers->$worker_id;
		} elseif(is_object($this->workers)) {
			return $this->workers;
		}
		return false;
	}

	private function load($ip,$port) {

		$supervisor = new SupervisorClient($ip,$port,$this->timeout);
		$this->workers = $supervisor->getAllProcessInfo();
		var_dump($supervisor->getAPIVersion());
		var_dump($this->workers);
	}
}
