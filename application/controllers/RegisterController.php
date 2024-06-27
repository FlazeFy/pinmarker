<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class RegisterController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		$data = [];

		$this->load->view('register/index', $data);
	}

	public function register(){
		$data = [
			'id' => get_UUID(),
			'fullname' => $this->input->post('fullname'), 
			'username' => $this->input->post('username'), 
			'email' => $this->input->post('email'), 
			'telegram_user_id' => null, 
			'telegram_is_valid' => 0, 
			'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT), 
			'img_url' => null, 
			'created_at' => date('Y-m-d H:i:s'), 
			'updated_at' => null, 
			'last_login' => date('Y-m-d H:i:s')
		];

		$res = $this->AuthModel->insert_user($data);

		if($res){
			$this->AuthModel->login($data['username'], $this->input->post('password'));
			redirect('/DashboardController');
		}
	}
}
