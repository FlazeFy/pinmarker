<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WeatherForecastCacheModel extends CI_Model {
    private $table = "weather_forecast_cache";
    private $detailTable = "weather_forecast_cache_detail";

    function __construct(){
        parent::__construct();
        $this->load->helper('generator_helper');
    }

    // Command
    public function create_cache($related_pin_id, $latitude, $longitude, $start_date, $end_date, $timezone){
        $id = get_UUID();

        $this->db->insert($this->table, [
            'id' => $id,
            'related_pin_id' => $related_pin_id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'timezone' => $timezone,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $id;
    }

    public function create_cache_details($forecast_cache_id, $weather, $air){
        $count = count($weather);

        for($i=0; $i<$count; $i++){
            $airData = $air[$i] ?? [];

            $this->db->insert($this->detailTable, [
                'id' => get_UUID(),
                'forecast_cache_id' => $forecast_cache_id,
                'forecast_datetime' => $weather[$i]['datetime'],
                'weather_code' => $weather[$i]['code'],
                'temperature' => $weather[$i]['temperature'],
                'feels_like' => $weather[$i]['feels_like'],
                'humidity' => $weather[$i]['humidity'],
                'wind_speed' => $weather[$i]['wind_speed'],
                'aqi' => $airData['aqi'] ?? null,
                'pm2_5' => $airData['pm2_5'] ?? null,
                'pm10' => $airData['pm10'] ?? null,
                'carbon_monoxide' => $airData['co'] ?? null,
                'nitrogen_dioxide' => $airData['no2'] ?? null
            ]);
        }
    }

    // Query
    public function get_cache_by_pin($pin_id, $start_date){
        return $this->db
            ->where('related_pin_id', $pin_id)
            ->where('start_date', $start_date)
            ->order_by('created_at', 'DESC')
            ->limit(1)
            ->get($this->table)
            ->row();
    }

    public function get_cache_nearby($latitude, $longitude, $start_date){
        $sql = "
            SELECT *,
                (
                    6371 * acos(
                        cos(radians(?))
                        * cos(radians(latitude))
                        * cos(radians(longitude) - radians(?))
                        + sin(radians(?))
                        * sin(radians(latitude))
                    )
                ) AS distance
            FROM {$this->table}
            WHERE start_date = ?
            HAVING distance <= 3
            ORDER BY distance ASC, created_at DESC
            LIMIT 1
        ";

        return $this->db->query($sql, [$latitude, $longitude, $latitude, $start_date])->row();
    }

    public function get_cache_detail($forecast_cache_id){
        return $this->db
            ->select('forecast_datetime, weather_code, temperature, feels_like, humidity, wind_speed, aqi, pm2_5, pm10, carbon_monoxide, nitrogen_dioxide')
            ->where('forecast_cache_id', $forecast_cache_id)
            ->order_by('forecast_datetime', 'ASC')
            ->get($this->detailTable)
            ->result();
    }
}