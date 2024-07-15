<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrashController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('AuthModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'trash';
			$data['dt_deleted_pin']= $this->PinModel->get_deleted_pin();
			$data['is_mobile_device'] = is_mobile_device();

			$this->load->view('trash/index', $data);
		} else {
			redirect('LoginController');
		}
	}
}
