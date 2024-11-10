<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailVisitController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
        $this->load->model('VisitModel');
		$this->load->model('DictionaryModel');
		$this->load->model('HistoryModel');
		$this->load->model('MultiModel');
		$this->load->model('TokenModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;
			$data['active_page']= 'history';
			$data['id'] = $id;
			$data['dt_dct_visit_by']= $this->DictionaryModel->get_dictionary_by_type('visit_by');
			$data['dt_all_my_pin_name']= $this->PinModel->get_all_my_pin_name();
			$data['dt_my_contact']= $this->PinModel->get_person_in_contact();
            $data['dt_detail_visit']= $this->VisitModel->get_visit_by_id($id);

			$this->load->view('detail_visit/index', $data);
		} else {
			redirect('LoginController');
		}
	}
}
