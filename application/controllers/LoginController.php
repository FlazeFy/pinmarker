<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('PinModel');
		$this->load->model('VisitModel');
		$this->load->model('GlobalListModel');

        $this->load->library('form_validation');
		$this->load->helper('generator_helper');
	}

    public function index()
	{
		$data = [];
		$data['dt_total_pin'] = $this->PinModel->get_total_all();
		$data['dt_total_user'] = $this->AuthModel->get_total_all();
		$data['dt_total_visit'] = $this->VisitModel->get_total_all();
		$data['dt_active_search'] = null;
		$data['is_mobile_device'] = is_mobile_device();

		$this->load->view('login/index',$data);
	}

	public function view($search)
	{
		$data = [];
		$data['dt_global'] = $this->GlobalListModel->get_global($search);
		$data['dt_total_pin'] = $this->PinModel->get_total_all();
		$data['dt_total_user'] = $this->AuthModel->get_total_all();
		$data['dt_total_visit'] = $this->VisitModel->get_total_all();
		$data['dt_active_search'] = $search;
		$data['is_mobile_device'] = is_mobile_device();

		$this->load->view('login/index',$data);
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
