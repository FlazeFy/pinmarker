<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailGlobalController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('GlobalListModel');
		$this->load->model('PinModel');

		$this->load->helper('generator_helper');
	}

	public function view($id)
	{
		$user_id = null;
		if($this->AuthModel->current_user()){
			$data['is_signed'] = true;
			$user_id = $this->session->userdata('user_id');
		} else {
			$this->session->set_userdata('search_global_id', $id);
			$data['is_signed'] = false;
		}
		$data['active_page']= 'global_list';
		$data['dt_detail']= $this->GlobalListModel->get_detail_list_by_id($id);
		$data['dt_pin_list']= $this->GlobalListModel->get_pin_list_by_id($id);
		$data['is_editable']= $this->GlobalListModel->check_pin_edit_mode($id, $user_id);
		$data['dt_available_pin'] = $this->PinModel->get_all_my_pin_name();
		$data['is_mobile_device'] = is_mobile_device();

		$this->load->view('detail_global/index', $data);
	}

	public function view_global_list_pin($id){
		if($this->session->userdata('view_mode_global_list_pin') == 'catalog'){
			$this->session->set_userdata('view_mode_global_list_pin', 'table');
		} else {
			$this->session->set_userdata('view_mode_global_list_pin', 'catalog');
		}

		redirect("DetailGlobalController/view/$id");
	}

	public function remove_pin($list_id){
		$pin_id = $this->input->post('pin_rel_id');
		if($this->GlobalListModel->delete_global_list_rel($pin_id)){
			$this->session->set_flashdata('message_success', "Successfully remove pin");
		} else {
			$this->session->set_flashdata('message_error', 'Failed to remove pin');
		}

		redirect("DetailGlobalController/view/$list_id");
	}

	public function add_pin_rel($list_id){
		$list_pin = $this->input->post('list_pin');

		if($list_pin != ''){
			$count_success = 0;
			$count_failed = 0;

			$pin_split = explode(",",$list_pin);
			foreach($pin_split as $dt){
				if($this->GlobalListModel->insert_rel([
					'id' => get_UUID(),
					'pin_id' => $dt,
					'list_id' => $list_id,
					'created_at' => date("Y-m-d H:i:s"), 
					'created_by' => $this->session->userdata('user_id')
				])){
					$count_success++;
				} else {
					$count_failed++;
				}
			}

			$extra_msg = ". With $count_success success and $count_failed failed pin attached";

			$this->session->set_flashdata('message_success', "Successfully added$extra_msg");
		} else {
			$this->session->set_flashdata('message_success', "Failed to add pin");
		}

		
		redirect("DetailGlobalController/view/$list_id");
	}
}
