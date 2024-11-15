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
			$data['active_page']= 'list';
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;

			$data['title_page'] = 'List | Add';
			$data['content'] = $this->load->view('add/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function add_marker($type){
		$success_insert = 0;
		$failed_insert = 0;

		if($type == "single"){
			$rules = $this->PinModel->rules(null);
			$this->form_validation->set_rules($rules);

			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('message_error', generate_message(false,'add','pin','validation failed'));
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
				if($this->PinModel->insert_marker($data)){
					$success_insert++;
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
				} else {
					$failed_insert++;
				}
			}
		} else if($type == "multiple"){
			$pin_names = $this->input->post('pin_name');
			$pin_descs = $this->input->post('pin_desc');
			$pin_lats = $this->input->post('pin_lat');
			$pin_longs = $this->input->post('pin_long');
			$pin_categories = $this->input->post('pin_category');

			$validation_errors = [];
			$data_to_insert = [];

			for ($i = 0; $i < count($pin_names); $i++) {
				$this->form_validation->set_data([
					'pin_name' => $pin_names[$i],
					'pin_desc' => $pin_descs[$i] ?? null,
					'pin_lat' => $pin_lats[$i],
					'pin_long' => $pin_longs[$i],
					'pin_category' => $pin_categories[$i],
					'pin_person' => null,
					'pin_call' => null,
					'pin_email' => null,
					'pin_address' => null
				]);

				$rules = $this->PinModel->rules(null);
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == FALSE) {
					$validation_errors[] = "Row " . ($i + 1) . ": " . validation_errors();
				} else {
					$split_pin_cat = explode("-", $pin_categories[$i]);
					$pin_cat = $split_pin_cat[0];

					$data_to_insert[] = [
						'id' => get_UUID(),
						'pin_name' => $pin_names[$i],
						'pin_desc' => $pin_descs[$i] ?? null,
						'pin_lat' => $pin_lats[$i],
						'pin_long' => $pin_longs[$i],
						'pin_category' => $pin_cat,
						'pin_person' => null,
						'pin_call' => null,
						'pin_email' => null,
						'pin_address' => null,
						'is_favorite' => 0,
						'created_at' => date("Y-m-d H:i:s"),
						'created_by' => $this->session->userdata('user_id'),
						'updated_at' => null,
						'deleted_at' => null
					];
				}
			}

			if (!empty($validation_errors)) {
				$this->session->set_flashdata('message_error', generate_message(false,'add','pin','validation failed'));
				$this->session->set_flashdata('validation_error', implode('<br>', $validation_errors));
				redirect('AddController');
			} else {
				foreach ($data_to_insert as $data) {
					// Insert into this App db
					if($this->PinModel->insert_marker($data)){
						$success_insert++;
						$this->HistoryModel->insert_history('Add Marker', $data['pin_name']);

						// Insert into Tracker's App db
						try {
							$response = $this->httpClient->post("/api/v1/location", [
								'form_params' => [
									'location_name' => $data['pin_name'],
									'location_desc' => $data['pin_desc']  ?? null,
									'location_lat' => $data['pin_lat'],
									'location_long' => $data['pin_long'],
									'location_category' => $data['pin_category'],
									'location_apps' => 'PinMarker',
									'location_address' => null,
								]
							]);

							if ($response->getStatusCode() != 200) {
								log_message('error', 'API request failed: ' . $response->getBody());
							}
						} catch (Exception $e) {
							log_message('error', 'API request exception: ' . $e->getMessage());
						}
					} else {
						$failed_insert++;
					}
				}
			}
		}

		if($success_insert > 0 && $failed_insert == 0){
			if($type == "multiple"){
				$this->session->set_flashdata('message_success', generate_message(true,'add','all pin',null));
			} else {
				$this->session->set_flashdata('message_success', generate_message(true,'add','pin',null));
			}
		} else if($success_insert > 0 && $failed_insert > 0){
			$this->session->set_flashdata('message_success', generate_message(true,'add',"$success_insert marker, and $failed_insert failed to add",null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'add','pin',null));
		}

		if($this->input->post("is_with_dir") == "true" && $type == "single"){
			$dir = $this->input->post('pin_lat').",".$this->input->post('pin_long');
			redirect("https://www.google.com/maps/dir/My+Location/$dir");
		} else {
			redirect('ListController');
		}
	}
}
