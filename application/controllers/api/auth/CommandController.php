<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommandController extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('AuthModel');
        $this->load->helper('validator_helper');
        $this->load->library('form_validation');
    }

    public function post_login(){
        // Decode JSON body
        $body = json_decode(file_get_contents('php://input'), true);

        // Body param
        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;
        
        $input = json_decode($this->input->raw_input_stream, true);
        $_POST = $input;

        // Validate rules
        $rules = $this->AuthModel->rules();
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            return api_response(400, 'failed', 'validation failed', validation_errors());
        } else {
            // Validate param
            if (!$username || !$password) return api_response(400, 'failed', 'username and password are required', null);

            // Model : Authenticate user by username / email and password
            $user = $this->AuthModel->api_login($username, $password);

            if (!$user) return api_response(401, 'failed', 'wrong username or password', null);

            // Model : Generate token
            $token = $this->AuthModel->create_token($user['id'], $user['role']);

            // Return API response
            return api_response(200, 'success', 'Login success', [
                'token' => $token,
                'user_id' => $user['id'],
                'role' => $user['role'],
                'img_url' => $user['img_url']
            ]);
        }
    }
}