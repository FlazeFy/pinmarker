<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class ForgetController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
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
		$data['is_mobile_device'] = is_mobile_device();

		$data['title_page'] = 'PinMarker | Forget Password';
		$data['content'] = $this->load->view('forget/index',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function forget_pass(){
		$found_user = $this->AuthModel->get_user_by_username($this->input->post('username'));
		if(!$found_user){
			$this->session->set_flashdata('message_error', generate_message(false,'forget password','user','user not found'));
			redirect('/');
		} else {
			$found_context = $this->ValidateRequestModel->get_my_active_request('forget', $found_user->id);
			if($found_context){
				$this->session->set_flashdata('message_error', generate_message(false,'forget password','user','user token not found'));
				redirect('/');
			} else {
				$data = [
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT), 
					'updated_at' => date('Y-m-d H:i:s'), 
				];

				$res = $this->AuthModel->update_user($id,$data);

				if($res){
					$this->AuthModel->login($data['username'], $this->input->post('password'));
					$this->session->set_flashdata('message_success', generate_message(true,'update','password',null));
					redirect('/DashboardController');
				} else {
					redirect('/');
				}
			}
		}
	}
}
