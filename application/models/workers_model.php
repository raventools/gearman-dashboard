<?php

class Workers_model extends MY_Model {

	private $timeout = 10;

	public static $gearman_worker_group = "gearman_worker";

	public function __construct($timeout=null) {
		if(!is_null($timeout)) {
			$this->timeout = $timeout;
		}
	}

	public function get($ip,$port,$worker_id=null) {
		if($this->load($ip,$port) === false) {
			return false;
		}

		if(!is_null($worker_id) && is_object($this->workers[$worker_id])) {
			return $this->workers[$worker_id];
		} elseif(is_array($this->workers)) {
			return $this->workers;
		}
		return false;
	}

	private function load($ip,$port) {
		$supervisor = new SupervisorClient($ip,$port,$this->timeout);
		try {
			$auth = $this->loadConfig("supervisord");
			if($supervisor->authenticate($auth->user,$auth->password) === false) {
				throw new Exception("invalid supervisor user/pass");
			}
		} catch(Exception $e) {
			error_log("supervisor auth exception ".$e->getMessage());
		}
		$this->workers = array();
		$all_procs = $supervisor->getAllProcessInfo();
		if(is_null($all_procs)) {
			return false;
		}
		foreach($all_procs as $proc) {
			$proc = (object)$proc;
			if($proc->group == self::$gearman_worker_group) {
				$this->workers[] = $proc;
			}
		}
		return (count($this->workers) > 0 ? true : false);
	}

	private function getAuth() {
		if(($json = file_get_contents("application/config/supervisord.json")) === false) {
			throw new Exception("supervisord.json not found");
		}
		if(($auth = json_decode($json)) === false) {
			throw new Exception("invalid json in supervisord.json");
		}
		return $auth;
	}
}
