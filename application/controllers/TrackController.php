<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'track';
			$this->load->view('track/index', $data);
		} else {
			redirect('logincontroller');
		}
	}
}
