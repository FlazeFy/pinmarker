<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

use GuzzleHttp\Client;

class CommandController extends BaseApiController {    
    private $allowed_target_sorting_pin;
    private $allowed_value_sorting_pin;
    private $allowed_value_condition_pin;
    protected $httpClient;

    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
        $this->load->model("VisitModel");
        $this->load->model("HistoryModel");

        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
        $this->load->library('form_validation');

        $this->allowed_target_sorting_pin = ['pin_name','total_visit','created_at'];
        $this->allowed_value_sorting_pin = ['desc','asc'];
        $this->allowed_value_condition_pin = [1,0];

        $this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:1323',
            'http_errors' => false
        ]);
    }

    public function post_pin(){
        $this->authenticate();
        $user_id = $this->auth_user_id;

        $success_insert = 0;
		$failed_insert = 0;
        $rules = $this->PinModel->rules(null);
        $this->form_validation->set_rules($rules);

        if($this->form_validation->run() == FALSE){
            return api_response(400, 'failed', generate_message(false,'add','pin','validation failed'), validation_errors());
        } else {
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
            if($this->PinModel->insert_marker($data, $user_id)){
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

        $message = $success_insert > 0 ? 'Marker created' : 'Failed to create marker';

        // Return API response
        return api_response(201, 'success', $message, $data);
    }
}