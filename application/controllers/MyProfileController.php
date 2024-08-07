<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class MyProfileController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('VisitModel');
		$this->load->model('GalleryModel');
		$this->load->model('ValidateRequestModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		$data = [];
		$year = date('Y');
		$date = date('Y-m-d');
		$user_id = $this->session->userdata('user_id');

		$data['dt_my_profile'] = $this->AuthModel->current_user();
		$data['dt_visit_activity'] = $this->VisitModel->get_visit_activity($year);
		$data['dt_visit_activity_by_date'] = $this->VisitModel->get_visit_activity_by_date($date);
		$data['dt_my_gallery'] = $this->GalleryModel->get_all_my_gallery();
		$data['dt_active_telegram_user_id_request'] = $this->ValidateRequestModel->get_my_active_request('telegram_id_validation', $user_id);
		$data['is_mobile_device'] = is_mobile_device();


		if($data['dt_my_profile']){
			$data['active_page']= 'myprofile';

			$this->load->view('myprofile/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function edit_profile(){
		$rules = $this->AuthModel->rules_user(null);
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'Profile failed to updated. Validation failed');
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
			$user_id = $this->session->userdata('user_id');

			$data = [
				'fullname' => $this->input->post('fullname'), 
				'username' => $this->input->post('username'), 
				'email' => $this->input->post('email'), 
				'updated_at' => date('Y-m-d H:i:s'), 
			];

			if($this->AuthModel->update_user($user_id,$data)){
				$this->session->set_flashdata('message_success', 'Profile updated');
			} else {
				$this->session->set_flashdata('message_error', 'Profile failed to updated');
			}
		}
		redirect('MyProfileController');
	}

	public function edit_image(){
		$user_id = $this->session->userdata('user_id');
		$img_url = $this->input->post('img_url');

		$data = [
			'img_url' => $img_url, 
			'updated_at' => date('Y-m-d H:i:s'), 
		];

		if($this->AuthModel->update_user($user_id,$data)){
			$this->session->set_userdata([
				'user_img_url' => $img_url
			]);

			$this->session->set_flashdata('message_success', 'Profile image updated');
		} else {
			$this->session->set_flashdata('message_error', 'Profile image failed to updated');
		}

		redirect('MyProfileController');
	}

	public function send_validation_token(){
		$user_id = $this->session->userdata('user_id');
		$user = $this->AuthModel->get_user_by_id($user_id);
		$token_length = 6;
		$token = get_token_validation($token_length);

		$data = [
			'id' => get_UUID(), 
			'request_type' => 'telegram_id_validation',
            'request_context' => $token, 
			'created_at' => date('Y-m-d H:i:s'), 
			'created_by' => $user_id
		];
		
		if($this->ValidateRequestModel->insert_request($data)){
			$this->telegram->sendMessage([
				'chat_id' => $user->telegram_user_id,
				'text' => "Hello,\n\nWe received a request to validate PinMarker apps's account with username <b>$user->username</b> to sync with this Telegram account. If you initiated this request, please confirm that this account belongs to you by clicking the button YES.\n\nAlso we provided the Token :\n$token\n\nIf you did not request this, please press button NO.\n\nThank you, PinMarker",
				'parse_mode' => 'HTML'
			]);
			$this->session->set_flashdata('message_success', 'Validation Token has sended');
		} else {
			$this->session->set_flashdata('message_error', 'Validation Token failed to send');
		}
		redirect('MyProfileController');
	}

	public function edit_telegram_id(){
		$user_id = $this->session->userdata('user_id');
		$user = $this->AuthModel->get_user_by_id($user_id);
		$token_length = 6;
		$token = get_token_validation($token_length);
		$new_tele_id = $this->input->post('telegram_user_id');

		$data_user = [
			'telegram_user_id' => $new_tele_id,
			'telegram_is_valid' => 0
		];

		$update_user = $this->AuthModel->update_user($user_id,$data_user);

		if($update_user){
			$data_request = [
				'id' => get_UUID(), 
				'request_type' => 'telegram_id_validation',
				'request_context' => $token, 
				'created_at' => date('Y-m-d H:i:s'), 
				'created_by' => $user_id
			];
				
			if($this->ValidateRequestModel->insert_request($data_request)){
				$this->telegram->sendMessage([
					'chat_id' => $new_tele_id,
					'text' => "Hello,\n\nWe received a request to validate PinMarker apps's account with username <b>$user->username</b> to sync with this Telegram account. If you initiated this request, please confirm that this account belongs to you by clicking the button YES.\n\nAlso we provided the Token :\n$token\n\nIf you did not request this, please press button NO.\n\nThank you, PinMarker",
					'parse_mode' => 'HTML'
				]);
				$this->session->set_flashdata('message_success', 'Validation Token has sended');
			} else {
				$this->session->set_flashdata('message_error', 'Validation Token failed to send');
			}

			redirect('MyProfileController');
		} else {
			$this->session->set_flashdata('message_error', 'Validation Token failed to send');

			redirect('MyProfileController');
		}
	}

	public function validate_token_telegram(){
		$user_id = $this->session->userdata('user_id');
		$user = $this->AuthModel->get_user_by_id($user_id);
		$check = $this->ValidateRequestModel->get_my_active_request('telegram_id_validation', $user_id);

		if($check->request_context == $this->input->post('token')){
			$data_user = [
				'telegram_is_valid' => 1
			];
	
			$update_user = $this->AuthModel->update_user($user_id,$data_user);
	
			if($update_user){
				$this->ValidateRequestModel->delete_request($check->id);
				
				$this->telegram->sendMessage([
					'chat_id' => $user->telegram_user_id,
					'text' => "Hello <b>$user->username</b>, Welcome to PinMarker!",
					'parse_mode' => 'HTML'
				]);
				$this->session->set_flashdata('message_success', 'Token validated!');

				redirect('MyProfileController');
			} else {
				redirect('MyProfileController');
			}
		} else {
			$this->session->set_flashdata('message_error', 'Wrong token!');

			redirect('MyProfileController');
		}
	}
}
