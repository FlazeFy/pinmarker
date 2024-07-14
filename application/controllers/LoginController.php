<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->library('form_validation');
	}

    public function index()
	{
		$this->load->view('login/index');
	}

	public function login()
	{
		$rules = $this->AuthModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'Failed to login. Validation failed');
			$this->session->set_flashdata('validation_error', validation_errors());
			redirect('/LoginController');
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if($this->AuthModel->login($username, $password)){
				redirect('/DashboardController');
			} else {
				$this->session->set_flashdata('message_error', 'Failed to login. username or password incorrect');
				redirect('/LoginController');
			}
		}
	}

    public function logout()
	{
		$this->AuthModel->logout();
		redirect('LoginController');
	}
}
