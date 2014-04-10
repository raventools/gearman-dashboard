<?php

class Workers_model extends MY_Model {

	private $timeout = 10;

	public static $gearman_worker_group = "gearman_worker";

	public function __construct($timeout=null) {
		if(!is_null($timeout)) {
			$this->timeout = $timeout;
		}
	}

	public function get($master_id,$instance_id,$worker_id=null) {

		$this->load->model("instances_model");
		$instance = $this->instances_model->get($master_id,$instance_id);
		$supervisord = $this->loadConfig("supervisord");
//		if($this->fetch($instance->private_ip_address,$supervisord->port) === false) {
		if($this->fetch("127.0.0.1",$supervisord->port) === false) {
			return false;
		}

		if(!is_null($worker_id) && is_object($this->workers[$worker_id])) {
			return $this->workers[$worker_id];
		} elseif(is_array($this->workers)) {
			return $this->workers;
		}
		return false;
	}

	private function fetch($ip,$port) {
		$supervisor = new SupervisorClient($ip,$port,$this->timeout);
		try {
			$supervisord = $this->loadConfig("supervisord");
			if($supervisor->authenticate($supervisord->username,$supervisord->password) === false) {
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
}
