<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsController extends CI_Controller {   
    private $flazenHandBaseUrl;
 
    function __construct(){
        parent::__construct();
        $this->load->model("NewsModel");
        $this->load->model("PinModel");
        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
        $this->flazenHandBaseUrl = "http://127.0.0.1:8000/api/v1";
    }

    public function get_news_by_pin_id($pin_id){
        $user_id = 'fcd3f23e-e5aa-11ee-811a-3216422910e9';

        // Validate path param
        if (!check_uuid($pin_id)) return api_response(400, 'failed', 'pin_id must be valid uuid', null);

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

        // Get existing news by pin id
        $result = $this->NewsModel->get_news_by_pin_id($per_page, $offset, $pin_id, $user_id);
        if (empty($result['data'])) {
            // Get pin name from id
            $pin_name = $this->PinModel->get_pin_name_by_id($pin_id);
            if (!$pin_name) return api_response(404, 'failed', 'pin_id not found', null);

            // API Endpoint
            $url = "{$this->flazenHandBaseUrl}/news/".urlencode($pin_name);
        
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

            if ($http_code !== 200) return api_response($http_code, 'failed', 'Failed to fetch external news', null);
        
            // Decode response
            $response_decode = json_decode($response, true);
            $result_external = $response_decode['data'];

            if (empty($result_external)) {
                return api_response(200, 'success', $response_decode['data'], [
                    'page' => $page,
                    'per_page' => $per_page,
                    'total_page' => 0,
                    'total_item' => 0,
                    'start_item' => 0,
                    'end_item' => 0,
                    'data' => []
                ]);
            }

            foreach ($result_external as $dt) {
                $published_at = date('Y-m-d H:i:s', strtotime($dt['published_at']));

                // Store external news api 
                $this->NewsModel->create_news($pin_id, $dt['title'], $dt['url'], $dt['source'], $published_at);
            }

            // Build pagination manually from external data
            $total_rows = count($result_external);
            $total_pages = ceil($total_rows / $per_page);
            $paged_data = array_slice($result_external, $offset, $per_page);
            $start_item = $total_rows > 0 ? $offset + 1 : 0;
            $end_item = min($offset + $per_page, $total_rows);

            // Format to match db model structure
            $formatted = array_map(function($dt) {
                return [
                    'news_title' => $dt['title'],
                    'news_url' => $dt['url'],
                    'news_source' => $dt['source'],
                    'published_at' => date('Y-m-d H:i:s', strtotime($dt['published_at']))
                ];
            }, $paged_data);

            return api_response(200, 'success', 'News fetched', [
                'page' => $page,
                'per_page' => $per_page,
                'total_page' => $total_pages,
                'total_item' => $total_rows,
                'start_item' => $start_item,
                'end_item' => $end_item,
                'data' => $formatted
            ]);
        } 

        // Return API response
        return api_response(
            200,
            'success',
            'News fetched',
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