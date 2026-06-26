<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

class QueryController extends BaseApiController {    
    function __construct(){
        parent::__construct();
        $this->load->model("VisitModel");
        $this->load->model("PinModel");
        $this->load->model("ReviewModel");
        $this->load->model("GlobalListTagRelationModel");
        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
    }

    public function get_all_visit_with(){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Query param
        $search = $this->input->get('search') ? $this->input->get('search') : null;
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

        // Model : Get all visit with (companion)
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

    public function get_visit_by_pin_id($pin_id){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Validate path param
        if (!check_uuid($pin_id)) return api_response(400, 'failed', 'pin_id must be valid uuid', null);

        // Query param
        $search = $this->input->get('search') ? $this->input->get('search') : null;
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

        // Check marker ownership
        $pin = $this->PinModel->get_pin_by_id($pin_id, $user_id);
        if(!$pin) return api_response(404, 'failed', 'Marker not found', null);

        // Pagination calculation
        $page = max(1, $page);
        $per_page = max(1, $per_page);
        $offset = ($page - 1) * $per_page;

        // Model : Get visit by pin id
        $result = $this->VisitModel->get_visit_by_pin_id($search, $pin_id, $per_page, $offset, $user_id);
        $message = !empty($result['data']) ? 'Visit fetched' : 'No visit found';

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
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        $name = str_replace("%20"," ",$name);

        // Query param
        $per_page_visit_history = $this->input->get('per_page_visit_history') ?? 14;
        $page_visit_history = $this->input->get('page_visit_history') ?? 1;
        $per_page_review = $this->input->get('per_page_review') ?? 14;
        $page_review = $this->input->get('page_review') ?? 1;
        $year_monthly_chart = $this->input->get('year_monthly_chart') ?? 2026;

        // Coordinate param
        $lat = $this->input->get('lat') ? $this->input->get('lat') : null;
        $long = $this->input->get('long') ? $this->input->get('long') : null;
        
        // Validate coordinate
        if ($lat || $long) {
            $invalid_coordinate = check_coordinate($lat, $long);
            if ($invalid_coordinate) return api_response(400, 'failed', $invalid_coordinate, null);
        }

        // Validation pagination
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
        if (!is_valid_positive_number($page_review)) {
            return api_response(400, 'failed', 'page_review must be a positive number', null);
        } else {
            $page_review = (int)$page_review;
        }
        if (!is_valid_positive_number($per_page_review)) {
            return api_response(400, 'failed', 'per_page_review must be a positive number', null);
        } else {
            $per_page_review = (int)$per_page_review;
        }

        // Pagination calculation
        $page_visit_history = max(1, $page_visit_history);
        $per_page_visit_history = max(1, $per_page_visit_history);
        $offset_visit_history = ($page_visit_history - 1) * $per_page_visit_history;

        $page_review = max(1, $page_review);
        $per_page_review = max(1, $per_page_review);
        $offset_review = ($page_review - 1) * $per_page_review;

        $total_appearance = $this->VisitModel->get_total_appearance($name, $user_id);

        $message = $total_appearance > 0 ? 'Visit companion fetched' : 'No visit companion found';
        if ($total_appearance === 0) return api_response(200, 'success', $message, null);

        // Stats
        $data['visit_pertime_hour'] = $this->VisitModel->get_visit_pertime_by_person($name,'hour', null, $user_id);
        $data['visit_pertime_year'] = $this->VisitModel->get_visit_pertime_by_person($name,'month', $year_monthly_chart, $user_id);
        $data['visit_pertime_dayname'] = $this->VisitModel->get_visit_pertime_by_person($name,'dayname',null, $user_id);
        $visit_location = $this->VisitModel->get_visit_location_by_person($name, true, $user_id);
        $data['visit_location'] = $visit_location;
        $data['visit_location_category'] = $this->VisitModel->get_visit_location_category_by_person($name, $user_id);
        $data['visit_location_favorite'] = $this->VisitModel->get_visit_location_favorite_by_person($name, $user_id);
        $data['visit_daily_hour_by_person'] = $this->VisitModel->get_visit_daily_hour_by_person($name, $user_id);
        $data['visit_trends'] = $this->VisitModel->get_visit_trends($name, $user_id);
        $data['visit_person_summary'] = $this->VisitModel->get_visit_person_summary($name, $user_id);
        $data['favorite_tag'] = $this->GlobalListTagRelationModel->get_favorite_tag_by_person($name, $user_id);
        // Count avg days / visit 
        $first_trip_date = new DateTime($data['visit_person_summary']->first_trip);
        $now = new DateTime();
        $days_passed = $first_trip_date->diff($now)->days;
        if ($days_passed > 0) $avg_day_visit = (int)($days_passed / $total_appearance);
        $data['visit_person_summary']->avg_day_visit = $avg_day_visit;

        // Count average distance
        $data['visit_person_summary']->avg_distance = null;
        if ($lat && $long) {
            $total_distance = 0;

            foreach($visit_location as $dt) {
                $distance = calculate_distance($dt->pin_lat, $dt->pin_long, $lat, $long);
                $total_distance += $distance;
            }

            $data['visit_person_summary']->avg_distance = round($total_distance / $total_appearance, 1);
        }

        // Model : Get review by context (person name)
        $data['reviews'] = $this->ReviewModel->get_review_by_context($per_page_review, $offset_review, $name, "person", $user_id);
        // Model : Get visit by person name
        $data['visit_by_person'] = $this->VisitModel->get_visit_by_person($name, $per_page_visit_history, $offset_visit_history, $user_id);
        
        // Return API response
        return api_response(200, 'success', $message, $data);
    }
}