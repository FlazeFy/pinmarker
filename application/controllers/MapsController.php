<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MapsController extends CI_Controller {
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
			$data['dt_my_pin']= $this->PinModel->get_all_my_pin('maps',null,null,null);
			$data['dt_my_pin_name']= $this->PinModel->get_all_my_pin_name();
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$data['is_mobile_device'] = is_mobile_device();

			$this->load->view('maps/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function search_pin_name(){
		$this->session->set_userdata('search_pin_name_key',$this->input->post('pin_name'));
		
		redirect('/MapsController');
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
			
		redirect('/MapsController');
	}
}
