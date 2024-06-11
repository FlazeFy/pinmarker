<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('PinModel');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'track';
			$data['dt_get_my_pin_coor'] = $this->PinModel->get_all_my_pin_name();
			$this->load->view('track/index', $data);
		} else {
			redirect('logincontroller');
		}
	}

	public function filter_date(){
		$this->session->set_userdata('filter_date_track',$this->input->post('filter_date_track'));

		redirect('trackcontroller');
	}

	public function reset_filter_date(){
		$this->session->unset_userdata('filter_date_track');
		
		redirect('trackcontroller');
	}
}
