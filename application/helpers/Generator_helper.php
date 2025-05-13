<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_UUID')) {
    function get_UUID(){
        $result = '';
        $bytes = random_bytes(16);
        $hex = bin2hex($bytes);
        $time_low = substr($hex, 0, 8);
        $time_mid = substr($hex, 8, 4);
        $time_hi_and_version = substr($hex, 12, 4);
        $clock_seq_hi_and_reserved = hexdec(substr($hex, 16, 2)) & 0x3f;
        $clock_seq_low = hexdec(substr($hex, 18, 2));
        $node = substr($hex, 20, 12);
        $uuid = sprintf('%s-%s-%s-%02x%02x-%s', $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $clock_seq_low, $node);
        
        return $uuid;
    }
}

if (!function_exists('get_token_validation')) {
    function get_token_validation($len){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $res = '';
        
        $charCount = strlen($characters);
        for ($i = 0; $i < $len; $i++) {
            $res .= $characters[rand(0, $charCount - 1)];
        }
        
        return $res;
    }
}

if (!function_exists('is_mobile_device')){
    function is_mobile_device() {
        $user = $_SERVER['HTTP_USER_AGENT'];
    
        $type = ['mobile', 'android', 'iphone', 'ipod', 'tablet', 'blackberry', 'windows phone', 'ipad'];
        
        foreach ($type as $key) {
            if (stripos($user, $key) !== false) {
                return true;
            }
        }
    
        return false;
    }
}

if (!function_exists('generate_message')){
    function generate_message($is_success,$type,$context,$notes){
        $msg = "";
        if($is_success){
            $msg .= "Success to ";
        } else {
            $msg .= "Failed to ";
        }
        $msg .= strtolower($type)." ";
        if($context){
            $msg .= ucfirst($context);
        } 
        if($notes){
            $msg .= ". ";
            if($is_success){
                $msg .= "But ".$notes;
            } else {
                $msg .= ucfirst($notes);
            }
        }
        return $msg;
    }
}

if (!function_exists('generate_document_template')){
    function generate_document_template($type,$username){
        if($type == "html_header"){
            $msg = "
                <style>
                    th, td{
                        border: 1px solid black;
                    }
                    thead {
                        font-size:13px;
                    }
                    tbody {
                        font-size:11px;
                    }
                    tbody td {
                        padding: 3px;
                    }
                    table {
                        border-collapse: collapse;
                        width:100%;
                    }
                    h5 {
                        font-size:15px;
                        margin-bottom:6px !important;
                    }
                    h6 {
                        font-size:12px;
                        margin:0 !important;
                    }
                    p {
                        font-size:11.5px;
                        margin:0 !important;
                    }
                </style>
            ";
        } else if($type == "document_header"){
            $msg = "
                <div style='text-align:center;'>
                    <h1 style='margin:0;'>PinMarker</h1>
                    <h3 style='font-style:italic; margin:0; color:grey;'>- Marks Your World -</h3>
                    <br><hr>
                </div>
            ";
        } else if($type == "document_footer"){
            $datetime = date("Y-m-d H:i");
            $msg = "
                <br><hr><br>
                <div style='font-size: 12px; font-style:italic;'>
                    <p style='float:left;'>PinMarker parts of FlazenApps</p>
                    <p style='float:right;'>Generated at $datetime by $username</p>
                </div>
            ";
        }
        
        return $msg;
    }
}

if (!function_exists('generate_document_pin_detail_body')){
    function generate_document_pin_detail_body($dt_pin, $pin_desc, $pin_address, $pin_person, $pin_email, $pin_call, $updated_at, $dt_visit_history){
        $body = "";
        if(count($dt_visit_history) > 0){
            foreach($dt_visit_history as $dt){
                $body .= "
                <tr>
                    <td>".($dt->visit_desc ?? '-')."</td>
                    <td>
                        <h6>Visit With : </h6>
                        ".($dt->visit_with ?? '-')."
                        <h6>Visit By : </h6>
                        ".($dt->visit_by ?? '-')."
                    </td>
                    <td>".date("Y-m-d H:i", strtotime($dt->created_at))."</td>
                </tr>
                ";
            }
        } else {
            $body = "
            <tr>
                <td colspan='3' style='font-style:italic; text-align:center;'>- No visit to show -</td>
            </tr>
            ";
        }
        
        $totalVisits = count($dt_visit_history);
        
        $html = "
            <h4 style='text-align:left;'>Pin Detail</h4>
            <div style='text-align:left;'>
                <p style='font-weight:normal;'>Pin Name : {$dt_pin->pin_name}</p>
                <p style='font-weight:normal;'>Description : {$pin_desc}</p>
                <p style='font-weight:normal;'>Category : {$dt_pin->pin_category}</p>
                <p style='font-weight:normal;'>Coordinate : {$dt_pin->pin_lat}, {$dt_pin->pin_long}</p>
                <p style='font-weight:normal;'>Address : {$pin_address}</p>
                <h5>Contact</h5>
                <p style='font-weight:normal;'>Person : {$pin_person}</p>
                <p style='font-weight:normal;'>Email : {$pin_email}</p>
                <p style='font-weight:normal;'>Call : {$pin_call}</p>
                <h5>Props</h5>
                <p style='font-weight:normal;'>Created At : {$dt_pin->created_at}</p>
                <p style='font-weight:normal;'>Updated At : {$updated_at}</p>
                <br>
                <div id='map-link'></div>
                <h6>Google Maps : <a href='https://www.google.com/maps/place/{$dt_pin->pin_lat},{$dt_pin->pin_long}' style='font-weight:normal; color:blue;'>https://www.google.com/maps/place/{$dt_pin->pin_lat},{$dt_pin->pin_long}</a></h6>
            </div>
            <h4 style='text-align:left;'>Visit List</h4>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Info</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    {$body}
                    <tr style='font-weight:bold;'>
                        <td colspan='2'>Total Visit</td>
                        <td>{$totalVisits}</td>
                    </tr>
                </tbody>
            </table>";
        
        return $html;
    }
}

if (!function_exists('highlight_item')){
    function highlight_item($find, $items) {
        $index = stripos($items, $find);
        
        if ($index === false) {
            return $items;
        }
    
        $beforeMatch = substr($items, 0, $index);
        $match = substr($items, $index, strlen($find));
        $afterMatch = substr($items, $index + strlen($find));
        $res = $beforeMatch."<span class='fst-italic bg-primary text-white rounded px-2 py-0 mx-1'>{$match}</span>".$afterMatch;
    
        return $res;
    }
}