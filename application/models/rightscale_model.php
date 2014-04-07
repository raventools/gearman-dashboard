<?php

class Rightscale_model extends CI_Model {

	private $rs = null;
	
	public function __construct() {
		$this->load();
	}

	/**
	 * loads rightscale api client
	 */
	private function load() {
		$this->rs = new Rightscale($config->account_id,$config->username,$config->password);
	}

}
