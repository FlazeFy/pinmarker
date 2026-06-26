<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

class QueryController extends BaseApiController {    
    private $flazenHandBaseUrl;
    function __construct(){
        parent::__construct();

        $this->flazenHandBaseUrl = "http://127.0.0.1:8000/api/v1";
        $this->load->model("WeatherForecastCacheModel");
        $this->load->model("PinModel");
        $this->load->helper('validator_helper');
    }

    // From FlazenHand app
    public function get_current_weather(){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Query param
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');

        // Validate coordinate
        $invalid_coordinate = check_coordinate($lat, $long);
        if ($invalid_coordinate) return api_response(400, 'failed', $invalid_coordinate, null);

        // Cache key
        $cache_key = 'weather_' . md5($lat . '_' . $long);

        // Cache file
        $cache_path = APPPATH . 'cache/' . $cache_key . '.json';

        // Return Cache If Exists
        if(file_exists($cache_path) && (time() - filemtime($cache_path)) < 600){
            $cached = json_decode(file_get_contents($cache_path), true);

            if ($cached['data']) return api_response(200, $cached['status'], $cached['message'], $cached['data']);
        }

        // External API - FlazenHand
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

        if (!$payload['data']) {
            if (file_exists($cache_path)) unlink($cache_path);
            return api_response(404, 'failed', 'weather fetched', null);
        }

        // Save cache
        file_put_contents($cache_path, json_encode($payload));

        // Return API response
        return api_response(
            $http_code,
            $http_code === 200 ? 'success' : 'failed',
            'weather fetched',
            $payload['data']
        );
    }

    public function get_nearby_places(){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Query param
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');
    
        // Validate coordinate
        $invalid_coordinate = check_coordinate($lat, $long);
        if ($invalid_coordinate) return api_response(400, 'failed', $invalid_coordinate, null);
    
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
    
        // External API - FlazenHand
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
            'data' => $response_decode['data'] ?? null
        ];
    
        // Save cache
        if ($http_code === 200 && isset($response_decode['data']['detail']) && !empty($response_decode['data']['detail'])) {
            file_put_contents($cache_path, json_encode($payload));
        }
    
