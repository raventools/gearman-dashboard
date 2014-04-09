<?php

class MY_Model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	protected function loadConfig($name) {

		$name = strtolower($name);
		$path = APPPATH . "config/{$name}.json";

		if (!file_exists($path)) {
			return new StdClass;
		}

		if(($json = file_get_contents($path)) === false) {
			throw new Exception("failed loading config $path");
		}
		if(($obj = json_decode($json)) === false) {
			throw new Exception("failed decoding config $path");
		}
		$this->config->$name = $obj;

		return $this->config->$name;
	}

	protected function saveConfig($name,$data) {
		$name = strtolower($name);
		$path = APPPATH . "config/{$name}.json";

		if (!file_exists($path)) {
			touch($path); // inappropriately
		}
		
		if(($json = json_encode($data)) === false) {
			throw new Exception("error while encoding json");
		}
		if(file_put_contents($path,$json) === false) {
			throw new Exception("error writing $path");
		}
		return true;
	}
}
