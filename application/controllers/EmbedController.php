<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmbedController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
		$this->load->model('MultiModel');
        $this->load->model('VisitModel');
		$this->load->model('BotRelModel');
		$this->load->model('FeedbackModel');
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

	public function distribution_bot()
	{
		$data = [];

		$data['dt_relation_type']= $this->BotRelModel->distribution_bot_by_context('relation_type');
		$data['dt_relation_platform']= $this->BotRelModel->distribution_bot_by_context('relation_platform');

		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Bot Distribution';
		$data['content'] = $this->load->view('embed/bot_distribution',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function most_active_user()
	{
		$data = [];
		$limit_user = $this->input->get('limit_user') ?? 7;

		$data['dt_user_most_pin']= $this->PinModel->user_most_pin($limit_user);

		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Most Active User';
		$data['content'] = $this->load->view('embed/most_active_user',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function most_visited_pin_category()
	{
		$data = [];
		$limit_category = $this->input->get('limit_category') ?? 7;

		$data['dt_most_visited_pin_category']= $this->VisitModel->most_visited_pin_category($limit_category);

		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Most Visited Pin Category';
		$data['content'] = $this->load->view('embed/most_visited_pin_category',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function feedback_distribution()
	{
		$data = [];

		$data['dt_feedback_distribution']= $this->FeedbackModel->feedback_distribution();

		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Feedback Distribution';
		$data['content'] = $this->load->view('embed/feedback_distribution',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function total_pin_created_monthly_by_year()
	{
		$data = [];
		$year = $this->input->get('year') ?? date('Y');

		$data['dt_total_context_created_monthly_by_year']= $this->MultiModel->get_total_context_created_monthly_by_year($year,"pin");

		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Total Pin Created Monthly By Year';
		$data['ctx'] = "total_created_pin_by_month";
		$data['label'] = "Total Pin";
		$data['content'] = $this->load->view('embed/total_context_created_monthly_by_year',$data,true);
		$this->load->view('others/layout', $data);
	}

	public function total_visit_monthly_by_year()
	{
		$data = [];
		$year = $this->input->get('year') ?? date('Y');

		$data['dt_total_context_created_monthly_by_year']= $this->MultiModel->get_total_context_created_monthly_by_year($year,"visit");

		$data['active_page']= 'embed';
		$data['is_mobile_device'] = is_mobile_device();
		$data['title_page'] = 'PinMarker | Total Visit Monthly By Year';
		$data['ctx'] = "total_visit_by_month";
		$data['label'] = "Total Visit";
		$data['content'] = $this->load->view('embed/total_context_created_monthly_by_year',$data,true);
		$this->load->view('others/layout', $data);
	}
}
