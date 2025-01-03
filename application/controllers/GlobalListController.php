<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalListController extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('AuthModel');
		$this->load->model('GlobalListModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			if(!$this->session->userdata('view_mode_global_list_pin')){
				$this->session->set_userdata('view_mode_global_list_pin', 'catalog');
			}

			$data['is_signed'] = true;
			$data['active_page']= 'global_list';
			$data['dt_global_list']= $this->GlobalListModel->get_global_list("");
			$data['is_mobile_device'] = is_mobile_device();

			$data['title_page'] = 'PinMarker | Global List';
			$data['content'] = $this->load->view('global/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}
}
