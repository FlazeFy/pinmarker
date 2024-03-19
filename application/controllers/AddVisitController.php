<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddVisitController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('DictionaryModel');
		$this->load->model('PinModel');
		$this->load->model('VisitModel');
		$this->load->model('AuthModel');

		$this->load->helper('Generator_helper');

	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'history';
			$data['dt_dct_visit_by']= $this->DictionaryModel->get_dictionary_by_type('visit_by');
			$data['dt_all_my_pin_name']= $this->PinModel->get_all_my_pin_name();
			$this->load->view('add_visit/index', $data);
		} else {
			redirect('logincontroller');
		}
	}

	public function add_visit(){
		$data = [
			'id' => get_UUID(), 
			'pin_id' => $this->input->post('pin_id'), 
			'visit_desc' => $this->input->post('visit_desc'), 
			'visit_by' => $this->input->post('visit_by'), 
			'visit_with' => $this->input->post('visit_with'), 
			'created_at' => $this->input->post('visit_date')." ".$this->input->post('visit_hour'), 
			'created_by' => 1, // for now 
			'updated_at' => null, 
		];

		$this->VisitModel->insert_visit($data);
		redirect('historycontroller');
	}
}
