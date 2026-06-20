<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Dompdf\Options;

class DetailPersonController extends CI_Controller {
	private $telegram;

	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
		$this->load->model('VisitModel');
		$this->load->model('PinModel');
		$this->load->model('ReviewModel');
		$this->load->model('MultiModel');

		$this->load->helper('generator_helper');

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function print_visit($person)
	{		
		$user_id = $this->session->userdata('user_id');
		$person = str_replace("%20"," ",$person);
		$dt_all_visit = $this->VisitModel->get_visit_location_by_person($person,false);
		$person = ucwords($person);

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
					<td style='text-align:center;'>$dt->pin_name</td>
					<td>$dt->pin_lat, $dt->pin_long</td>
					<td>$dt->pin_category</td>
					<td>$dt->visit_by</td>
					<td>$dt->visit_with</td>
					<td>".date("Y-m-d H:i", strtotime($dt->visit_at))."</td>
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
					<h4>Visit History</h4>
					<table>
						<thead>
							<tr>
								<th>Pin Name</th>
								<th>Coordinate</th>
								<th>Category</th>
								<th>Visit By</th>
								<th>Visit With</th>
								<th>Visit At</th>
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

			$pdfFilePath = $person."_visit_history_"."$user->username-$time.pdf";
			$dompdf->stream($pdfFilePath, array("Attachment" => 0));

			file_put_contents($pdfFilePath, $dompdf->output());
			$inputFile = InputFile::create('./'.$pdfFilePath, $pdfFilePath);
		
			$this->telegram->sendDocument([
				'chat_id' => $user->telegram_user_id,
				'document' => $inputFile,
				'caption' => "Hello <b>{$user->username}</b>, Here the Visit History of ".ucwords($person)." you have generated",
				'parse_mode' => 'HTML'
			]);
		
			unlink($pdfFilePath);

			// $this->HistoryModel->insert_history('Generate Document', 'Marker List');
			
			$this->session->set_flashdata('message_success', generate_message(true,'generate','document',null));
		} else {
			$this->session->set_flashdata('message_error', generate_message(false,'generate','document','no data to generated'));
		}
		redirect("DetailPersonController/view/$name");
	}
}
