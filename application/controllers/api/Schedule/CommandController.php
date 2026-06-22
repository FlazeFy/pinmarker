<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

use GuzzleHttp\Client;

class CommandController extends BaseApiController {    
    protected $httpClient;

    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
        $this->load->model("ScheduleModel");

        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
        $this->load->library('form_validation');

        $this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:1323',
            'http_errors' => false
        ]);
    }

    public function put_update_schedule($pin_id){
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Check existence
        $found = $this->PinModel->get_pin_by_id($pin_id, $user_id);
        if (!$found) return api_response(404, 'failed', 'Marker not found', null);
         
        // Validate schedule
        $schedules = $this->input->post('schedules');
        if (!$schedules || !is_array($schedules)) {
            return api_response(400, 'failed', "Schedule is required", null);
        }

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

        // Repo : Delete old schedule
        $this->ScheduleModel->delete_schedules($pin_id);
        // Repo : insert new schedule
        $this->ScheduleModel->insert_schedules($pin_id, $schedules);

        // Return API response
        return api_response(201, 'success', 'Schedule updated', null);
    }
}