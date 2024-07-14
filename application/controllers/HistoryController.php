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

		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'history';
			$data['dt_all_my_visit_header']= $this->VisitModel->get_all_my_visit_header();
			$data['dt_my_activity']= $this->HistoryModel->get_my_activity();
			$this->load->view('history/index', $data);
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
			$options->set('defaultFont', 'Courier');
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
					<title>$user->username's Marker</title>
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
					<h2>Visit List</h2>
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

			// $this->HistoryModel->insert_history('Generate Document', 'Visit List');

			$this->session->set_flashdata('message_generated_success', 'Document generated!');
		} else {
			$this->session->set_flashdata('message_generated_error', 'No data to generated!');
		}
		redirect('HistoryController');
	}
}
