<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VisitController extends CI_Controller {    
    function __construct(){
        parent::__construct();
        $this->load->model("VisitModel");
        $this->load->model("PinModel");
        $this->load->model("ReviewModel");
        $this->load->helper('validator_helper');
    }

    public function get_all_visit_with(){
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';

        // Query param
        $search = $this->input->get('search') ? $this->input->get('search') : null;
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

        $result = $this->VisitModel->get_all_visit_with($search, $per_page, $offset, $user_id);
        $message = !empty($result['data']) ? 'Visit companion fetched' : 'No visit companion found';

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

    public function get_person_analyze($name){
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';
        $name = str_replace("%20"," ",$name);

        // Query param
        $per_page_visit_history = $this->input->get('per_page_visit_history') ?? 14;
        $page_visit_history = $this->input->get('page_visit_history') ?? 1;
        $year_monthly_chart = $this->input->get('year_monthly_chart') ?? 2026;

        if (!is_valid_positive_number($page_visit_history)) {
            return api_response(400, 'failed', 'page_visit_history must be a positive number', null);
        } else {
            $page_visit_history = (int)$page_visit_history;
        }
        if (!is_valid_positive_number($per_page_visit_history)) {
            return api_response(400, 'failed', 'per_page_visit_history must be a positive number', null);
        } else {
            $per_page_visit_history = (int)$per_page_visit_history;
        }

        // Pagination calculation
        $page_visit_history = max(1, $page_visit_history);
        $per_page_visit_history = max(1, $per_page_visit_history);
        $offset_visit_history = ($page_visit_history - 1) * $per_page_visit_history;

        $total_appearance = $this->VisitModel->get_total_appearance($name, $user_id);

        $message = $total_appearance > 0 ? 'Visit companion fetched' : 'No visit companion found';
        if ($total_appearance === 0) return api_response(200, 'success', $message, null);

        // Stats
        $data['visit_pertime_hour'] = $this->VisitModel->get_visit_pertime_by_person($name,'hour', null, $user_id);
        $data['visit_pertime_year'] = $this->VisitModel->get_visit_pertime_by_person($name,'month', $year_monthly_chart, $user_id);
        $data['visit_pertime_dayname'] = $this->VisitModel->get_visit_pertime_by_person($name,'dayname',null, $user_id);
        $data['visit_location'] = $this->VisitModel->get_visit_location_by_person($name,true, $user_id);
        $data['visit_location_category'] = $this->VisitModel->get_visit_location_category_by_person($name, $user_id);
        $data['visit_location_favorite'] = $this->VisitModel->get_visit_location_favorite_by_person($name, $user_id);
        $data['visit_daily_hour_by_person'] = $this->VisitModel->get_visit_daily_hour_by_person($name, $user_id);
        $data['visit_person_summary'] = $this->VisitModel->get_visit_person_summary($name, $user_id);
        $data['visit_trends'] = $this->VisitModel->get_visit_trends($name, $user_id);

        // Pagination model
        $data['visit_by_person'] = $this->VisitModel->get_visit_by_person($name, $per_page_visit_history, $offset_visit_history, $user_id);
        
        // Return API response
        return api_response(200, 'success', $message, $data);
    }
}