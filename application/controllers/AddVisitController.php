<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddVisitController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('DictionaryModel');
		$this->load->model('PinModel');
		$this->load->model('VisitModel');
		$this->load->model('HistoryModel');
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
		$visit_desc = $this->input->post('visit_desc');
		$pin_id_split = explode("/", $this->input->post('pin_id'));
		$pin_id = $pin_id_split[0];

		$data = [
			'id' => get_UUID(), 
			'pin_id' => $pin_id, 
			'visit_desc' => $visit_desc, 
			'visit_by' => $this->input->post('visit_by'), 
			'visit_with' => $this->input->post('visit_with'), 
			'created_at' => $this->input->post('visit_date')." ".$this->input->post('visit_hour'), 
			'created_by' => $this->session->userdata('user_id'),
			'updated_at' => null, 
		];

		$this->VisitModel->insert_visit($data);

		if($visit_desc == null){
			$pin_name = $pin_id_split[1];
			$this->HistoryModel->insert_history('Add Visit', $pin_name);
		} else {
			$this->HistoryModel->insert_history('Add Visit', $visit_desc);
		}

		redirect('historycontroller');
	}
}
