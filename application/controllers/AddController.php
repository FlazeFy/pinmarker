<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('DictionaryModel');
		$this->load->model('PinModel');
		$this->load->model('AuthModel');
		$this->load->model('HistoryModel');

		$this->load->helper('Generator_helper');

	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'maps';
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$this->load->view('add/index', $data);
		} else {
			redirect('logincontroller');
		}
	}

	public function add_marker(){
		$split_pin_cat = explode("-", $this->input->post('pin_category'));
		$pin_cat = $split_pin_cat[0];
		$pin_name = $this->input->post('pin_name');

		$data = [
			'id' => get_UUID(), 
			'pin_name' => $pin_name, 
			'pin_desc' => $this->input->post('pin_desc'), 
			'pin_lat' => $this->input->post('pin_lat'), 
			'pin_long' => $this->input->post('pin_long'), 
			'pin_category' => $pin_cat, 
			'pin_person' => $this->input->post('pin_person'), 
			'pin_call' => $this->input->post('pin_call'), 
			'pin_email' => $this->input->post('pin_email'), 
			'pin_address' => $this->input->post('pin_address'), 
			'is_favorite' => 0, 
			'created_at' => date("Y-m-d H:i:s"), 
			'created_by' => $this->session->userdata('user_id'),
			'updated_at' => null, 
			'deleted_at' => null
		];

		$this->PinModel->insert_marker($data);
		$this->HistoryModel->insert_history('Add Marker', $pin_name);

		redirect('listcontroller');
	}
}
