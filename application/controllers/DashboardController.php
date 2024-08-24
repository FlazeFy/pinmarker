<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('VisitModel');
		$this->load->model('GalleryModel');
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');
	}

	public function index()
	{
		$data = [];
		$data['dt_my_profile'] = $this->AuthModel->current_user();
		if($data['dt_my_profile']){
			$data['active_page']= 'dashboard';
			$data['dt_count_my_pin']= $this->PinModel->count_my_pin();
			$data['dt_count_my_fav_pin']= $this->PinModel->count_my_fav_pin();
			$data['dt_get_most_category']= $this->PinModel->get_most_category(1);
			$data['dt_get_latest_pin']= $this->PinModel->get_latest_pin();
			$data['dt_get_most_visit']= $this->VisitModel->get_most_visit('pin_name',1);
			$data['dt_get_stats_total_pin_by_category']= $this->PinModel->get_most_category(6); // for now
			$data['dt_get_stats_total_visit_by_category']= $this->VisitModel->get_most_visit('pin_category',6); // for now
			$data['dt_get_total_visit_by_month']= $this->VisitModel->get_total_visit_by_month();
			$data['dt_get_stats_total_gallery']= $this->GalleryModel->get_most_gallery(6);
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;

			if ($this->session->userdata('role_key') == 0){
				$data['dt_count_my_visit']= $this->VisitModel->count_my_visit();
				$data['dt_total_user']= $this->AuthModel->get_total_user();
				$data['dt_avg_gallery_pin']= $this->PinModel->get_avg_gallery_pin();
				$data['dt_avg_pin_user']= $this->PinModel->get_avg_pin_user();
				$data['dt_avg_visit_pin']= $this->PinModel->get_avg_visit_pin();
			} else {
				$data['dt_get_last_visit']= $this->VisitModel->get_last_visit();
			}

			$this->load->view('dashboard/index', $data);
		} else {
			redirect('LoginController');
		}
	}
}
