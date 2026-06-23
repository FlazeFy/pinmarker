<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

class QueryController extends BaseApiController {    
    private $allowed_value_condition_schedule;
    private $flazenHandBaseUrl;

    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
        $this->load->model("VisitModel");
        $this->load->model("ScheduleModel");
        $this->load->helper('validator_helper');
        $this->allowed_value_condition_schedule = [1,0];
    }

    public function get_all_schedule(){
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Query param
        $search = $this->input->get('search') ? $this->input->get('search') : null;
        $pin_category = $this->input->get('pin_category') ? $this->input->get('pin_category') : null;
        // dont use ternery, 0 count as false
        $is_favorite = $this->input->get('is_favorite');
        if ($is_favorite === null) $is_favorite = 'all'; 
        $is_visited = $this->input->get('is_visited');
        if ($is_visited === null) $is_visited = 'all'; 
        $open_status = $this->input->get('open_status');
        if ($open_status === null) $open_status = 'all';

        // Coordinate param
        $lat = $this->input->get('lat') ? $this->input->get('lat') : null;
        $long = $this->input->get('long') ? $this->input->get('long') : null;

        if (($lat && !$long) || (!$lat && $long)) {
            return api_response(400, 'failed', 'coordinate not valid', null);
        }

        // Max distance param
        $max_distance = $this->input->get('max_distance') ?? null;

        // Query param validator
        if ($is_favorite !== 'all' && !in_array($is_favorite, $this->allowed_value_condition_schedule)) {
            return api_response(400, 'failed', 'is_favorite not valid', null);
        }
        if ($is_visited !== 'all' && !in_array($is_visited, $this->allowed_value_condition_schedule)) {
            return api_response(400, 'failed', 'is_visited not valid', null);
        }
        if ($open_status !== 'all' && !in_array($open_status, $this->allowed_value_condition_schedule)) {
            return api_response(400, 'failed', 'open_status not valid', null);
        }

        // Max distance query param validator
        if ($max_distance && !is_valid_positive_number($max_distance) && $max_distance !== "all") {
            return api_response(400, 'failed', 'max_distance must be a positive number', null);
        } else if($max_distance){
            if ($max_distance !== "all") {
                $max_distance = (int)$max_distance;
            } else {
                $max_distance = null;
            }
        } 

        $result = $this->ScheduleModel->get_all_schedule($search, $pin_category, $is_favorite, $is_visited, $open_status, $max_distance, $lat, $long, $user_id);

        $message = !empty($result) ? 'Schedule fetched' : 'No schedule found';

        // Return API response
        return api_response(200, 'success', $message, $result);
    }

    public function get_schedule_by_pin_id($pin_id) {
        // Validate path param
        if (!check_uuid($pin_id)) return api_response(400, 'failed', 'pin_id must be valid uuid', null);

        $result = $this->ScheduleModel->get_schedule_by_pin_id($pin_id);

        $message = !empty($result) ? 'Schedule fetched' : 'No schedule found';

        // Return API response
        return api_response(200, 'success', $message, $result);
    }
}