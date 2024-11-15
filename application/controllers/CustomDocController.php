<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Dompdf\Options;

class CustomDocController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
        $this->load->model('VisitModel');
		$this->load->model('TokenModel');

		$this->load->helper('generator_helper');
		$this->load->library('form_validation');

		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function view($id)
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['is_signed'] = true;
			$data['id'] = $id;
			$data['dt_coor_pin']= $this->PinModel->get_pin_coor_by_id($id);
			$data['is_mobile_device'] = is_mobile_device();
			$data['detail_generated'] = $this->generate_detail($id);
			$data['active_page']= 'list';

			$data['title_page'] = 'List | Detail | Custom';
			$data['content'] = $this->load->view('custom_doc/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function generate_detail($id)
	{		
		$user_id = $this->session->userdata('user_id');
		$dt_pin = $this->PinModel->get_pin_by_id($id);

		if($dt_pin){
			require 'vendor/autoload.php';

			$dt_visit_history = $this->VisitModel->get_visit_history_by_pin_id($id, null, null);
			$user = $this->AuthModel->get_user_by_id($user_id);
			$time = time();
			$datetime = date("Y-m-d H:i:s");
			$options = new Options();
			$options->set('defaultFont', 'Helvetica');
			$dompdf = new Dompdf($options);
			$pin_desc = "-";
			$pin_call = "-";
			$pin_email = "-";
			$pin_address = "-";
			$pin_person = "-";
			$updated_at = "-";

			if($dt_pin->pin_desc){
				$pin_desc = $dt_pin->pin_desc;
			} 
			if($dt_pin->pin_call){
				$pin_call = $dt_pin->pin_call;
			} 
			if($dt_pin->pin_email){
				$pin_email = $dt_pin->pin_email;
			} 
			if($dt_pin->pin_person){
				$pin_person = $dt_pin->pin_person;
			} 
			if($dt_pin->pin_address){
				$pin_address = $dt_pin->pin_address;
			} 
			if($dt_pin->updated_at){
				$updated_at = $dt_pin->updated_at;
			} 

			$html = generate_document_template("html_header",null).generate_document_template("document_header",null).generate_document_pin_detail_body($dt_pin, $pin_desc, $pin_address, $pin_person, $pin_email, $pin_call, $updated_at, $dt_visit_history).generate_document_template("document_footer",$user->username);

			$this->session->set_flashdata('message_success', generate_message(true,'generate','document',null));
			return $html;
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'generate','document','no data to generated'));
			return null;
		}
		redirect("DetailController/view/$id");
	}
}
