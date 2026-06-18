<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ScheduleController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'schedule';
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;

			$data['title_page'] = 'Schedule';
			$data['content'] = $this->load->view('schedule/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}
}
