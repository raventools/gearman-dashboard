<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GearmanDashboardAPI extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->format = "json";
		try {
			$this->load->model("masterservers_model");
		} catch(Exception $e) {
			$this->ERROR($e->getMessage());
		}
	}

	public function index() {
		$this->json($this,false);
	}

	public function MasterServers() {
		try {
			$servers = $this->masterservers_model->get();
		} catch(Exception $e) {
			$this->ERROR($e->getMessage());
		}
		$this->OK("MasterServers",$servers);
	}

	public function WorkerServers() {
	}

	public function WorkerProcesses() {
	}

	protected function OK($details,$data=null) {
		$this->response("OK",$details,$data);
	}

	protected function ERROR($details,$data=null) {
		$this->response("ERROR",$details,$data);
	}

	protected function response($status,$details,$data) {
		$response = array(
			"status" => $status,
			"detail" => $details,
			"data" => $data
			);
		$this->json($response);
	}

	protected function json($data) {

		switch($this->format) {
			case "jsonp":
				header("Content-type: text/javascript");
				break;
			case "json":
				header("Content-type: application/json");
				break;
		}

		echo json_encode($data)."\n";
	}
}
