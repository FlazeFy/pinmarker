<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyProfileController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
	}

	public function index()
	{
		$data = [];
		$data['dt_my_profile'] = $this->AuthModel->current_user();
		if($data['dt_my_profile']){
			$data['active_page']= 'myprofile';

			$this->load->view('myprofile/index', $data);
		} else {
			redirect('logincontroller');
		}
	}

	public function edit_profile(){
		$user_id = $this->session->userdata('user_id');

		$data = [
			'fullname' => $this->input->post('fullname'), 
			'username' => $this->input->post('username'), 
			'email' => $this->input->post('email'), 
			'updated_at' => date('Y-m-d H:i:s'), 
		];

		$this->AuthModel->update_user($user_id,$data);

		redirect('myprofilecontroller');
	}
}
