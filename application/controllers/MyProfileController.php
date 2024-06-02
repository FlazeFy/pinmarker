<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyProfileController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('VisitModel');
		$this->load->model('GalleryModel');
	}

	public function index()
	{
		$data = [];
		$year = date('Y');
		$date = date('Y-m-d');

		$data['dt_my_profile'] = $this->AuthModel->current_user();
		$data['dt_visit_activity'] = $this->VisitModel->get_visit_activity($year);
		$data['dt_visit_activity_by_date'] = $this->VisitModel->get_visit_activity_by_date($date);
		$data['dt_my_gallery'] = $this->GalleryModel->get_all_my_gallery();

		if($data['dt_my_profile']){
			$data['active_page']= 'myprofile';

			$this->load->view('myprofile/index', $data);
		} else {
			redirect('logincontroller');
		}
	}

	public function edit_profile(){
		$user_id = $this->session->userdata('user_id');

		$data = [
			'fullname' => $this->input->post('fullname'), 
			'username' => $this->input->post('username'), 
			'email' => $this->input->post('email'), 
			'updated_at' => date('Y-m-d H:i:s'), 
		];

		$this->AuthModel->update_user($user_id,$data);

		redirect('myprofilecontroller');
	}

	public function edit_image(){
		$user_id = $this->session->userdata('user_id');
		$img_url = $this->input->post('img_url');

		$data = [
			'img_url' => $img_url, 
			'updated_at' => date('Y-m-d H:i:s'), 
		];

		$this->AuthModel->update_user($user_id,$data);

		$this->session->set_userdata([
			'user_img_url' => $img_url
		]);

		redirect('myprofilecontroller');
	}
}
