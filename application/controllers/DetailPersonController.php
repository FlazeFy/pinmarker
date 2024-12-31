<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailPersonController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('VisitModel');
		$this->load->model('PinModel');

		$this->load->helper('generator_helper');
	}

	public function view($name)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$year = date('Y');
			$data['is_mobile_device'] = is_mobile_device();
			if($data['is_mobile_device']){
				$per_page = 8;
			} else {
				$per_page = 14;
			}
			$offset = 0;

			$data['is_signed'] = true;
			$data['active_page'] = 'detail_person';
			$data['raw_name'] = str_replace("%20"," ",$name);
			$data['clean_name'] = ucwords(str_replace("%20"," ",$name));
			$data['total_appearance'] = $this->VisitModel->get_total_appearance($name);

			if($this->session->userdata('page_visit')){
				$offset = $this->session->userdata('page_visit') * $per_page;
			}
			$data['dt_visit_by_person'] = $this->VisitModel->get_visit_by_person($name, $per_page, $offset);
			$data['dt_pin_by_person'] = $this->PinModel->get_pin_by_person($name);
			$data['dt_visit_pertime_hour'] = $this->VisitModel->get_visit_pertime_by_person($name,'hour');
			$data['dt_visit_pertime_year'] = $this->VisitModel->get_visit_pertime_by_person($name,'month',$year);
			$data['dt_visit_pertime_dayname'] = $this->VisitModel->get_visit_pertime_by_person($name,'dayname',$year);
			$data['dt_visit_location'] = $this->VisitModel->get_visit_location_by_person($name);
			$data['dt_visit_location_category'] = $this->VisitModel->get_visit_location_category_by_person($name);
			$data['dt_visit_location_favorite'] = $this->VisitModel->get_visit_location_favorite_by_person($name);
			$data['dt_visit_daily_hour_by_person'] = $this->VisitModel->get_visit_daily_hour_by_person($name);
			$data['dt_visit_location_favorite_tag_by_person'] = $this->VisitModel->get_visit_location_favorite_tag_by_person($name);

			$data['title_page'] = 'Detail | Person | '.$data['clean_name'];
			$data['content'] = $this->load->view('detail_person/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function navigate($name,$page){
		$this->session->set_userdata('page_visit', $page);

		redirect("DetailPersonController/view/$name");
	}
}
