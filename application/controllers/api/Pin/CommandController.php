<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

use GuzzleHttp\Client;

class CommandController extends BaseApiController {    
    protected $httpClient;

    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
        $this->load->model("VisitModel");
        $this->load->model("HistoryModel");
        $this->load->model("ScheduleModel");

        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
        $this->load->library('form_validation');

        $this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:1323',
            'http_errors' => false
        ]);
    }

    public function post_create_pin(){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        $success_insert = 0;
		$failed_insert = 0;

        // Validate pin
        $rules = $this->PinModel->rules(null);
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            return api_response(400, 'failed', generate_message(false,'add','pin','validation failed'), validation_errors());
        } else {
            // Validate schedule
            $schedules = $this->input->post('schedules');
            if ($schedules && is_array($schedules)) {
                foreach ($schedules as $index => $dt) {
                    $_POST['schedule_day'] = $dt['schedule_day'] ?? null;
                    $_POST['schedule_hour_start'] = $dt['schedule_hour_start'] ?? null;
                    $_POST['schedule_hour_end'] = $dt['schedule_hour_end'] ?? null;
            
                    $rules = $this->ScheduleModel->rules(null);
                    $this->form_validation->reset_validation();
                    $this->form_validation->set_rules($rules);
            
                    if ($this->form_validation->run() == FALSE) {
                        return api_response(400, 'failed', "Schedule invalid at index $index: " . validation_errors(), null);
                    }
                }
            }

            $pin_name = $this->input->post('pin_name');

            $data = [
                'pin_name' => $pin_name,
                'pin_desc' => cleanTrimNull($this->input->post('pin_desc')),
                'pin_lat' => cleanTrimNull($this->input->post('pin_lat')),
                'pin_long' => cleanTrimNull($this->input->post('pin_long')),
                'pin_village' => cleanTrimNull($this->input->post('pin_village')),
                'pin_suburb' => cleanTrimNull($this->input->post('pin_suburb')),
                'pin_city' => cleanTrimNull($this->input->post('pin_city')),
                'pin_country' => cleanTrimNull($this->input->post('pin_country')),
                'pin_category' => cleanTrimNull($this->input->post('pin_category')),
                'pin_person' => cleanTrimNull($this->input->post('pin_person')),
                'pin_call' => cleanTrimNull($this->input->post('pin_call')),
                'pin_email' => cleanTrimNull($this->input->post('pin_email')),
                'pin_address' => cleanTrimNull($this->input->post('pin_address')),
                'is_favorite' => $this->input->post('is_favorite')
            ];

            // This App db
            $inserted_pin = $this->PinModel->insert_marker($data, $user_id);
            if($inserted_pin){
                $success_insert++;
                $this->HistoryModel->insert_history('Add Marker', $pin_name, $user_id);

                // Insert schedule if provided
                if ($schedules && is_array($schedules)) {
                    $this->ScheduleModel->insert_schedules($inserted_pin, $schedules);
                }

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

                    if ($response->getStatusCode() !== 200) {
                        log_message('error', 'API request failed: ' . $response->getBody());
                    }
                } catch (Exception $e) {
                    log_message('error', 'API request exception: ' . $e->getMessage());
                }
            } else {
                $failed_insert++;
            }
        }        

        $message = $success_insert > 0 ? 'Marker created' : 'Failed to create marker';

        // Return API response
        return api_response(201, 'success', $message, $data);
    }

    public function put_update_pin($pin_id){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Validate pin
        $rules = $this->PinModel->rules(null);
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            return api_response(400, 'failed', generate_message(false,'update','pin','validation failed'), validation_errors());
        } else {
            // Check existence
            $found = $this->PinModel->get_pin_by_id($pin_id, $user_id);
            if (!$found) return api_response(404, 'failed', 'Marker not found', null);

            $pin_name = $this->input->post('pin_name');
            $data = [
                'pin_name' => $pin_name,
                'pin_desc' => cleanTrimNull($this->input->post('pin_desc')),
                'pin_lat' => cleanTrimNull($this->input->post('pin_lat')),
                'pin_long' => cleanTrimNull($this->input->post('pin_long')),
                'pin_village' => cleanTrimNull($this->input->post('pin_village')),
                'pin_suburb' => cleanTrimNull($this->input->post('pin_suburb')),
                'pin_city' => cleanTrimNull($this->input->post('pin_city')),
                'pin_country' => cleanTrimNull($this->input->post('pin_country')),
                'pin_category' => cleanTrimNull($this->input->post('pin_category')),
                'pin_person' => cleanTrimNull($this->input->post('pin_person')),
                'pin_call' => cleanTrimNull($this->input->post('pin_call')),
                'pin_email' => cleanTrimNull($this->input->post('pin_email')),
                'pin_address' => cleanTrimNull($this->input->post('pin_address')),
                'is_favorite' => $this->input->post('is_favorite')
            ];

            $updated_pin = $this->PinModel->update_marker($data, $pin_id, $user_id);
            if (!$updated_pin) return api_response(500, 'failed', 'Marker failed to update', null);

            $this->HistoryModel->insert_history('Edit Marker', $pin_name);

            // Return API response
            return api_response(200, 'success', 'Marker updated', null);
        }        
    }
}