<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HistoryController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('VisitModel');
		$this->load->model('AuthModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'history';
			$data['dt_all_my_visit_header']= $this->VisitModel->get_all_my_visit_header();
			$this->load->view('history/index', $data);
		} else {
			redirect('logincontroller');
		}
	}
}
