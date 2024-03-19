<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
        $this->load->model('VisitModel');
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'maps';
            $data['dt_detail_pin']= $this->PinModel->get_pin_by_id($id);
            $data['dt_visit_history']= $this->VisitModel->get_visit_history_by_pin_id($id);
            $data['dt_my_personal_pin']= $this->PinModel->get_pin_by_category('Personal', $id);
			$this->load->view('detail/index', $data);
		} else {
			redirect('logincontroller');
		}
	}
}
