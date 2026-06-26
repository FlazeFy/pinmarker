<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('check_uuid')) {
    function check_uuid($val) {
        $uuidRegex = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        return preg_match($uuidRegex, $val) === 1;
    }
}

if (!function_exists('check_coordinate')) {
    function check_coordinate($lat, $long) {
        if ($lat === null || $long === null || $lat === '' || $long === '') return 'coordinate is required';
        if (!is_numeric($lat)) return 'lat must be valid number';
        if (!is_numeric($long)) return 'long must be valid number';

        $lat = (double)$lat;
        $long = (double)$long;
        if ($lat < -90 || $lat > 90) return 'lat is invalid';
        if ($long < -180 || $long > 180) return 'long is invalid';

        return null;
    }
}

if (!function_exists('is_valid_positive_number')) {
    function is_valid_positive_number($value) {
        return is_numeric($value) && (int)$value > 0;
    }
}

if (!function_exists('cleanTrimNull')) {
    function cleanTrimNull($value) {
        if ($value === null) return null;
        $trimmed = trim($value);
        return $trimmed !== '' ? $trimmed : null;
    }
}