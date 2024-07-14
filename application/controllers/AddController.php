<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class AddController extends CI_Controller {
	protected $httpClient;

	function __construct(){
		parent::__construct();
		$this->load->model('DictionaryModel');
		$this->load->model('PinModel');
		$this->load->model('AuthModel');
		$this->load->model('HistoryModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');
		
		$this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:1323',
            'http_errors' => false
        ]);
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'maps';
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$this->load->view('add/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function add_marker(){
		$rules = $this->PinModel->rules(null);
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'Pin failed to created. Validation failed');
			$this->session->set_flashdata('validation_error', validation_errors());
			redirect('AddController');
		} else {
			$split_pin_cat = explode("-", $this->input->post('pin_category'));
			$pin_cat = $split_pin_cat[0];
			$pin_name = $this->input->post('pin_name');

			$data = [
				'id' => get_UUID(), 
				'pin_name' => $pin_name, 
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
				'created_by' => $this->session->userdata('user_id'),
				'updated_at' => null, 
				'deleted_at' => null
			];

			// This App db
			$this->PinModel->insert_marker($data);
			$this->HistoryModel->insert_history('Add Marker', $pin_name);

			// Tracker's App db
			try {
				$response = $this->httpClient->post("/api/v1/location", [
					'form_params' => [
						'location_name' => $data['pin_name'],
						'location_desc' => $data['pin_desc'],
						'location_lat' => $data['pin_lat'],
						'location_long' => $data['pin_long'],
						'location_category' => $data['pin_category'],
						'location_apps' => 'PinMarker',
						'location_address' => $data['pin_address'],
					]
				]);

				if ($response->getStatusCode() == 200) {
				} else {
					log_message('error', 'API request failed: ' . $response->getBody());
				}
			} catch (Exception $e) {
				log_message('error', 'API request exception: ' . $e->getMessage());
			}

			redirect('ListController');
		}
	}
}
