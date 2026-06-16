<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PinController extends CI_Controller {    
    private $allowed_target_sorting_pin;
    private $allowed_value_sorting_pin;
    private $allowed_value_condition_pin;
    private $flazenHandBaseUrl;

    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
        $this->load->model("VisitModel");
        $this->load->helper('validator_helper');
        $this->allowed_target_sorting_pin = ['pin_name','total_visit','created_at'];
        $this->allowed_value_sorting_pin = ['desc','asc'];
        $this->allowed_value_condition_pin = [1,0];
        $this->flazenHandBaseUrl = "http://127.0.0.1:8000/api/v1";
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
        
        // Coordinate param
        $lat = $this->input->get('lat') ? $this->input->get('lat') : null;
        $long = $this->input->get('long') ? $this->input->get('long') : null;
        
        // Total item per page param
        $per_page = $this->input->get('per_page');
        if ($per_page === null) {
            $per_page = 15;
        } else if ($per_page !== "all" && is_valid_positive_number($per_page)) {
            $per_page = (int)$per_page;
        } else if ($per_page === "all") {
            $per_page = null;
        } else {
            return api_response(400, 'failed', 'per_page must be a positive number', null);
        }

        // Pagination param
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;

        // Max distance param
        $max_distance = $this->input->get('max_distance') ?? null;
        
        // boolean param safety
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

        // Pagination calculation
        $page = max(1, $page);
        if ($per_page !== null) {
            $per_page = max(1, (int)$per_page);
            $offset = ($page - 1) * $per_page;
        } else {
            $offset = 0;
        }

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
                'visited_percentage' => $result['visited_percentage'],
                'average_distance' => $result['average_distance'],
                'end_item' => $result['end_item'],
                'data' => $result['data'],
            ]
        );
    }

    public function get_validate_new_marker(){
        // Coordinate param
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';

        // Validate coordinate
        if (!is_valid_coordinate($lat,$long)) return api_response(400, 'failed', 'coordinate is not valid', null);

        // API Endpoint
        $url = "{$this->flazenHandBaseUrl}/locations/reverse?lat=$lat&long=$long";

        // CURL request
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPGET => true
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        // Decode response
        $response_decode = json_decode($response, true);

        // Nearest marker
        $max_distance = 0.5;
        $max_item = 10;
        $result = $this->PinModel->get_all_pin_maps_format(null, null, $lat, $long, $max_distance, null, null, $max_item, 1, $user_id);
        $existing = count($result['data']) > 0 ? $result['data'] : null;

        if ($existing) {
            $existing = array_map(function($pin) {
                return [
                    'pin_name' => $pin->pin_name,
                    'pin_lat' => $pin->pin_lat,
                    'pin_long' => $pin->pin_long,
                    'pin_category' => $pin->pin_category,
                    'distance' => $pin->distance
                ];
            }, $existing);
        }

        $data = [
            'detail' => $response_decode['data']['detail'],
            'existing' => $existing,
            'recommended' => $response_decode['data']['nearby'],
        ];

        // Return API response
        return api_response(
            $http_code,
            $http_code === 200 ? 'success' : 'failed',
            'pin fetched',
            $data
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