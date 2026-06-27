<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EditVisitController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('DictionaryModel');
		$this->load->model('AuthModel');

		$this->load->helper('generator_helper');
		$this->load->helper('validator_helper');
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'history';
			$data['dt_dct_visit_by']= $this->DictionaryModel->get_dictionary_by_type('visit_by');
            $data['id']= $id;
			$data['is_mobile_device'] = is_mobile_device();
			$data['is_signed'] = true;

			$data['title_page'] = 'History | Edit';
			$data['content'] = $this->load->view('edit_visit/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}
}
