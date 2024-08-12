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
			$data['active_page']= 'global_list';
			$data['dt_detail']= $this->GlobalListModel->get_detail_list_by_id($id);
			$data['dt_pin_list']= $this->GlobalListModel->get_pin_list_by_id($id);

			$data['is_mobile_device'] = is_mobile_device();

			$this->load->view('detail_global/index', $data);
		} else {
			redirect('LoginController');
		}
	}
}
