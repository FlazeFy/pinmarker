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
		
		$this->load->helper('generator_helper');
		$this->load->library('form_validation');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$per_page = 10;
			$offset = 0;

			$data = [];
			$data['active_page']= 'list';

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
				$user_id = $this->session->userdata('user_id');
				$data['dt_my_pin']= $this->PinModel->get_pin_list_by_category($user_id);
			}
			$data['dt_my_category'] = $this->DictionaryModel->get_my_pin_category();
			$data['is_mobile_device'] = is_mobile_device();
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
			$options->set('defaultFont', 'Courier');
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
					<title>$user->username's Visit</title>
					<style>
						th, td{
							border: 1px solid black;
						}
						thead {
							font-size:15px;
						}
						tbody {
							font-size:12px;
						}
						tbody td {
							padding: 3px;
						}
						table {
							border-collapse: collapse;
							width:100%;
						}
						h6 {
							font-size:12.5px;
							margin:0;
						}
					</style>
				</head>
				<body>
					<h1>PinMarker</h1>
					<hr>
					<h2>Pin List</h2>
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
					<hr>
					<div style='font-size: 12px; font-style:italic;'>
						<p style='float:left;'>PinMarker parts of FlazenApps</p>
						<p style='float:right;'>Generated at $datetime by $user->username</p>
					</div>
				</body>
			</html>";

			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'landscape');
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
			
			$this->session->set_flashdata('message_success', 'Document generated!');
		} else {
			$this->session->set_flashdata('message_error', 'No data to generated!');
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
			$this->session->set_flashdata('message_success', 'Category pin color updated');
		} else {
			$this->session->set_flashdata('message_error', 'Failed to update pin color');
		}

		redirect("ListController");
	}

	public function add_category(){
		$rules = $this->DictionaryModel->rules();
		$this->form_validation->set_rules($rules);

		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message_error', 'Failed to create category. Validation failed');
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
					$this->session->set_flashdata('message_success', 'Category created');
				} else {
					$this->session->set_flashdata('message_error', 'Failed to create category');
				}
			} else {
				$this->session->set_flashdata('message_error', 'Failed to create category. Name already used');
			}
		}

		redirect("ListController");
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
