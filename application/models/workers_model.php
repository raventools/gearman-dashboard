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

		if($this->fetch($master_id,$instance_id) === false) {
			return false;
		}

		if(!is_null($worker_id) && is_object($this->workers->{$worker_id})) {
			return $this->workers->{$worker_id};
		} elseif(is_object($this->workers)) {
			return $this->workers;
		}
		return false;
	}

	public function restart($master_id,$instance_id,$worker_id) {
		$this->load->model("supervisor_model");
		$this->supervisor_model->init($master_id,$instance_id);
		return $this->supervisor_model->restart($worker_id);
	}

	private function fetch($master_id,$instance_id) {

		$this->load->model("instances_model");
		$instance = $this->instances_model->get($master_id,$instance_id);
		$this->workers = new StdClass();
		$count = 0;
		$this->load->model("supervisor_model");
		$this->supervisor_model->init($master_id,$instance_id);
		$all_procs = $this->supervisor_model->get();
		if(is_null($all_procs)) {
			return false;
		}
		$registered = $this->getRegisteredWorkers($master_id,$instance->private_ip_address);
		foreach($all_procs as $proc) {
			$proc = (object)$proc;
			$proc->functions = $registered->{$proc->pid}->functions;
			$this->workers->{$proc->pid} = $proc;
			$count++;
		}
		return ($count > 0 ? $this->workers : false);
	}

	private function parseProc($proc,$registered) {
		$ret = new StdClass();
		$ret->functions = $registered->functions;
		$ret->state = $proc->statename;
		$ret->pid = $proc->pid;
		$ret->uptime = ($proc->now - $proc->start);
		$ret->idx = $proc->name;
		return $ret;
	}

	private function getRegisteredWorkers($master_id,$ip) {
		$this->load->model("gearmand_model");
		$registered = $this->gearmand_model->workers($master_id);
		return $registered->{$ip};
	}
}
