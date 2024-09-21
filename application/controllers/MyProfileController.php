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
		$this->load->model('DictionaryModel');
		$this->load->model('ValidateRequestModel');
		$this->load->model('MultiModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$year = date('Y');
			$date = date('Y-m-d');
			$user_id = $this->session->userdata('user_id');
			$role_key = $this->session->userdata('role_key');

			$data['is_signed'] = true;
			$data['dt_my_profile'] = $this->AuthModel->current_user();
			$data['dt_visit_activity'] = $this->VisitModel->get_visit_activity($year);
			$data['dt_visit_activity_by_date'] = $this->VisitModel->get_visit_activity_by_date($date);
			$data['dt_my_gallery'] = $this->GalleryModel->get_all_my_gallery();
			$data['dt_active_telegram_user_id_request'] = $this->ValidateRequestModel->get_my_active_request('telegram_id_validation', $user_id);
			$data['is_mobile_device'] = is_mobile_device();
			$data['active_page']= 'myprofile';

			if($role_key == 0){
				$data['dt_all_user'] = $this->MultiModel->get_all_data('user',null,null,null);
				$data['dt_all_dct'] = $this->DictionaryModel->get_all_dct();
				$data['dt_all_feedback'] = $this->MultiModel->get_all_data('feedback',null,null,null);
			} else {
				$data['dt_all_user'] = null;
				$data['dt_all_dct'] = null;
				$data['dt_all_feedback'] = null;
			}

			$this->load->view('myprofile/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function edit_profile(){
		$rules = $this->AuthModel->rules_user(null);
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', generate_message(false,'update','profile','validation failed'));
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
				$this->session->set_flashdata('message_success', generate_message(true,'update','profile',null));
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'update','profile',null));
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

			$this->session->set_flashdata('message_success', generate_message(true,'update','profile image',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'update','profile image',null));
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
				'text' => "Hello,\n\nWe received a request to validate PinMarker apps's account with username <b>@$user->username</b> to sync with this Telegram account. If you initiated this request, please confirm that this account belongs to you by clicking the button YES.\n\nAlso we provided the Token :\n$token\n\nIf you did not request this, please press button NO.\n\nThank you, PinMarker",
				'parse_mode' => 'HTML'
			]);
			$this->session->set_flashdata('message_success', generate_message(true,'send','validation token',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'send','validation token',null));
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
				$this->session->set_flashdata('message_success', generate_message(true,'send','validation token',null));
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'send','validation token',null));
			}

			redirect('MyProfileController');
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'send','validation token',null));

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
				$this->session->set_flashdata('message_success', generate_message(true,'validate','token',null));

				redirect('MyProfileController');
			} else {
				redirect('MyProfileController');
			}
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'validate','token','wrong token'));

			redirect('MyProfileController');
		}
	}

	public function edit_category_color($id){
		$owner = $this->DictionaryModel->get_owner_list($id);
		$data = [
			'dictionary_color' => $this->input->post('dictionary_color')
		];
		if($this->DictionaryModel->update_table($data, $id)){
			if($owner){
				$this->telegram->sendMessage([
					'chat_id' => $owner->telegram_user_id,
					'text' => "Hello <b>$owner->username</b>, your category called <b>$owner->dictionary_name</b> color has been updated from $owner->dictionary_color to ".$data['dictionary_color'],
					'parse_mode' => 'HTML'
				]);
			}
			$this->session->set_flashdata('message_success', generate_message(true,'update','pin category color',null));
		} else {
			$this->session->set_flashdata('message_error',  generate_message(false,'update','pin category color',null));
		}

		redirect("MyProfileController");
	}

	public function delete_user(){
		$id = $this->input->post('id');
		$user = $this->AuthModel->get_user_by_id($id);

		if($user){
			if($user->telegram_user_id && $user->telegram_is_valid == 1){
				$this->telegram->sendMessage([
					'chat_id' => $user->telegram_user_id,
					'text' => "Hello <b>$user->username</b>, your account is deleted by admin. Sorry you cant access PinMarker apps from now",
					'parse_mode' => 'HTML'
				]);
			}
			
			if($this->MultiModel->delete('user',$id)){
				$this->session->set_flashdata('message_success', generate_message(true,'permanently delete','user',null));
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'permanently delete','user','user not found'));
			}
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'permanently delete','user','user not found'));
		}
		redirect('MyProfileController');
	}

	public function rename_category(){
		$id = $this->input->post('id');
		$dct_type = $this->input->post('dictionary_type');
		$dct_name_old = $this->input->post('dictionary_name_old');
		$dct_name_new = $this->input->post('dictionary_name_new');
		
		if($dct_type == 'pin_category'){
			$owner = $this->DictionaryModel->get_owner_list($id);
		} else {
			$owner = null;
		}

		$data = [
			'dictionary_name' => $dct_name_new
		];

		if($this->DictionaryModel->update_table($data, $id)){
			$tele_msg = "Hello <b>$owner->username</b>, your category called <b>$dct_name_old</b> has been renamed to <b>".$data['dictionary_name']."</b>";	
			$all_msg = "dictionary <b>$dct_name_old</b> has been renamed to <b>".$data['dictionary_name']."</b>";			

			if($dct_name_old && $dct_name_old != ""){
				$table_name = explode('_',$dct_type)[0];
				if($this->DictionaryModel->update_mass_dictionary($table_name,$dct_type,$dct_name_old,$dct_name_new)){
					if($owner && $dct_type == 'pin_category'){
						$this->telegram->sendMessage([
							'chat_id' => $owner->telegram_user_id,
							'text' => $tele_msg." and it affected some attached pin",
							'parse_mode' => 'HTML'
						]);
					} else if($dct_type == 'visit_by'){
						$users = $this->AuthModel->get_all_user_contact();

						foreach($users as $dt){
							$this->telegram->sendMessage([
								'chat_id' => $dt->telegram_user_id,
								'text' => "Hello <b>$dt->username</b>, $all_msg",
								'parse_mode' => 'HTML'
							]);
						}						
					}
					$this->session->set_flashdata('message_success', generate_message(true,'rename','dictionary','success to migrate'));
				} else {
					$this->session->set_flashdata('message_error', generate_message(false,'rename','dictionary','failed to migrate'));
				}
			} else {
				if($owner && $dct_type == 'pin_category'){
					$this->telegram->sendMessage([
						'chat_id' => $owner->telegram_user_id,
						'text' => $tele_msg,
						'parse_mode' => 'HTML'
					]);
				}
				$this->session->set_flashdata('message_success', generate_message(true,'rename','dictionary','nothing to migrate'));
			}
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'rename','dictionary',null));
		}

		redirect("MyProfileController");
	}

	public function delete_category(){
		$id = $this->input->post('id');
		$dct_name = $this->input->post('dictionary_migrate');
		$old_dct = $this->DictionaryModel->get_dct_by_id($id);

		if($old_dct->dictionary_type == 'pin_category'){
			$owner = $this->DictionaryModel->get_owner_list($id);
		} else {
			$owner = null;
		}

		if($this->MultiModel->delete('dictionary',$id)){
			if($owner){
				$tele_msg = "Hello <b>$owner->username</b>, your category called <b>$old_dct->dictionary_name</b> has been deleted";	
			}
			$all_msg = "dictionary <b>$old_dct->dictionary_name</b> has been deleted";			

			if($dct_name != ""){
				$table_name = explode('_',$old_dct->dictionary_type)[0];
				if($this->DictionaryModel->update_mass_dictionary($table_name,$old_dct->dictionary_type,$old_dct->dictionary_name,$dct_name)){
					if($owner && $old_dct->dictionary_type == 'pin_category'){
						$this->telegram->sendMessage([
							'chat_id' => $owner->telegram_user_id,
							'text' => $tele_msg." and it affected some attached pin",
							'parse_mode' => 'HTML'
						]);
					} else if($old_dct->dictionary_type == 'visit_by'){
						$users = $this->AuthModel->get_all_user_contact();

						foreach($users as $dt){
							$this->telegram->sendMessage([
								'chat_id' => $dt->telegram_user_id,
								'text' => "Hello <b>$dt->username</b>, $all_msg",
								'parse_mode' => 'HTML'
							]);
						}						
					}
					$this->session->set_flashdata('message_success', generate_message(true,'permanently delete','dictionary','success to migrate'));
				} else {
					$this->session->set_flashdata('message_error', generate_message(false,'permanently delete','dictionary','failed to migrate'));
				}
			} else {
				if($owner && $old_dct->dictionary_type == 'pin_category'){
					$this->telegram->sendMessage([
						'chat_id' => $owner->telegram_user_id,
						'text' => $tele_msg,
						'parse_mode' => 'HTML'
					]);
				}
				$this->session->set_flashdata('message_success', generate_message(true,'permanently delete','dictionary','nothing to migrate'));
			}
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'permanently delete','dictionary',null));
		}

		redirect("MyProfileController");
	}

	public function send_chat(){
		$username = $this->input->post('username');
		$user = $this->AuthModel->get_user_by_username($username);
		$chat = $this->input->post('chat');

		if($user){
			if($user->telegram_user_id && $user->telegram_is_valid == 1){
				$this->telegram->sendMessage([
					'chat_id' => $user->telegram_user_id,
					'text' => "[ADMIN] $chat",
					'parse_mode' => 'HTML'
				]);
				$this->session->set_flashdata('message_success', generate_message(true,'send','chat',"to @$username"));
			} else if($user->telegram_user_id && $user->telegram_is_valid == 0) {
				$this->session->set_flashdata('message_error', generate_message(false,'send','chat',"to @$username. Telegram account is not verified"));
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'send','chat',"to @$username. Telegram account not found"));
			}
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'send','chat',"to @$username. User not found"));
		}
		redirect('MyProfileController');
	}

	public function add_category(){
		$rules = $this->DictionaryModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', generate_message(false,'add','dictionary','validation failed'));
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
			$data = [
				'id' => get_UUID(), 
				'dictionary_type' => $this->input->post('dictionary_type'),
				'dictionary_name' => $this->input->post('dictionary_name'),
				'dictionary_color' => $this->input->post('dictionary_color') ?? null,
			];

			if($this->MultiModel->insert('dictionary', $data)){
				$this->session->set_flashdata('message_success', generate_message(true,'add','dictionary',null));
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'add','dictionary',null));
			}
		}
		redirect("MyProfileController");
	}

	public function delete_feedback(){
		$id = $this->input->post('id');
		if($this->MultiModel->delete('feedback',$id)){
			$this->session->set_flashdata('message_success', generate_message(true,'permanently deleted','feedback',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'permanently deleted','feedback','feedback not found'));
		}

		redirect('MyProfileController');
	}
}
