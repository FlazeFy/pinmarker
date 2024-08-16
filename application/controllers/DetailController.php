<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DetailController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
        $this->load->model('VisitModel');
		$this->load->model('DictionaryModel');
		$this->load->model('GalleryModel');
		$this->load->model('HistoryModel');
		$this->load->model('MultiModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['is_mobile_device'] = is_mobile_device();

			if($data['is_mobile_device']){
				$per_page = 8;
			} else {
				$per_page = 14;
			}
			$offset = 0;

			if($this->session->userdata('page_detail_history')){
				$offset = $this->session->userdata('page_detail_history') * $per_page;
			}

			$data['is_signed'] = true;
			$data['active_page']= 'list';
            $data['dt_detail_pin']= $this->PinModel->get_pin_by_id($id);
            $data['dt_visit_history']= $this->VisitModel->get_visit_history_by_pin_id($id, $per_page, $offset);
            $data['dt_my_personal_pin']= $this->PinModel->get_pin_by_category('Personal', $id);
			$data['dt_all_my_pin_name']= $this->PinModel->get_all_my_pin_name();
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			$data['dt_all_gallery_by_pin']= $this->GalleryModel->get_all_gallery_by_pin($id);
			$data['dt_total_visit_by_by_pin']= $this->VisitModel->get_total_visit_by_by_pin_id($id); 

			$this->load->view('detail/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function favorite_toogle($id){
		$data = [
			'is_favorite' => $this->input->post('is_favorite'),
			'updated_at' => date('Y-m-d H:i:s'), 
		];

		$this->PinModel->update_marker($data, $id);
		redirect("/DetailController/view/$id");
	}

	public function edit_toogle($id){
		$is_edit = $this->session->userdata('is_edit_mode');
		if($is_edit == false){
			$this->session->set_userdata('is_edit_mode', true);
		} else {
			$this->session->set_userdata('is_edit_mode', false);
		}

		redirect("/DetailController/view/$id");
	}

	public function add_gallery($id){
		$user_id = $this->session->userdata('user_id');
		$type = $this->input->post('gallery_type');
		$capt = $this->input->post('gallery_caption');

		$data = [
			'id' => get_UUID(), 
			'pin_id' => $id, 
			'gallery_type' => $type, 
			'gallery_url' => $this->input->post('gallery_url'), 
			'gallery_caption' => $capt, 
			'created_at' => date('Y-m-d H:i:s'), 
			'created_by' => $user_id
		];

		if($this->GalleryModel->insert_gallery($data)){
			$pin_name = $this->PinModel->get_pin_name_by_id($id);

			$history_ctx = "Add $type for $pin_name";
			if($capt != null || $capt != ""){
				$history_ctx = "Add $type for $pin_name with caption $capt";
			}	
			$this->HistoryModel->insert_history('Add Gallery', $history_ctx);

			redirect("/DetailController/view/$id");
		} else {
			redirect("/DetailController/view/$id");
		}
	}

	public function delete_gallery($id){
		$gallery_id = $this->input->post('id');
		if($this->GalleryModel->delete_gallery($gallery_id)){
			$pin_name = $this->PinModel->get_pin_name_by_id($id);

			$history_ctx = "From $pin_name";
			$this->HistoryModel->insert_history('Remove Gallery', $history_ctx);

			redirect("/DetailController/view/$id");
		} else {
			redirect("/DetailController/view/$id");
		}
	}

	public function delete_pin($id){
		if($this->MultiModel->soft_delete('pin', $id)){
			$pin_name = $this->PinModel->get_pin_name_by_id($id);

			$history_ctx = "From $pin_name";
			$this->HistoryModel->insert_history('Delete pin', $history_ctx);
			$this->session->set_flashdata('message_success', 'Pin deleted');

			redirect("/ListController");
		} else {
			$this->session->set_flashdata('message_error', 'Pin failed to deleted');
			redirect("/DetailController/view/$id");
		}
	}

	public function edit_marker($id){
		$rules = $this->PinModel->rules(null);
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'Pin failed to updated. Validation failed');
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
			$split_pin_cat = explode("-", $this->input->post('pin_category'));
			$pin_cat = $split_pin_cat[0];
			$pin_name = $this->input->post('pin_name');

			if($this->PinModel->get_pin_availability($pin_name, $id, 'update')){
				$data = [
					'pin_name' => $pin_name, 
					'pin_desc' => $this->input->post('pin_desc'), 
					'pin_lat' => $this->input->post('pin_lat'), 
					'pin_long' => $this->input->post('pin_long'), 
					'pin_category' => $pin_cat, 
					'pin_person' => $this->input->post('pin_person'), 
					'pin_call' => $this->input->post('pin_call'), 
					'pin_email' => $this->input->post('pin_email'), 
					'pin_address' => $this->input->post('pin_address'), 
					'updated_at' => date('Y-m-d H:i:s'), 
				];

				if($this->PinModel->update_marker($data, $id)){	
					$this->session->set_flashdata('message_success', 'Pin updated');
				} else {
					$this->session->set_flashdata('message_error', 'Pin failed to updated');
				}
			} else {
				$this->session->set_flashdata('message_error', 'Pin failed to updated. Name already exist');
			}
		}
		redirect("/DetailController/view/$id");
	}

	public function navigate($id,$page){
		$this->session->set_userdata('page_detail_history', $page);

		redirect("DetailController/view/$id");
	}
}
