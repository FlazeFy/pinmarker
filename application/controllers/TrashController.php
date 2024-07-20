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

	public function recover($id){
		$data = [
			'deleted_at' => null
		];
		if($this->PinModel->update_marker($data, $id)){
			$this->session->set_flashdata('message_success', 'Pin successfully recover');
		} else {
			$this->session->set_flashdata('message_error', 'Pin failed to recover');
		}

		redirect('TrashController');
	}
}
