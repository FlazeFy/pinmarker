<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

class DetailGlobalController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('GlobalListModel');
		$this->load->model('PinModel');
		$this->load->model('HistoryModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function view_global_list_pin($id){
		if($this->session->userdata('view_mode_global_list_pin') == 'catalog'){
			$this->session->set_userdata('view_mode_global_list_pin', 'table');
		} else {
			$this->session->set_userdata('view_mode_global_list_pin', 'catalog');
		}

		redirect("DetailGlobalController/view/$id");
	}

	public function remove_pin($list_id){
		$pin_id = $this->input->post('pin_rel_id');
		if($this->GlobalListModel->delete_global_list_rel(['id'=>$pin_id])){
			$this->session->set_flashdata('message_success', generate_message(true,'remove','pin',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'remove','pin',null));
		}

		redirect("DetailGlobalController/view/$list_id");
	}

	public function add_pin_rel($list_id){
		$list_pin = $this->input->post('list_pin');

		if($list_pin != ''){
			$count_success = 0;
			$count_failed = 0;

			$pin_split = explode(",",$list_pin);
			foreach($pin_split as $dt){
				if($this->GlobalListModel->insert_rel([
					'id' => get_UUID(),
					'pin_id' => $dt,
					'list_id' => $list_id,
					'created_at' => date("Y-m-d H:i:s"), 
					'created_by' => $this->session->userdata('user_id')
				])){
					$count_success++;
				} else {
					$count_failed++;
				}
			}

			$extra_msg = "with $count_success success and $count_failed failed pin attached";

			$this->session->set_flashdata('message_success', generate_message(true,'add','pin',$extra_msg));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'add','pin',null));
		}

		
		redirect("DetailGlobalController/view/$list_id");
	}
}
