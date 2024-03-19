<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddController extends CI_Controller {

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
		$this->load->model('DictionaryModel');
		$this->load->model('PinModel');
		$this->load->model('AuthModel');

		$this->load->helper('Generator_helper');

	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'maps';
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$this->load->view('add/index', $data);
		} else {
			redirect('logincontroller');
		}
	}

	public function add_marker(){
		$split_pin_cat = explode("-", $this->input->post('pin_category'));
		$pin_cat = $split_pin_cat[0];

		$data = [
			'id' => get_UUID(), 
			'pin_name' => $this->input->post('pin_name'), 
			'pin_desc' => $this->input->post('pin_desc'), 
			'pin_lat' => $this->input->post('pin_lat'), 
			'pin_long' => $this->input->post('pin_long'), 
			'pin_category' => $pin_cat, 
			'pin_person' => $this->input->post('pin_person'), 
			'pin_call' => $this->input->post('pin_call'), 
			'pin_email' => $this->input->post('pin_email'), 
			'pin_address' => $this->input->post('pin_address'), 
			'is_favorite' => 0, 
			'created_at' => date("Y-m-d H:i:s"), 
			'created_by' => 1, // for now 
			'updated_at' => null, 
			'deleted_at' => null
		];

		$this->PinModel->insert_marker($data);
		redirect('listcontroller');
	}
}
