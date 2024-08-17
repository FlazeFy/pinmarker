<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailGlobalController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('GlobalListModel');
		$this->load->model('PinModel');
		$this->load->model('HistoryModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');
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
		if($this->GlobalListModel->delete_global_list_rel(['id'=>$pin_id])){
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
			$this->session->set_flashdata('message_error', "Failed to add pin");
		}

		
		redirect("DetailGlobalController/view/$list_id");
	}

	public function delete_global_list($list_id){
		if($this->GlobalListModel->delete_global_list($list_id)){
			if($this->GlobalListModel->delete_global_list_rel(['list_id'=>$list_id])){
				$this->session->set_flashdata('message_success', "Successfully added$extra_msg");
			} else {
				$this->session->set_flashdata('message_error', "Failed to remove pin");
			}
		} else {
			$this->session->set_flashdata('message_error', "Failed to remove global list");
		}
		
		redirect("GlobalListController");
	}

	public function edit_toggle($id){
		$is_edit = $this->session->userdata('is_global_edit_mode');
		if($is_edit == false){
			$this->session->set_userdata('is_global_edit_mode', true);
		} else {
			$this->session->set_userdata('is_global_edit_mode', false);
		}

		redirect("/DetailGlobalController/view/$id");
	}

	public function edit_list($id){
		$rules = $this->GlobalListModel->rules(null);
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'List failed to updated. Validation failed');
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
			$user_id = $this->session->userdata('user_id');

			$data = [
				'list_name' => $this->input->post('list_name'), 
				'list_desc' => $this->input->post('list_desc'), 
				'updated_at' => date('Y-m-d H:i:s'), 
			];

			if($this->GlobalListModel->update_list($id,$data)){
				$this->HistoryModel->insert_history('Edit list', $data['list_name']);

				$this->session->set_flashdata('message_success', 'List updated');
			} else {
				$this->session->set_flashdata('message_error', 'List failed to updated');
			}
		}
		redirect("/DetailGlobalController/view/$id");
	}
}
