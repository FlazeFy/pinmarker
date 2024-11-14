<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class HelpController extends CI_Controller {
	protected $httpClient;

	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');
	}

	public function index()
	{
		$data = [];
		if($this->AuthModel->current_user()){
			$data['is_signed'] = true;
		} else {
			$data['is_signed'] = false;
		}
		$data['active_page']= 'help';
		$data['is_mobile_device'] = is_mobile_device();

		$this->load->view('help/index', $data);
	}
}