        // Return API response
        return api_response(
            $http_code,
            $http_code === 200 ? 'success' : 'failed',
            'reverse location fetched',
            $response_decode['data']
        );
    }

    public function get_weather_forecast(){
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;
        
        // Query param
        $pin_id = $this->input->get('pin_id');
        $lat = $this->input->get('lat');
        $long = $this->input->get('long');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
    
        // Validate pin id
        if (!$pin_id) return api_response(400, 'failed', 'pin_id is required', null);
        if (!check_uuid($pin_id)) return api_response(400, 'failed', 'pin_id must be valid uuid', null);

        // Validate coordinate
        $invalid_coordinate = check_coordinate($lat, $long);
        if ($invalid_coordinate) return api_response(400, 'failed', $invalid_coordinate, null);

        // Validate date
        if (!$start_date || !$end_date) return api_response(400, 'failed', 'date is required', null);
        $start = DateTime::createFromFormat('Y-m-d', $start_date);
        $end = DateTime::createFromFormat('Y-m-d', $end_date);

        if (!$start || $start->format('Y-m-d') !== $start_date || !$end || $end->format('Y-m-d') !== $end_date) {
            return api_response(400, 'failed', 'date must use format YYYY-MM-DD', null);
        }

        $today = new DateTime(date('Y-m-d'));
        $maxDate = (clone $today)->modify('+14 days');
        if ($start < $today) return api_response(400, 'failed', 'start_date must be today or later', null);
        if ($end < $start) return api_response(400, 'failed', 'end_date must be equal to or after start_date', null);
        if ($start > $maxDate || $end > $maxDate) return api_response(400, 'failed', 'date must be within 14 days from today', null);
    
        // Check marker ownership
        $pin = $this->PinModel->get_pin_by_id($pin_id, $user_id);
        if(!$pin) return api_response(404, 'failed', 'Marker not found', null);
    
        // Model : Get cache by pin
        $cache = $this->WeatherForecastCacheModel->get_cache_by_pin($pin_id, $start_date);
        if(!$cache){
            // Model : Get cache by nearby coordinate (3 KM)
            $cache = $this->WeatherForecastCacheModel->get_cache_nearby($lat, $long, $start_date);
    
            // Reuse nearby cache for this pin
            if($cache){
                // Model : Create cache 
                $this->WeatherForecastCacheModel->create_cache($pin_id, $lat, $long, $cache->start_date, $cache->end_date, $cache->timezone);
                // Model : Get cache by pin id
                $newCache = $this->WeatherForecastCacheModel->get_cache_by_pin($pin_id, $start_date);
                // Model : Get cache detail by id
                $details = $this->WeatherForecastCacheModel->get_cache_detail($cache->id);
    
                $weather = [];
                $air = [];
                foreach($details as $dt){
                    $weather[] = [
                        'datetime' => $dt->forecast_datetime,
                        'temperature' => (float)$dt->temperature,
                        'feels_like' => (float)$dt->feels_like,
                        'humidity' => (int)$dt->humidity,
                        'wind_speed' => (float)$dt->wind_speed,
                        'code' => (int)$dt->weather_code
                    ];
    
                    $air[] = [
                        'datetime' => $dt->forecast_datetime,
                        'aqi' => (int)$dt->aqi,
                        'pm2_5' => (float)$dt->pm2_5,
                        'pm10' => (float)$dt->pm10,
                        'co' => (float)$dt->carbon_monoxide,
                        'no2' => (float)$dt->nitrogen_dioxide
                    ];
                }
    
                // Model : Create cache detail
                $this->WeatherForecastCacheModel->create_cache_details($newCache->id, $weather, $air);
    
                return api_response(200, 'success', 'weather forecast fetched', [
                    'timezone' => $cache->timezone,
                    'weather' => $weather,
                    'air' => $air
                ]);
            }
        }else{
            // Get cache detail
            $details = $this->WeatherForecastCacheModel->get_cache_detail($cache->id);
    
            $weather = [];
            $air = [];
            foreach($details as $dt){
                $weather[] = [
                    'datetime' => $dt->forecast_datetime,
                    'temperature' => (float)$dt->temperature,
                    'feels_like' => (float)$dt->feels_like,
                    'humidity' => (int)$dt->humidity,
                    'wind_speed' => (float)$dt->wind_speed,
                    'code' => (int)$dt->weather_code
                ];
    
                $air[] = [
                    'datetime' => $dt->forecast_datetime,
                    'aqi' => (int)$dt->aqi,
                    'pm2_5' => (float)$dt->pm2_5,
                    'pm10' => (float)$dt->pm10,
                    'co' => (float)$dt->carbon_monoxide,
                    'no2' => (float)$dt->nitrogen_dioxide
                ];
            }
    
            return api_response(200, 'success', 'weather forecast fetched', [
                'timezone' => $cache->timezone,
                'weather' => $weather,
                'air' => $air
            ]);
        }
    
        // External API - FlazenHand
        $url = "{$this->flazenHandBaseUrl}/locations/forecast?lat={$lat}&long={$long}&start_date={$start_date}&end_date={$end_date}";
    
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
    
        $response_decode = json_decode($response, true);
        if ($http_code != 200 || empty($response_decode['data'])) return api_response(500, 'failed', 'Failed fetch weather forecast', null);
    
        $forecast = $response_decode['data'];

        // Model : Create cache & detail
        $forecast_cache_id = $this->WeatherForecastCacheModel->create_cache($pin_id, $lat, $long, $start_date, $end_date, $forecast['timezone']);
        $this->WeatherForecastCacheModel->create_cache_details($forecast_cache_id, $forecast['weather'], $forecast['air']);
    
        return api_response(200, 'success', 'weather forecast fetched', $forecast);
    }
}