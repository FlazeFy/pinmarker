<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class FeedbackController extends CI_Controller {
	protected $httpClient;

	function __construct(){
		parent::__construct();
		$this->load->model('FeedbackModel');
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'feedback';
			$this->load->view('feedback/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function add_feedback(){

		$data = [
			'id' => get_UUID(), 
			'feedback_rate' => $this->input->post('feedback_rate'), 
			'feedback_body' => $this->input->post('feedback_body'),
			'created_at' => date("Y-m-d H:i:s")
		];

		$res = $this->FeedbackModel->insert_feedback($data);

		if($res){
			$this->session->set_flashdata('message_insert_success', 'Feedback Sended. Thank you!');
		} else {
			$this->session->set_flashdata('message_insert_error', 'Feedback failed to send');
		}

		redirect('FeedbackController');
	}
}
