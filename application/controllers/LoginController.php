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

		$data['title_page'] = 'PinMarker | Marks Your World';
		$data['content'] = $this->load->view('login/index',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function view($search)
	{
		$data = [];
		$search = str_replace('%20', ' ', $search);

		$data['dt_global'] = $this->GlobalListModel->get_global_list($search);
		$data['dt_total_pin'] = $this->PinModel->get_total_all();
		$data['dt_total_user'] = $this->AuthModel->get_total_all();
		$data['dt_total_visit'] = $this->VisitModel->get_total_all();
		$data['dt_active_search'] = $search;
		$data['is_mobile_device'] = is_mobile_device();

		$data['title_page'] = 'PinMarker | Marks Your World';
		$data['content'] = $this->load->view('login/index',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function login()
	{
		$rules = $this->AuthModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', generate_message(false,'login','to your account','validation failed'));
			$this->session->set_flashdata('validation_error', validation_errors());
			redirect('/LoginController');
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if($this->AuthModel->login($username, $password)){
				redirect('/DashboardController');
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'login','to your account','wrong username or password'));
				redirect('/LoginController#login-section');
			}
		}
	}

    public function logout()
	{
		$this->AuthModel->logout();
		redirect('LoginController');
	}
}
