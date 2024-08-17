<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class AddVisitController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('DictionaryModel');
		$this->load->model('PinModel');
		$this->load->model('VisitModel');
		$this->load->model('HistoryModel');
		$this->load->model('AuthModel');

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
			$data['active_page']= 'history';
			$data['dt_dct_visit_by']= $this->DictionaryModel->get_dictionary_by_type('visit_by');
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$data['dt_all_my_pin_name']= $this->PinModel->get_all_my_pin_name();
			$data['dt_my_contact']= $this->PinModel->get_person_in_contact();
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;

			$this->load->view('add_visit/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function add_visit(){
		if($this->input->post('type_add') != 'multi'){
			$rules = $this->VisitModel->rules(null);
			$this->form_validation->set_rules($rules);

			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('message_error', 'Visit failed to add. Validation failed');
				$this->session->set_flashdata('validation_error', validation_errors());
				redirect('AddVisitController');
			} else {
				if($this->input->post('type_add') == 'pin_visit'){
					$rules = $this->PinModel->rules(null);
					$this->form_validation->set_rules($rules);

					if($this->form_validation->run() == FALSE){
						$this->session->set_flashdata('message_error', 'Pin failed to created. Validation failed');
						$this->session->set_flashdata('validation_error', validation_errors());
						redirect('AddVisitController');
					} else {
						// Add marker
						$split_pin_cat = explode("-", $this->input->post('pin_category'));
						$pin_cat = $split_pin_cat[0];
						$pin_name = $this->input->post('pin_name');
						$pin_id = get_UUID();

						$data = [
							'id' => $pin_id, 
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
					}
				} else if($this->input->post('type_add') == 'visit_custom'){
					$pin_id = null;
					$visit_desc = $this->input->post('visit_desc')." at ".$this->input->post('location_name');
				}

				// Add visit

				if($this->input->post('type_add') != 'visit_custom'){
					$visit_desc = $this->input->post('visit_desc');
				}
				$pin_id_split = explode("/", $this->input->post('pin_id'));
				if($this->input->post('type_add') == 'visit'){
					$pin_id = $pin_id_split[0];
				}

				$data = [
					'id' => get_UUID(), 
					'pin_id' => $pin_id, 
					'visit_desc' => $visit_desc, 
					'visit_by' => $this->input->post('visit_by'), 
					'visit_with' => $this->input->post('visit_with'), 
					'created_at' => $this->input->post('visit_date')." ".$this->input->post('visit_hour'), 
					'created_by' => $this->session->userdata('user_id'),
					'updated_at' => null, 
				];

				if($this->VisitModel->insert_visit($data)){
					if($visit_desc == null){
						$pin_name = $pin_id_split[1];
						$this->HistoryModel->insert_history('Add Visit', $pin_name);
					} else {
						$this->HistoryModel->insert_history('Add Visit', $visit_desc);
					}

					$this->session->set_flashdata('message_success', 'Visit successfully added');
				}

				redirect('HistoryController');
			}
		} else {
			$success_insert = 0;
			$failed_insert = 0;

			$visit_descs = $this->input->post('visit_desc');
			$visit_bys = $this->input->post('visit_by');
			$visit_withs = $this->input->post('visit_with');

			$validation_errors = [];
			$data_to_insert = [];
			$pin_names = [];

			for($i = 0; $i < count($visit_descs); $i++){
				$this->form_validation->set_data([
					'visit_desc' => $visit_descs[$i],
					'visit_with' => $visit_withs[$i],
					'visit_by' => $visit_bys[$i],
				]);

				$rules = $this->VisitModel->rules(null);
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == FALSE) {
					$validation_errors[] = "Row " . ($i + 1) . ": " . validation_errors();
				} else {
					$pin_id_split = explode("/", $this->input->post('pin_id')[$i]);
					$pin_id = $pin_id_split[0];
					$pin_name = $pin_id_split[1];
					array_push($pin_names,$pin_name);

					$data_to_insert[] = [
						'id' => get_UUID(), 
						'pin_id' => $pin_id, 
						'visit_desc' => $visit_descs[$i],
						'visit_with' => $visit_withs[$i],
						'visit_by' => $visit_bys[$i],
						'created_at' => $this->input->post('visit_date')[$i]." ".$this->input->post('visit_hour')[$i],
						'created_by' => $this->session->userdata('user_id'),
						'updated_at' => null, 
					];
				}
			}

			if (!empty($validation_errors)) {
				$this->session->set_flashdata('message_error', 'Visits failed to be created. Validation failed');
				$this->session->set_flashdata('validation_error', implode('<br>', $validation_errors));
				redirect('AddVisitController');
			} else {
				foreach ($data_to_insert as $idx => $data) {
					if($this->VisitModel->insert_visit($data)){
						$success_insert++;
						$this->HistoryModel->insert_history('Add Visit', $pin_names[$idx]);
					} else {
						$failed_insert++;
					}
				}

				if($success_insert > 0 && $failed_insert == 0){
					if($type == "multiple"){
						$this->session->set_flashdata('message_success', 'Successfully add all visit');
					} else {
						$this->session->set_flashdata('message_success', 'Successfully add visit');
					}
				} else if($success_insert > 0 && $failed_insert > 0){
					$this->session->set_flashdata('message_success', "Successfully add $success_insert visit, and $failed_insert failed to add");
				} else {
					$this->session->set_flashdata('message_error', 'Failed to add visit');
				}

				redirect('HistoryController');
			}
		}
	}
}
