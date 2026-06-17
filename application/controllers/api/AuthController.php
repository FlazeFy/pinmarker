<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('AuthModel');
        $this->load->helper('validator_helper');
    }

    public function post_login(){
        // Decode JSON body
        $body = json_decode(file_get_contents('php://input'), true);

        // Body param
        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;

        // Validate param
        if (!$username || !$password) return api_response(400, 'failed', 'username and password are required', null);

        // Authenticate user
        $user = $this->AuthModel->api_login($username, $password);

        if (!$user) return api_response(401, 'failed', 'wrong username or password', null);

        // Generate token
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