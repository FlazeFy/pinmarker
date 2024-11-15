<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class RegisterController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		$data = [];
		$data['is_mobile_device'] = is_mobile_device();

		$data['title_page'] = 'PinMarker | Register';
		$data['content'] = $this->load->view('register/index',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function register(){
		$rules = $this->AuthModel->rules_user();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', generate_message(false,'register','user','validation failed'));
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
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
				$this->session->set_flashdata('message_success', generate_message(true,'regist','to PinMarker','Welcome <b>@'.$this->input->post('username').'</b>. If you want to connect this account with Telegram you can go to <a href="/MyProfileController" id="my-profile-welcome-btn" class="fw-bold">Profile<a> Menu.<br><br>Thank You, - PinMarker'));
				redirect('/DashboardController');
			} else {
				redirect('/');
			}
		}
	}
}
