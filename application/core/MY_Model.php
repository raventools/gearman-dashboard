<?php

class MY_Model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	protected function loadConfig($name) {

		$name = strtolower($name);
		$path = APPPATH . "/config/{$name}.json";
		if(($json = file_get_contents($path)) === false) {
			throw new Exception("failed loading config $path");
		}
		if(($obj = json_decode($json)) === false) {
			throw new Exception("failed decoding config $path");
		}
		$this->config->$name = $obj;

		return $this->config->$name;
	}
}
