<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('check_uuid')) {
    function check_uuid($val){
        $uuidRegex = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';
        
        return preg_match($uuidRegex, $str) === 1;
    }
}
