<?php

class Gearmand_model extends MY_Model {

	public static $timeout = 10;

	public function queues($master_id) {

		$this->queues = new StdClass();
		$lines = $this->command($master_id,"status");

		foreach($lines as $line) {
			$queue = new StdClass();
			list($queue->name,$queue->waiting,$queue->running,$queue->workers) = sscanf($line,"%s %s %s %s");
			$this->queues->{$queue->name} = $queue;
		}

		return $this->queues;
	}

	public function workers($master_id) {
		$this->workers = new StdClass();
		$lines = $this->command($master_id,"workers");

		foreach($lines as $line) {
			$worker = new StdClass();
			list($info, $endpoints) = explode(" : ", $line);
			list($worker->fd, $worker->ip, $worker->id) = explode(" ", $info);
			$worker->endpoints = explode(" ",$endpoints);

			$this->workers->{$worker->ip}[] = $worker;
		}

		return $this->workers;
	}

	private function command($master_id,$cmd) {

		$this->load->model("masters_model");
		$master = $this->masters_model->get($master_id);

		$this->conn = @fsockopen($master->private_ip, $master->port, $errCode, $errMsg, self::$timeout);
		if ($this->conn === false) {
			error_log("error connecting to gearmand $master_id ip:{$master->private_ip} [{$errCode}:{$errMsg}]");
			return false;
		}

		$lines = array();
		$cmd = "$cmd\r\n";
		$written = fwrite($this->conn,$cmd,mb_strlen($cmd));
		while($line = fgets($this->conn,1024)) {
			if(strpos($line,".") === 0) break;
			$lines[] = $line;
		}
		fclose($this->conn);
		return $lines;
	}

}
