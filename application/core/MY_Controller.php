<?php

class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	protected function echoJSON($data) {
		$data = (array) $data;

		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: application/json');

		echo json_encode( $data ) . "\n";
		
		exit;
	}
}
