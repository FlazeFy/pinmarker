<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersonController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('VisitModel');
		$this->load->model('AuthModel');

		$this->load->library('form_validation');
		$this->load->helper('generator_helper');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'maps';
			$data['is_signed'] = true;
			$data['is_mobile_device'] = is_mobile_device();
			$data['dt_person'] = $this->VisitModel->get_all_visit_with();

			$data['title_page'] = 'PinMarker | Persons';
			$data['content'] = $this->load->view('person/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}
}
