<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('VisitModel');
		$this->load->model('GalleryModel');
		$this->load->model('AuthModel');
		$this->load->model('MultiModel');

		$this->load->helper('generator_helper');
	}

	public function index()
	{
		$data = [];
		$data['dt_my_profile'] = $this->AuthModel->current_user();
		$year = $this->session->userdata('year_filter') ?? date('Y');
		if($data['dt_my_profile']){
			$data['active_page']= 'dashboard';
			$data['dt_count_my_pin']= $this->PinModel->count_my_pin();
			$data['dt_count_my_fav_pin']= $this->PinModel->count_my_fav_pin();
			$data['dt_get_most_category']= $this->PinModel->get_most_category(1);
			$data['dt_get_latest_pin']= $this->PinModel->get_latest_pin();
			$data['dt_get_most_visit']= $this->VisitModel->get_most_visit('pin_name',1);
			$data['dt_get_most_visit_with']= $this->VisitModel->get_most_visit_with(7);
			$data['dt_get_stats_total_pin_by_category']= $this->PinModel->get_most_category(7); // for now
			$data['dt_get_stats_total_visit_pin_category']= $this->VisitModel->get_most_visit('pin_category',7); // for now
			$data['dt_get_stats_total_visit_by']= $this->VisitModel->get_most_visit('visit_by',7); // for now
			$data['dt_get_total_visit_by_month']= $this->VisitModel->get_total_visit_by_month($year);
			$data['dt_get_stats_total_gallery']= $this->GalleryModel->get_most_gallery(7);
			$data['dt_available_year'] = $this->MultiModel->get_available_year();
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

			$data['title_page'] = 'PinMarker | Dashboard';
			$data['content'] = $this->load->view('dashboard/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function filter_year(){
		$year = $this->input->post('year_filter');
		$this->session->set_userdata('year_filter', $year);
		$this->session->set_flashdata('message_success', generate_message(true,'change filter of','year',null));

		redirect('DashboardController');
	}
}
