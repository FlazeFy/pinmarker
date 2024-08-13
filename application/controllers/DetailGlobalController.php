<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailGlobalController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('GlobalListModel');

		$this->load->helper('generator_helper');
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data['is_signed'] = true;
		} else {
			$this->session->set_userdata('search_global_id', $id);
			$data['is_signed'] = false;
		}
		$data['active_page']= 'global_list';
		$data['dt_detail']= $this->GlobalListModel->get_detail_list_by_id($id);
		$data['dt_pin_list']= $this->GlobalListModel->get_pin_list_by_id($id);
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
}