<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class FeedbackController extends CI_Controller {
	protected $httpClient;

	function __construct(){
		parent::__construct();
		$this->load->model('FeedbackModel');
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = [];
		if($this->AuthModel->current_user()){
			$data['is_signed'] = true;
		} else {
			$data['is_signed'] = false;
		}
		$data['active_page']= 'feedback';
		$data['is_mobile_device'] = is_mobile_device();

		$this->load->view('feedback/index', $data);
	}

	public function add_feedback(){
		$rules = $this->FeedbackModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'Feedback failed to sended. Validation failed');
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
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
		}
		redirect('FeedbackController');
	}
}
