<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

class GlobalListController extends BaseApiController {    
    private $allowed_target_sorting_pin;
    private $allowed_value_sorting_pin;
    private $allowed_value_condition_pin;

    function __construct(){
        parent::__construct();
        $this->load->model("GlobalListModel");
        $this->load->model("VisitModel");
        $this->load->helper('validator_helper');
        $this->allowed_target_sorting_pin = ['list_name','total_visit','created_at'];
        $this->allowed_value_sorting_pin = ['desc','asc'];
        $this->allowed_value_condition_pin = [1,0];
    }

    public function get_my_global_list(){
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Query param
        $search = $this->input->get('search') ? $this->input->get('search') : null;
        $per_page = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 14;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        // dont use ternery, 0 count as false
        $sorting = $this->input->get('sorting');
        $with_companion = $this->input->get('with_companion');
        if ($with_companion === null) $with_companion = '0'; 
        $visit_with = $this->input->get('visit_with');
        if ($visit_with === null) $visit_with = '-all-'; 
        if ($sorting === null) $sorting = 'created_at-desc'; 

        // Query param validator
        $sorting_split = explode('-', $sorting);
        $target_sort = $sorting_split[0];
        $value_sort = $sorting_split[1];
        if (!in_array($value_sort, $this->allowed_value_sorting_pin) || !in_array($target_sort, $this->allowed_target_sorting_pin)) {
            return api_response(400, 'failed', 'sorting not valid', null);
        }
        if (!in_array($with_companion, $this->allowed_value_condition_pin)) {
            return api_response(400, 'failed', 'with_companion not valid', null);
        }
        if (!is_valid_positive_number($page)) {
            return api_response(400, 'failed', 'page must be a positive number', null);
        }
        if (!is_valid_positive_number($per_page)) {
            return api_response(400, 'failed', 'per_page must be a positive number', null);
        }

        // Pagination calculation
        $page = max(1, $page);
        $per_page = max(1, $per_page);
        $offset = ($page - 1) * $per_page;

        $result = $this->GlobalListModel->get_my_global_list($search, $with_companion, $visit_with, $per_page, $offset, $sorting, $user_id);
        $companions = $with_companion === "1" ? $this->VisitModel->get_visit_withs($user_id) : null;

        $message = !empty($result['data']) ? 'Global list fetched' : 'No global list found';

        // Return API response
        return api_response(
            200,
            'success',
            $message,
            [
                'page' => $page,
                'per_page' => $per_page,
                'total_page' => $result['total_page'],
                'total_item' => $result['total_item'],
                'start_item' => $result['start_item'],
                'end_item' => $result['end_item'],
                'data' => $result['data'],
                'visit_with' => $companions
            ]
        );
    }

    public function get_recommended_tag_address(){
        $limit_pin_address = $this->input->get('limit_pin_address') ? (int)$this->input->get('limit_pin_address') : 6;
        $limit_tag = $this->input->get('limit_tag') ? (int)$this->input->get('limit_tag') : 12;

        if (!is_valid_positive_number($limit_pin_address)) {
            return api_response(400, 'failed', 'limit_pin_address must be a positive number', null);
        }
        if (!is_valid_positive_number($limit_tag)) {
            return api_response(400, 'failed', 'limit_tag must be a positive number', null);
        }

        $res_pin_address = $this->GlobalListModel->get_recommended_pin_address($limit_pin_address);
        $res_tags = $this->GlobalListModel->get_recommended_tag($limit_tag);

        $message = !empty($res_pin_address) || !empty($res_tags) ? 'Global list fetched' : 'No global list found';

        // Return API response
        return api_response(
            200,
            'success',
            $message,
            [
                'pin_address' => $res_pin_address,
                'tags' => $res_tags
            ]
        );
    }
}