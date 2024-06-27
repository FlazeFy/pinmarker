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
		

		redirect('DashboardController');
	}
}
