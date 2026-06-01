<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PinController extends CI_Controller {    
    private $allowed_target_sorting_pin;
    private $allowed_value_sorting_pin;
    private $allowed_value_condition_pin;

    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
        $this->load->model("VisitModel");
        $this->load->helper('validator_helper');
        $this->allowed_target_sorting_pin = ['pin_name','total_visit','created_at'];
        $this->allowed_value_sorting_pin = ['desc','asc'];
        $this->allowed_value_condition_pin = [1,0];
    }

    public function get_all_pin(){
        // Query param
        $search = $this->input->get('search') ? $this->input->get('search') : null;
        $pin_category = $this->input->get('pin_category') ? $this->input->get('pin_category') : null;
        $per_page = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 14;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        // dont use ternery, 0 count as false
        $is_favorite = $this->input->get('is_favorite');
        if ($is_favorite === null) $is_favorite = 'all'; 
        $is_visited = $this->input->get('is_visited');
        if ($is_visited === null) $is_visited = 'all'; 
        $sorting = $this->input->get('sorting');
        $with_companion = $this->input->get('with_companion');
        if ($with_companion === null) $with_companion = '0'; 
        $visit_with = $this->input->get('visit_with');
        if ($visit_with === null) $visit_with = '-all-'; 
        if ($sorting === null) $sorting = 'created_at-desc'; 
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';

        // Query param validator
        $sorting_split = explode('-', $sorting);
        $target_sort = $sorting_split[0];
        $value_sort = $sorting_split[1];
        if (!in_array($value_sort, $this->allowed_value_sorting_pin) || !in_array($target_sort, $this->allowed_target_sorting_pin)) {
            return api_response(400, 'failed', 'sorting not valid', null);
        }
        if ($is_favorite !== 'all' && !in_array($is_favorite, $this->allowed_value_condition_pin)) {
            return api_response(400, 'failed', 'is_favorite not valid', null);
        }
        if (!in_array($with_companion, $this->allowed_value_condition_pin)) {
            return api_response(400, 'failed', 'with_companion not valid', null);
        }
        if ($is_visited !== 'all' && !in_array($is_visited, $this->allowed_value_condition_pin)) {
            return api_response(400, 'failed', 'is_visited not valid', null);
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

        $result = $this->PinModel->get_all_pin($search, $pin_category, $is_favorite, $with_companion, $visit_with, $is_visited, $per_page, $offset, $sorting, $user_id);
        
        $categories = $this->PinModel->get_pin_category($user_id);
        foreach ($categories as $dt) {
            unset($dt->dictionary_icon);
        }
        unset($dt);

        $companions = $with_companion === "1" ? $this->VisitModel->get_visit_withs($user_id) : null;

        $message = !empty($result['data']) ? 'Pin fetched' : 'No pins found';

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
                'category' => $categories,
                'visit_with' => $companions
            ]
        );
    }

    public function get_all_pin_maps(){
        // Query param
        $search = $this->input->get('search') ? $this->input->get('search') : null;
        $pin_category = $this->input->get('pin_category') ? $this->input->get('pin_category') : null;
        $lat = $this->input->get('lat') ? $this->input->get('lat') : null;
        $long = $this->input->get('long') ? $this->input->get('long') : null;
        $per_page = $this->input->get('per_page') ? (int)$this->input->get('per_page') : 50;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $max_distance = $this->input->get('max_distance') ? (int)$this->input->get('max_distance') : null;
        // dont use ternery, 0 count as false
        $is_favorite = $this->input->get('is_favorite');
        if ($is_favorite === null) $is_favorite = 'all'; 
        $is_visited = $this->input->get('is_visited');
        if ($is_visited === null) $is_visited = 'all'; 
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';

        // Query param validator
        if ($is_favorite !== 'all' && !in_array($is_favorite, $this->allowed_value_condition_pin)) {
            return api_response(400, 'failed', 'is_favorite not valid', null);
        }
        if ($is_visited !== 'all' && !in_array($is_visited, $this->allowed_value_condition_pin)) {
            return api_response(400, 'failed', 'is_visited not valid', null);
        }
        if (!is_valid_positive_number($page)) {
            return api_response(400, 'failed', 'page must be a positive number', null);
        }
        if (!is_valid_positive_number($per_page)) {
            return api_response(400, 'failed', 'per_page must be a positive number', null);
        }
        if ($max_distance && !is_valid_positive_number($max_distance)) {
            return api_response(400, 'failed', 'max_distance must be a positive number', null);
        }

        // Pagination calculation
        $page = max(1, $page);
        $per_page = max(1, $per_page);
        $offset = ($page - 1) * $per_page;

        $result = $this->PinModel->get_all_pin_maps_format($search, $pin_category, $lat, $long, $max_distance, $is_favorite, $is_visited, $per_page, $offset, $user_id);

        $message = !empty($result['data']) ? 'Pin fetched' : 'No pins found';

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
            ]
        );
    }

    public function get_pin_category(){
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';
        $data = $this->PinModel->get_pin_category($user_id);
        $message = !empty($data) ? 'Pin category fetched' : 'No pin category found';

        // Return API response
        return api_response(200, 'success', $message, $data);
    }
}