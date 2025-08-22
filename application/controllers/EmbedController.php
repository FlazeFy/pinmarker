<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmbedController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
        $this->load->model('VisitModel');
	}

	public function apps_summary()
	{
		$data = [];

		$data['dt_total_pin'] = $this->PinModel->get_total_all();
		$data['dt_total_user'] = $this->AuthModel->get_total_all();
		$data['dt_total_visit'] = $this->VisitModel->get_total_all();

		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Apps Summary';
		$data['content'] = $this->load->view('embed/apps_summary',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function distribution_pin_visit()
	{
		$data = [];
		$limit_pin   = $this->input->get('limit_pin') ?? 7;
    	$limit_visit = $this->input->get('limit_visit') ?? 7;

		$data['dt_distribution_pin']= $this->PinModel->distribution_pin_by_context('pin_category',$limit_pin);
		$data['dt_distribution_visit']= $this->VisitModel->distribution_visit_by_context('visit_by',$limit_visit);


		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Content Distribution';
		$data['content'] = $this->load->view('embed/content_distribution',$data,true);
		$this->load->view('others/layout', $data);
	}


}
