<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EditController extends CI_Controller {
	protected $httpClient;
	const SESSION_KEY = 'user_id';

	function __construct(){
		parent::__construct();
		$this->load->model('DictionaryModel');
		$this->load->model('PinModel');
		$this->load->model('AuthModel');
		$this->load->helper('generator_helper');
		$this->load->library('form_validation');
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$user_id = $this->session->userdata(self::SESSION_KEY);

			$data['active_page']= 'list';
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$data['dt_detail_pin']= $this->PinModel->get_pin_by_id($id, $user_id);
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;

			$data['title_page'] = "Edit | ".$data['dt_detail_pin']->pin_name;
			$data['content'] = $this->load->view('edit/index', $data, true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}
}
