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
        $this->load->model("AuthModel");

        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
        $this->load->library('form_validation');
        $this->load->library('TelegramHelper'); 
    }

    public function post_create_visit(){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Validate visit
        $rules = $this->VisitModel->rules(null);
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            return api_response(400, 'failed', generate_message(false,'add','visit','validation failed'), validation_errors());
        } else {
            $pin_id = cleanTrimNull($this->input->post('pin_id'));
            $type_visit = $this->input->post('type_visit');

            // Model : Get pin name
            $pin = $this->PinModel->get_pin_by_id($pin_id, $user_id);
            if (!$pin) return api_response(404, 'failed', 'Marker not found', null);

            $pin_name = $pin->pin_name;
            $visit_at = cleanTrimNull($this->input->post('visit_date'))." ".cleanTrimNull($this->input->post('visit_hour'));
            $data = [
                'pin_id' => $pin_id, 
                'visit_desc' => cleanTrimNull($this->input->post('visit_desc')), 
                'visit_by' => cleanTrimNull($this->input->post('visit_by')), 
                'visit_with' => cleanTrimNull($this->input->post('visit_with')), 
                'created_at' => $visit_at
            ];

            // Model : Create visit
            $visit = $this->VisitModel->insert_visit($data, $user_id);
            if ($visit) {
                // Model : Create history
                $this->HistoryModel->insert_history('Add Visit', "to $pin_name", $user_id);

                // Model : Get user profile
                $user = $this->AuthModel->get_user_by_id($user_id);

                // Broadcast telegram message
                if ($user->telegram_is_valid == 1) {
                    $context = $type_visit === "plan" ? "plan to visit" : "visit history at";
                    $message = "Hello <b>$user->username</b>, your $context $pin_name has been created";
                    $this->telegramhelper->sendMessageText($user->telegram_user_id, $message);
                }

                // Return API response
                $data['id'] = $visit->id;
                return api_response(201, 'success', "Visit to $pin_name has been created", $data);
            } else {
                return api_response(500, 'failed', 'Something went wrong', null);
            }
        }        
    }

    public function put_update_visit_by_id($id) {
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Validate path param
        if (!check_uuid($id)) return api_response(400, 'failed', 'id must be valid uuid', null);

        // Validate visit
        $rules = $this->VisitModel->rules(null);
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            return api_response(400, 'failed', generate_message(false,'add','visit','validation failed'), validation_errors());
        } else {
            // Model : Get visit by id
            $visit = $this->VisitModel->get_visit_by_id($id, $user_id);
            if (!$visit) return api_response(404, 'failed', 'Visit not found', null);

            // Model : Get pin name
            $pin = $this->PinModel->get_pin_by_id($visit->pin_id, $user_id);
            if (!$pin) return api_response(404, 'failed', 'Marker not found', null);

            $pin_name = $pin->pin_name;
            $visit_at = cleanTrimNull($this->input->post('visit_date'))." ".cleanTrimNull($this->input->post('visit_hour'));
            $data = [
                'visit_desc' => cleanTrimNull($this->input->post('visit_desc')), 
                'visit_by' => cleanTrimNull($this->input->post('visit_by')), 
                'visit_with' => cleanTrimNull($this->input->post('visit_with')), 
                'created_at' => $visit_at
            ];

            // Model : Update visit by id
            $visit = $this->VisitModel->update_visit($data, $id, $user_id);
            if ($visit) {
                // Model : Add updated visit history
                $this->HistoryModel->insert_history('Update visit', "Visit at $pin_name", $user_id);			

                // Return API response
                return api_response(200, 'success', "Visit updated", $data);
            } else {
                return api_response(500, 'failed', 'Something went wrong', null);
            }
        } 
    }

    public function delete_visit_by_id($id) {
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Validate path param
        if (!check_uuid($id)) return api_response(400, 'failed', 'id must be valid uuid', null);

        // Model : Get visit by id
        $visit = $this->VisitModel->get_visit_by_id($id, $user_id);
        if (!$visit) return api_response(404, 'failed', 'Visit not found', null);

        // Model : Delete visit by id
        $deleted = $this->VisitModel->delete_visit($id, $user_id);
        if (!$deleted) return api_response(404, 'failed', 'Visit not found', null);

        // Model : Add deleted visit history
        $this->HistoryModel->insert_history('Delete visit', "Visit at $visit->pin_name", $user_id);			

        // Return API response
        return api_response(200, 'success', "Visit deleted", $data);
    }
}