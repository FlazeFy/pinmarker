<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
        $this->load->model('VisitModel');
		$this->load->model('DictionaryModel');
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'maps';
            $data['dt_detail_pin']= $this->PinModel->get_pin_by_id($id);
            $data['dt_visit_history']= $this->VisitModel->get_visit_history_by_pin_id($id);
            $data['dt_my_personal_pin']= $this->PinModel->get_pin_by_category('Personal', $id);
			$data['dt_all_my_pin_name']= $this->PinModel->get_all_my_pin_name();
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$this->load->view('detail/index', $data);
		} else {
			redirect('logincontroller');
		}
	}

	public function favorite_toogle($id){
		$data = [
			'is_favorite' => $this->input->post('is_favorite'),
			'updated_at' => date('Y-m-d H:i:s'), 
		];

		$this->PinModel->update_marker($data, $id);
		redirect("/detailcontroller/view/$id");
	}

	public function edit_toogle($id){
		$is_edit = $this->session->userdata('is_edit_mode');
		if($is_edit == false){
			$this->session->set_userdata('is_edit_mode', true);
		} else {
			$this->session->set_userdata('is_edit_mode', false);
		}

		redirect("/detailcontroller/view/$id");
	}
}