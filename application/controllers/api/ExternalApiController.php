<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExternalApiController extends CI_Controller {
    function __construct(){
        parent::__construct();
    }

    // From FlazenHand app
    public function get_current_weather(){
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');

        // Validate Coordinate
        if(!$lat || !$long) return api_response(400, 'failed', 'coordinate is required', null);

        // Cache Key
        $cache_key = 'weather_' . md5($lat . '_' . $long);

        // Cache File
        $cache_path = APPPATH . 'cache/' . $cache_key . '.json';

        // Return Cache If Exists
        if(file_exists($cache_path) && (time() - filemtime($cache_path)) < 600){
            $cached = json_decode(file_get_contents($cache_path), true);

            return api_response(200, $cached['status'], $cached['message'], $cached['data']);
        }

        // API Endpoint
        $url = "http://127.0.0.1:8000/api/v1/locations/weather?lat=$lat&long=$long";

        // CURL Request
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

        // Decode Response
        $response_decode = json_decode($response, true);

        // Response Payload
        $payload = [
            'status' => $http_code === 200 ? 'success' : 'failed',
            'message' => 'weather fetched',
            'data' => $response_decode
        ];

        // Save Cache
        file_put_contents($cache_path, json_encode($payload));

        // Return API Response
        return api_response(
            $http_code,
            $http_code === 200 ? 'success' : 'failed',
            'weather fetched',
            $response_decode
        );
    }
}