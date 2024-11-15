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
		$this->load->helper('validator_helper');
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

			$data['title_page'] = 'History | Detail | '.($data['dt_detail_visit']->pin_name ? $data['dt_detail_visit']->visit_desc." at ".$data['dt_detail_visit']->pin_name : $data['dt_detail_visit']->visit_desc);
			$data['content'] = $this->load->view('detail_visit/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function edit_visit($id){
		$rules = $this->VisitModel->rules(null);
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', generate_message(false,'update','visit','validation failed'));
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
			if($this->input->post('type_edit') != 'visit_custom'){
				$pin_id_split = explode("/", $this->input->post('pin_id'));
				$pin_id = $pin_id_split[0];
				$visit_desc = $this->input->post('visit_desc');
			} else {
				$pin_id = null;
				$visit_desc = $this->input->post('visit_desc')." at ".$this->input->post('location_name');
			}
			$data = [
				'pin_id' => $this->input->post('type_add') != 'visit_custom' ? $pin_id : null, 
				'visit_desc' => $visit_desc, 
				'visit_by' => $this->input->post('visit_by'), 
				'visit_with' => $this->input->post('visit_with'), 
				'created_at' => $this->input->post('visit_date')." ".$this->input->post('visit_hour'),
				'updated_at' => date('Y-m-d H:i:s'), 
			];

			if($this->VisitModel->update_visit($data,$id)){
				if($visit_desc == null){
					$pin_name = $pin_id_split[1];
					$this->HistoryModel->insert_history('Edit Visit', $pin_name);
				} else {
					$this->HistoryModel->insert_history('Edit Visit', $visit_desc);
				}

				$dir = $this->input->post('coordinate_dir');
				if($dir != "" && $dir != null){
					$pin = $this->PinModel->get_pin_by_id($dir);
					if($pin){
						$dir = "$pin->pin_lat,$pin->pin_long";

						redirect("https://www.google.com/maps/dir/My+Location/$dir");
					} else {
						$this->session->set_flashdata('message_success', generate_message(true,'add','visit','failed to make maps direction'));
						redirect("DetailVisitController/view/$id");
					}
				} else {
					$this->session->set_flashdata('message_success', generate_message(true,'add','visit',null));
					redirect("DetailVisitController/view/$id");
				}
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'add','visit',null));
				redirect("DetailVisitController/view/$id");
			}
		}
	}
}