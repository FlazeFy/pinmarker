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

		$this->load->model('TokenModel');
		$telegram_token = $this->TokenModel->get_token('TELEGRAM_TOKEN');
		$this->telegram = new Api($telegram_token);
	}

	public function index()
	{
		if($this->AuthModel->current_user()){
			$data = [];
			$data['active_page']= 'list';
			$data['dt_my_pin']= $this->PinModel->get_all_my_pin('list');
			$this->load->view('list/index', $data);
		} else {
			redirect('LoginController');
		}
	}

	public function print_pin()
	{
		require 'vendor/autoload.php';
		
		$user_id = $this->session->userdata('user_id');
		$user = $this->AuthModel->get_user_by_id($user_id);
		$dt_all_pin = $this->PinModel->get_all_my_pin('list');
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

		redirect('ListController');
	}
}
