<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	function __construct() {
		parent::__construct();

		$this->load->model('dashboard_model', 'dashboard_model');
	}

	public function index() {
		$this->_header();
		$this->_backbone();
		$this->_templates();
		$this->_footer();
	}

	public function api() {
		$method = $this->input->get('method');

		$response = $this->dashboard_model->dummyApi($method);

		$this->echoJSON($response);
	}

	private function _header() {
		$this->load->view('core/header');
	}

	private function _templates() {
		$template_directory = 'assets/templates/';
		$templates = array();

		foreach (glob($template_directory . '*.tpl') as $template) {
			$templates[] = basename($template);
		}

		$formattedTemplates = array();

		foreach ($templates as $template) {
			$key = preg_replace("/\..*$/", '', $template);

			$formattedTemplates[$key] = file_get_contents($template_directory . $template);

			// filter out some extras
			$formattedTemplates[$key] = preg_replace("/(?<=\>|\})(\n|\t)+/", '', $formattedTemplates[$key]);
			$formattedTemplates[$key] = urlencode($formattedTemplates[$key]);
		}

		$data = array(
			'templates' => $formattedTemplates
		);

		$this->load->view('core/templates', $data);
	}

	private function _backbone() {
		$this->load->view('core/backbone');
	}

	private function _footer() {
		$this->load->view('core/footer');
	}
}
