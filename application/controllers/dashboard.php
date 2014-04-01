<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	public function index() {
		$this->_header();
		$this->_backbone();
		$this->_footer();
	}

	public function api() {
		$method = $this->input->get('method');

		$response = array();

		switch ($method) {
			case 'getServerList':
				$response = array(
					'servers' => array(
						array(
							'name' => 'Test Server 1',
							'stats' => array(
								'cpu' => '80%',
								'health' => '44'
							)
						),
						array(
							'name' => 'Test Server 2',
							'stats' => array(
								'cpu' => '30%',
								'health' => '85'
							)
						),
						array(
							'name' => 'Test Server 3',
							'stats' => array(
								'cpu' => '53%',
								'health' => '71'
							)
						)
					)
				);

				break;
		}

		$this->echoJSON($response);
	}

	private function _header() {
		$this->load->view('core/header');
	}

	private function _backbone() {
		$this->load->view('core/backbone');
	}

	private function _footer() {
		$this->load->view('core/footer');
	}
}
