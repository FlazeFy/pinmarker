<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

class QueryController extends BaseApiController {    
    function __construct(){
        parent::__construct();
        $this->load->model("HistoryModel");
        $this->load->helper('validator_helper');
    }

    public function get_my_activity(){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Query param
        $per_page = $this->input->get('per_page') ?? 14;
        $page = $this->input->get('page') ?? 1;

        // Validation pagination
        if (!is_valid_positive_number($page)) {
            return api_response(400, 'failed', 'page must be a positive number', null);
        } else {
            $page = (int)$page;
        }
        if (!is_valid_positive_number($per_page)) {
            return api_response(400, 'failed', 'per_page must be a positive number', null);
        } else {
            $per_page = (int)$per_page;
        }

        // Pagination calculation
        $page = max(1, $page);
        $per_page = max(1, $per_page);
        $offset = ($page - 1) * $per_page;

        // Model : Get activity by user
        $data = $this->HistoryModel->get_my_activity($per_page, $offset, $user_id);
        $message = !empty($data) ? 'History fetched' : 'No history found';

        // Return API response
        return api_response(200, 'success', $message, $data);
    }
}