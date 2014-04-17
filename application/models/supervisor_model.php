<?php

class Supervisor_Model extends MY_Model {

	private $supervisor = null;
	private $timeout = null;

	public static $gearman_worker_group = "gearman_worker";

	/**
	 * called after loading the model to initialize the supervisor client
	 */
	public function init($master_id,$instance_id,$timeout=5) {
		$this->timeout = $timeout;
		$this->load->model("instances_model");
		$instance = $this->instances_model->get($master_id,$instance_id);
		$conf = $this->loadConfig("supervisord");
		$this->supervisor_client = new SupervisorClient($instance->private_ip_address,$conf->port,$this->timeout);
		if($this->supervisor_client->authenticate($conf->username,$conf->password) === false) {
			throw new Exception("invalid supervisor user/pass"); 
		}
	}

	/**
	 * gets all gearman worker processes, or just one if given a pid
	 */
	public function get($pid=null) {
		$ret = new StdClass();
		$all_procs = $this->supervisor_client->getAllProcessInfo();
		foreach($all_procs as $proc) {
			$proc = (object)$proc;
			if($proc->group == self::$gearman_worker_group) {
				$ret->{$proc->pid} = $this->formatProc($proc);
			}
		}

		if(is_null($pid)) {
			return $ret;
		} elseif(isset($ret->{$pid})) {
			return $ret->{$pid};
		} else {
			return false;
		}
	}

	/**
	 * restarts a process by its pid
	 */
	public function restart($pid=null) {
		if($pid > 0) {
			if(($proc = $this->get($pid)) === false) {
				throw new Exception("process not found $pid");
			}
			$name = self::$gearman_worker_group.":{$proc->idx}";
		} else {
			$name = self::$gearman_worker_group.":*";
		}
		$this->supervisor_client->stopProcess($name);
		return $this->supervisor_client->startProcess($name);
	}

	/**
	 * formats interesting process data
	 */
	public function formatProc($proc) {
        $ret = new StdClass();
        $ret->state = $proc->statename;
        $ret->pid = $proc->pid;
        $ret->uptime = ($proc->now - $proc->start);
        $ret->idx = $proc->name;
		return $ret;
	}
}
