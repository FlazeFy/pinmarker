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

		$this->load->helper('generator_helper');
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
}
