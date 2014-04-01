<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index() {
		$this->_header();
		$this->_backbone();
		$this->_footer();
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
