<?php

/**
 * bogus worker controller for testing purposes
 */

class GearmanWorkerController extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function work($my_ip) {
		$worker = new GearmanWorker();
		$worker->addServer("127.0.0.1");
		$worker->addFunction("GearmanWorkerController/doJob",array($this,"doJob"));
		$worker->setTimeout(1000);
		$worker->setID(getmypid()); // have to setid after addserver

		while($worker->work() || $worker->returnCode() == GEARMAN_TIMEOUT) {
			if(@$worker->echo("ping") === false) {
				// die slowly if server goes away
				echo "server gone away\n";
				sleep(5);
				exit(1);
			}
			sleep(1);
		}
	}

	public function doJob() {
		sleep(1);
	}
}
