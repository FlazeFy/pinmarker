<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('AuthModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'list';
			$data['dt_my_pin']= $this->PinModel->get_all_my_pin('list');
			$this->load->view('list/index', $data);
		} else {
			redirect('logincontroller');
		}
	}
}
