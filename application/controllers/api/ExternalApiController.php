<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExternalApiController extends CI_Controller {
    private $flazenHandBaseUrl;
    function __construct(){
        parent::__construct();

        $this->flazenHandBaseUrl = "http://127.0.0.1:8000/api/v1";
    }

    // From FlazenHand app
    public function get_current_weather(){
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');

        // Validate coordinate
        if(!$lat || !$long) return api_response(400, 'failed', 'coordinate is required', null);

        // Cache key
        $cache_key = 'weather_' . md5($lat . '_' . $long);

        // Cache file
        $cache_path = APPPATH . 'cache/' . $cache_key . '.json';

        // Return Cache If Exists
        if(file_exists($cache_path) && (time() - filemtime($cache_path)) < 600){
            $cached = json_decode(file_get_contents($cache_path), true);

            return api_response(200, $cached['status'], $cached['message'], $cached['data']);
        }

        // API Endpoint
        $url = "{$this->flazenHandBaseUrl}/locations/weather?lat=$lat&long=$long";

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

        // Response payload
        $payload = [
            'status' => $http_code === 200 ? 'success' : 'failed',
            'message' => 'weather fetched',
            'data' => $response_decode['data']
        ];

        // Save cache
        file_put_contents($cache_path, json_encode($payload));

        // Return API response
        return api_response(
            $http_code,
            $http_code === 200 ? 'success' : 'failed',
            'weather fetched',
            $response_decode['data']
        );
    }

    public function get_nearby_places(){
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');
    
        // Validate coordinate
        if (!$lat || !$long) return api_response(400, 'failed', 'coordinate is required', null);
    
        // Cache directory
        $cache_dir = APPPATH . 'cache/';
    
        // Find existing reverse cache
        $cache_files = glob($cache_dir . 'reverse_*.json');
    
        foreach ($cache_files as $file) {
            $cached = json_decode(file_get_contents($file), true);
    
            // Skip invalid cache
            if (!isset($cached['coordinate']['lat']) || !isset($cached['coordinate']['long'])) continue;
    
            $cached_lat = $cached['coordinate']['lat'];
            $cached_long = $cached['coordinate']['long'];
    
            // Calculate distance
            $distance = calculate_distance($lat, $long, $cached_lat, $cached_long, 'm');
    
            // Use existing cache if distance < 20 m & cache < 24 hr
            if ($distance <= 20 && (time() - filemtime($file)) < 86400) {
                return api_response(200, $cached['status'], $cached['message'], $cached['data']);
            }
        }
    
        // Cache key
        $cache_key = 'reverse_' . md5($lat . '_' . $long);
    
        // Cache file
        $cache_path = $cache_dir . $cache_key . '.json';
    
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
    
        // Response payload
        $payload = [
            'status' => $http_code === 200 ? 'success' : 'failed',
            'message' => 'reverse location fetched',
            'coordinate' => [
                'lat' => (float)$lat,
                'long' => (float)$long
            ],
            'data' => $response_decode['data']
        ];
    
        // Save cache
        file_put_contents($cache_path, json_encode($payload));
    
        // Return API response
        return api_response(
            $http_code,
            $http_code === 200 ? 'success' : 'failed',
            'reverse location fetched',
            $response_decode['data']
        );
    }
}