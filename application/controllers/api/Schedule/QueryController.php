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
        $is_open_only = $this->input->get('is_open_only');
        if ($is_open_only === null) $is_open_only = 'all';

        // Query param validator
        if ($is_favorite !== 'all' && !in_array($is_favorite, $this->allowed_value_condition_schedule)) {
            return api_response(400, 'failed', 'is_favorite not valid', null);
        }
        if ($is_visited !== 'all' && !in_array($is_visited, $this->allowed_value_condition_schedule)) {
            return api_response(400, 'failed', 'is_visited not valid', null);
        }
        if ($is_open_only !== 'all' && !in_array($is_open_only, $this->allowed_value_condition_schedule)) {
            return api_response(400, 'failed', 'is_open_only not valid', null);
        }

        $result = $this->ScheduleModel->get_all_schedule($search, $pin_category, $is_favorite, $is_visited, $is_open_only, $user_id);

        $message = !empty($result) ? 'Schedule fetched' : 'No schedule found';

        // Return API response
        return api_response(200, 'success', $message, $result);
    }
}