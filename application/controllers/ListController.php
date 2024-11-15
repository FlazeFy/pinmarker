<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Dompdf\Options;

class ListController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
		$this->load->model('AuthModel');
		$this->load->model('HistoryModel');
		$this->load->model('DictionaryModel');
		$this->load->model('GlobalListModel');
		$this->load->model('MultiModel');
		
		$this->load->helper('generator_helper');
		$this->load->library('form_validation');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['is_mobile_device'] = is_mobile_device();
			$user_id = $this->session->userdata('user_id');

			if($data['is_mobile_device']){
				$per_page = 8;
			} else {
				$per_page = 14;
			}
			$offset = 0;

			$data['active_page']= 'list';
			$data['is_signed'] = true;
			$data['dt_dct_pin_category']= $this->DictionaryModel->get_dictionary_by_type('pin_category');
			if($this->session->userdata('is_catalog_view') == false || $this->session->userdata('open_pin_list_category')){
				$category = null;
				if($this->session->userdata('open_pin_list_category')){
					$category = $this->session->userdata('open_pin_list_category');
				}

				if($this->session->userdata('page_pin')){
					$offset = $this->session->userdata('page_pin') * $per_page;
				}

				$data['dt_my_pin']= $this->PinModel->get_all_my_pin('list', $category, $per_page,$offset);
			} else {
				$data['dt_my_pin']= $this->PinModel->get_pin_list_by_category($user_id);
			}
			$data['dt_my_category'] = $this->DictionaryModel->get_my_pin_category();
			$data['dt_my_list'] = $this->GlobalListModel->get_global_list_name($user_id);

			$this->load->view('list/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function print_pin()
	{		
		$user_id = $this->session->userdata('user_id');
		$dt_all_pin = $this->PinModel->get_all_my_pin('list',null,null,null);

		if($dt_all_pin){
			require 'vendor/autoload.php';

			$user = $this->AuthModel->get_user_by_id($user_id);

			$time = time();
			$datetime = date("Y-m-d H:i:s");
			$options = new Options();
			$options->set('defaultFont', 'Helvetica');
			$dompdf = new Dompdf($options);
			$body = "";

			foreach($dt_all_pin as $dt){
				$body .= "
				<tr>
					<td style='text-align:center;'>$dt->pin_name</td>
					<td>
						<h6>Notes : </h6>
						".($dt->pin_desc ?? '-')."
						<h6>Address : </h6>
						".($dt->pin_address ?? '-')."
						<h6>Coordinate : </h6>
						$dt->pin_lat, $dt->pin_long
					</td>
					<td style='text-align:center;'>$dt->pin_category</td>
					<td>
						<h6>Person In Contact : </h6>
						".($dt->pin_person ?? '-')."
						<h6>Email : </h6>
						".($dt->pin_email ?? '-')."
						<h6>Phone Number : </h6>
						".($dt->pin_call ?? '-')."
					</td>
					<td style='text-align:center;'>$dt->total_visit</td>
					<td>".date("Y-m-d H:i", strtotime($dt->created_at))."</td>
				</tr>
				";
			}

			$html = "
			<html>
				<head>
					<title>Pinmarker</title>
					".generate_document_template("html_header",null)."
				</head>
				<body>
					".generate_document_template("document_header",null)."
					<h4>Pin List</h4>
					<table>
						<thead>
							<tr>
								<th>Pin Name</th>
								<th>Info</th>
								<th>Category</th>
								<th>Contact</th>
								<th>Total Visit</th>
								<th>Created At</th>
							</tr>
						</thead>
						<tbody>".$body."</tbody>
					</table>
					".generate_document_template("document_footer",$user->username)."
				</body>
			</html>";

			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			$pdfFilePath = "my_marker_$user->username-$time.pdf";
			$dompdf->stream($pdfFilePath, array("Attachment" => 0));

			file_put_contents($pdfFilePath, $dompdf->output());
			$inputFile = InputFile::create('./'.$pdfFilePath, $pdfFilePath);
		
			$this->telegram->sendDocument([
				'chat_id' => $user->telegram_user_id,
				'document' => $inputFile,
				'caption' => "Hello <b>{$user->username}</b>, Here the Pin List you have generated",
				'parse_mode' => 'HTML'
			]);
		
			unlink($pdfFilePath);

			// $this->HistoryModel->insert_history('Generate Document', 'Marker List');
			
			$this->session->set_flashdata('message_success', generate_message(true,'generate','document',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'generate','document','no data to generated'));
		}
		redirect('ListController');
	}
	
	public function view_toogle(){
		$is_catalog = $this->session->userdata('is_catalog_view');
		if($is_catalog == false){
			$this->session->set_userdata('is_catalog_view', true);
		} else {
			$this->session->set_userdata('is_catalog_view', false);
		}

		redirect("ListController");
	}

	public function edit_category_color($id){
		$data = [
			'dictionary_color' => $this->input->post('dictionary_color')
		];
		if($this->DictionaryModel->update_table($data, $id)){
			$this->session->set_flashdata('message_success', generate_message(true,'update','pin category color',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'update','pin category color',null));
		}

		redirect("ListController");
	}

	public function add_category(){
		$rules = $this->DictionaryModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', generate_message(false,'add','pin category','validation failed'));
			$this->session->set_flashdata('validation_error', validation_errors());
		} else {
			if($this->DictionaryModel->get_available_dct($this->input->post('dictionary_name'), 'pin_category')){
				$data = [
					'id' => get_UUID(), 
					'dictionary_type' => 'pin_category',
					'dictionary_name' => $this->input->post('dictionary_name'),
					'dictionary_color' => $this->input->post('dictionary_color'),
					'created_by' => $this->session->userdata('user_id'),
				];
	
				if($this->DictionaryModel->insert_table($data)){
					$this->session->set_flashdata('message_success', generate_message(true,'add','pin category',null));
				} else {
					$this->session->set_flashdata('message_error', generate_message(false,'add','pin category',null));
				}
			} else {
				$this->session->set_flashdata('message_error', generate_message(false,'add','pin category','name already exist'));
			}
		}

		redirect("ListController");
	}

	public function soft_del_pin(){
		$id = $this->input->post('id');
		$owner = $this->PinModel->get_owner_pin($id);

		if($this->MultiModel->soft_delete('pin',$id)){
			if($owner){
				$this->telegram->sendMessage([
					'chat_id' => $owner->telegram_user_id,
					'text' => "Hello <b>$owner->username</b>, your pin called $owner->pin_name <b>($owner->pin_category)</b> is deleted by admin",
					'parse_mode' => 'HTML'
				]);
			}

			$this->session->set_flashdata('message_success', generate_message(true,'delete','pin',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'delete','pin',null));
		}

		redirect("ListController");
	}

	public function delete_category(){
		$id = $this->input->post('id');
		$old_dct = $this->DictionaryModel->get_dct_by_id($id);

		if($this->MultiModel->delete('dictionary',$id)){
			$new_dct = $this->input->post('dictionary_migrate');
			
			if($new_dct && $new_dct != ""){
				if($this->DictionaryModel->update_mass_dictionary('pin','pin_category',$old_dct->dictionary_name,$new_dct)){
					$this->session->set_flashdata('message_success', generate_message(true,'delete','pin category','success to migrate'));
				} else {
					$this->session->set_flashdata('message_error', generate_message(true,'delete','pin category','failed to migrate'));
				}
			} else {
				$this->session->set_flashdata('message_success', generate_message(true,'delete','pin category','nothing to migrate'));
			}
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'delete','pin category',null));
		}

		redirect("ListController");
	}

	public function rename_category(){
		$id = $this->input->post('id');
		$dct_name_old = $this->input->post('dictionary_name_old');
		$dct_name_new = $this->input->post('dictionary_name_new');
		$data = [
			'dictionary_name' => $dct_name_new
		];

		if($this->DictionaryModel->update_table($data, $id)){			
			if($dct_name_old && $dct_name_old != ""){
				if($this->DictionaryModel->update_mass_dictionary('pin','pin_category',$dct_name_old,$dct_name_new)){
					$this->session->set_flashdata('message_success', generate_message(true,'rename','pin category','success to migrate'));
				} else {
					$this->session->set_flashdata('message_error', generate_message(true,'rename','pin category','failed to migrate'));
				}
			} else {
				$this->session->set_flashdata('message_success', generate_message(true,'rename','pin category','nothing to migrate'));
			}
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'rename','pin category',null));
		}

		redirect("ListController");
	}

	public function publish_to_global(){
		$list_id = $this->input->post('list_id');
		$category_name = $this->input->post('category_name');
		$success = 0;
		$failed = 0;

		$pin_attached = $this->PinModel->get_pin_by_category($category_name, null);
		foreach($pin_attached as $dt){
			if($this->GlobalListModel->insert_rel([
				'id' => get_UUID(),
				'pin_id' => $dt->id,
				'list_id' => $list_id,
				'created_at' => date("Y-m-d H:i:s"), 
				'created_by' => $this->session->userdata('user_id')
			])){
				$success++;
			} else {
				$failed++;
			}
		}

		if($success > 0 && $failed == 0){
			$this->session->set_flashdata('message_success', generate_message(true,'publish','all pin',"from category $category_name"));
		} else if($success > 0 && $failed > 0){
			$this->session->set_flashdata('message_success', generate_message(true,'publish',"$success pin and failed to publish $failed pin","from category $category_name"));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'publish','all pin',null));
		}

		redirect("/DetailGlobalController/view/$list_id");
	}

	public function view_catalog_detail($category){
		if($category != 'back'){
			$this->session->set_userdata('open_pin_list_category', $category);
		} else {
			$this->session->unset_userdata('open_pin_list_category');
		}

		redirect("ListController");
	}

	public function navigate($page){
		$this->session->set_userdata('page_pin', $page);

		redirect("ListController");
	}
}
