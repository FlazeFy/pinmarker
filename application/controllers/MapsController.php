<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MapsController extends CI_Controller {
	const SESSION_KEY = 'user_id';

	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('DictionaryModel');
		$this->load->model('AuthModel');

		$this->load->library('form_validation');
		$this->load->helper('generator_helper');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'maps';
			$data['is_signed'] = true;
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$data['dt_pin_category']= $this->PinModel->get_pin_category($user_id);
			$data['is_mobile_device'] = is_mobile_device();

			$data['title_page'] = 'PinMarker | Maps';
			$data['content'] = $this->load->view('maps/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function search_pin_name(){
		$this->session->set_userdata('search_pin_name_key',$this->input->post('pin_name'));
		
		redirect("/".$this->input->post('page')."Controller");
	}

	public function filter_pin_category(){
		$cat = $this->input->post('pin_category');
		if($cat == "all"){
			$this->session->unset_userdata('filter_pin_by_cat');
		} else {
			$this->session->set_userdata('filter_pin_by_cat',$cat);
		}
		
		redirect('/MapsController');
	}

	public function reset_search_pin_name(){
		$this->session->unset_userdata('search_pin_name_key');
			
		redirect("/".$this->input->post('page')."Controller");
	}
}
