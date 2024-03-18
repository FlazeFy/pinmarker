<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MapsController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('DictionaryModel');
	}

	public function index()
	{
		$data = [];
		$data['active_page']= 'maps';
		$data['dt_my_pin']= $this->PinModel->get_all_my_pin('maps');
		$data['dt_my_pin_name']= $this->PinModel->get_all_my_pin_name();
		$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
		$this->load->view('maps/index', $data);
	}

	public function search_pin_name(){
		$this->session->set_userdata('search_pin_name_key',$this->input->post('pin_name'));
		
		redirect('/mapscontroller');
	}

	public function reset_search_pin_name(){
		$this->session->unset_userdata('search_pin_name_key');
			
		redirect('/mapscontroller');
	}
}
