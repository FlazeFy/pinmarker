<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Dompdf\Options;

class HistoryController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('VisitModel');
		$this->load->model('AuthModel');
		$this->load->model('HistoryModel');
		$this->load->model('TokenModel');

		$this->load->library('form_validation');
		$this->load->helper('generator_helper');

		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
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

			if($this->session->userdata('page_activity')){
				$offset = $this->session->userdata('page_activity') * $per_page;
			}

			$data['active_page']= 'history';
			$data['is_signed'] = true;
			$data['dt_all_my_visit_header']= $this->VisitModel->get_all_my_visit_header();
			$data['dt_my_activity']= $this->HistoryModel->get_my_activity($per_page, $offset);

			$data['title_page'] = 'PinMarker | History';
			$data['content'] = $this->load->view('history/index',$data,true);
			$this->load->view('others/layout', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function print_visit()
	{		
		$user_id = $this->session->userdata('user_id');
		$dt_all_visit = $this->VisitModel->get_all_my_visit_detail();

		if($dt_all_visit){
			require 'vendor/autoload.php';

			$user = $this->AuthModel->get_user_by_id($user_id);
			$time = time();
			$datetime = date("Y-m-d H:i:s");
			$options = new Options();
			$options->set('defaultFont', 'Helvetica');
			$dompdf = new Dompdf($options);
			$body = "";

			foreach($dt_all_visit as $dt){
				$body .= "
				<tr>
					<td style='text-align:center;'>"; 
						if($dt->pin_name){ 
							$body .= $dt->pin_name; 
						} else {
							$body .= "-";
						} 
					$body .= "</td>
					<td style='text-align:center;'>"; 
						if($dt->pin_category){
							$body .= $dt->pin_category;
						} else {
							$body .= "<span style='font-style:italic;'>Custom Location</span>";
						} 
					$body .="</td>
					<td>".($dt->visit_desc ?? '-')."</td>
					<td>
						<h6>Visit With : </h6>
						".($dt->visit_with ?? '-')."
						<h6>Visit By : </h6>
						".($dt->visit_by ?? '-')."
					</td>
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
					<h4>Visit List</h4>
					<table>
						<thead>
							<tr>
								<th>Pin Name</th>
								<th>Pin Category</th>
								<th>Description</th>
								<th>Info</th>
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

			$pdfFilePath = "my_visit_$user->username-$time.pdf";
			$dompdf->stream($pdfFilePath, array("Attachment" => 0));

			file_put_contents($pdfFilePath, $dompdf->output());
			$inputFile = InputFile::create('./'.$pdfFilePath, $pdfFilePath);
		
			$this->telegram->sendDocument([
				'chat_id' => $user->telegram_user_id,
				'document' => $inputFile,
				'caption' => "Hello <b>{$user->username}</b>, Here the Visit List you have generated",
				'parse_mode' => 'HTML'
			]);
		
			unlink($pdfFilePath);

			$this->session->set_flashdata('message_success', generate_message(true,'generate','document',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'generate','document','no data to generated'));
		}
		redirect('HistoryController');
	}

	public function navigate($page){
		$this->session->set_userdata('page_activity', $page);

		redirect("HistoryController");
	}
}
