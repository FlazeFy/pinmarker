<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BaseApiController extends CI_Controller {
    protected $auth_user_id;
    protected $auth_role;

    function __construct(){
        parent::__construct();
        $this->load->model('AuthModel');
    }

    protected function authenticate(){
        $headers = $this->input->request_headers();
        $bearer = $headers['Authorization'] ?? null;
    
        if (!$bearer) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'failed', 'message' => 'Unauthorized', 'data' => null]);
            exit();
        }
    
        $token = str_replace('Bearer ', '', $bearer);
        $row = $this->AuthModel->find_valid_token($token);
    
        if (!$row) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'failed', 'message' => 'Invalid or expired token', 'data' => null]);
            exit();
        }
    
        $this->auth_user_id = $row->user_id;
        $this->auth_role = $row->role;
    }
}