<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class AddGlobalListController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('GlobalListModel');
		$this->load->model('HistoryModel');
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');
		
		$this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:1323',
            'http_errors' => false
        ]);
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'global_list';
			$data['is_mobile_device'] = is_mobile_device();
			$data['dt_global_tag'] = $this->GlobalListModel->get_global_tag();
			$data['is_signed'] = true;

			$this->load->view('add_global_list/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function add_list(){
		$rules = $this->GlobalListModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'Global List failed to add. Validation failed');
			$this->session->set_flashdata('validation_error', validation_errors());
			redirect('AddGlobalListController');
		} else {
			$list_name = $this->input->post('list_name');
			$list_code = null;
			$list_tag = null;
			
			if($this->input->post('list_code') != ""){
				$list_code = $this->input->post('list_code');
			}
			if($this->input->post('list_tag') != ""){
				$list_tag = $this->input->post('list_tag');
			}

			$data = [
				'id' => get_UUID(), 
				'list_code' => $list_code,  
				'list_name' => $list_name, 
				'list_desc' => $this->input->post('list_desc'), 
				'list_tag' => $list_tag,
				'created_at' => date("Y-m-d H:i:s"), 
				'created_by' => $this->session->userdata('user_id'),
				'updated_at' => null, 
			];

			if($this->GlobalListModel->insert($data)){
				$this->HistoryModel->insert_history('Add Global List', $list_name);

				$this->session->set_flashdata('message_success', 'Global List successfully added');
				redirect('GlobalListController');
			} else {
				$this->session->set_flashdata('message_failed', 'Global List failed to created');
				redirect('AddGlobalListController');
			}
		}
	}
}
