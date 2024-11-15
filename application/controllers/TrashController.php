<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrashController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('AuthModel');
		$this->load->model('HistoryModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'list';
			$data['dt_deleted_pin']= $this->PinModel->get_deleted_pin();
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;

			$data['title_page'] = 'List | Trash';
			$data['content'] = $this->load->view('trash/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function recover($id){
		$data = [
			'deleted_at' => null
		];
		if($this->PinModel->update_marker($data, $id)){
			$pin_name = $this->PinModel->get_pin_name_by_id($id);

			$history_ctx = "From $pin_name";
			$this->HistoryModel->insert_history('Recover pin', $history_ctx);
			
			$this->session->set_flashdata('message_success', generate_message(true,'recover','pin',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'recover','pin',null));
		}

		redirect('TrashController');
	}
}
