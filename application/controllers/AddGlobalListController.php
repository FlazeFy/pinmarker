<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class AddGlobalListController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('GlobalListModel');
		$this->load->model('HistoryModel');
		$this->load->model('PinModel');
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
			$data['dt_available_pin'] = $this->PinModel->get_all_my_pin_name();
			$data['is_signed'] = true;

			$data['title_page'] = 'Global List | Add';
			$data['content'] = $this->load->view('add_global_list/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function add_list(){
		$rules = $this->GlobalListModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', generate_message(false,'add','global list','validation failed'));
			$this->session->set_flashdata('validation_error', validation_errors());
			redirect('AddGlobalListController');
		} else {
			$list_name = $this->input->post('list_name');
			$list_code = null;
			$list_tag = null;
			$id = get_UUID();
			
			if($this->input->post('list_code') != ""){
				$list_code = $this->input->post('list_code');
			}
			if($this->input->post('list_tag') != ""){
				$list_tag = $this->input->post('list_tag');
			}

			$data = [
				'id' => $id, 
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
				$list_pin = $this->input->post('list_pin');
				$extra_msg = "";

				if($list_pin != ''){
					$count_success = 0;
					$count_failed = 0;

					$pin_split = explode(",",$list_pin);
					foreach($pin_split as $dt){
						if($this->GlobalListModel->insert_rel([
							'id' => get_UUID(),
							'pin_id' => $dt,
							'list_id' => $id,
							'created_at' => date("Y-m-d H:i:s"), 
							'created_by' => $this->session->userdata('user_id')
						])){
							$count_success++;
						} else {
							$count_failed++;
						}
					}

					$extra_msg = ". With $count_success success and $count_failed failed pin attached";
				}

				$this->session->set_flashdata('message_success', generate_message(true,'add','global list',$extra_msg));
				redirect('GlobalListController');
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'add','global list',null));
				redirect('AddGlobalListController');
			}
		}
	}
}
