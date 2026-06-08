<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReviewController extends CI_Controller {    
    function __construct(){
        parent::__construct();
        $this->load->model("ReviewModel");
        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
    }

    public function get_review_by_pin_id($pin_id){
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';

        // Query param
        $per_page = $this->input->get('per_page') ?? 14;
        $page = $this->input->get('page') ?? 1;

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

        $result = $this->ReviewModel->get_review_by_context($per_page, $offset, $pin_id, "pin", $user_id);
        $message = !empty($result['data']) ? 'Review fetched' : 'No review found';

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
}