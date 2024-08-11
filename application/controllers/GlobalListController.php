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

			$data['active_page']= 'global_list';
			$data['dt_global_list']= $this->GlobalListModel->get_global_list();
			$data['is_mobile_device'] = is_mobile_device();

			$this->load->view('global/index', $data);
		} else {
			redirect('LoginController');
		}
	}
}
