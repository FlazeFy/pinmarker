<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('PinModel');

		$this->load->helper('generator_helper');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'track';

			if($this->session->userdata('view_mode_track') == 'related_pin'){
				$data['dt_my_pin']= $this->PinModel->get_all_my_pin('maps',null,null,null);
			}

			$data['dt_get_my_pin_coor'] = $this->PinModel->get_all_my_pin_name();
			$data['is_mobile_device'] = is_mobile_device();

			$this->load->view('track/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function filter_date(){
		$this->session->set_userdata('filter_date_track',$this->input->post('filter_date_track'));

		redirect('TrackController');
	}

	public function set_view_mode(){
		$this->session->set_userdata('view_mode_track',$this->input->post('view_mode'));

		redirect('TrackController');
	}

	public function reset_filter_date(){
		$this->session->unset_userdata('filter_date_track');
		
		redirect('TrackController');
	}
}
