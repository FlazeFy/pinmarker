<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PinController extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
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
        
        // Pagination calculation
        $page = max(1, $page);
        $per_page = max(1, $per_page);
        $offset = ($page - 1) * $per_page;

        $result = $this->PinModel->get_all_pin($search, $pin_category, $is_favorite, $is_visited, $per_page, $offset);

        $message = !empty($result['data']) ? 'Pin fetched successfully' : 'No pins found';

        // Return API response
        return api_response(
            200,
            'success',
            $message,
            [
                'page' => $page,
                'per_page' => $per_page,
                'total_page' => $result['total_page'],
                'data' => $result['data']
            ]
        );
    }
}