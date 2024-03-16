<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	function __construct(){
		parent::__construct();
		$this->load->model('PinModel');
	}

	public function index()
	{
		$data = [];
		$data['active_page']= 'dashboard';
		$data['dt_count_my_pin']= $this->PinModel->count_my_pin();
		$data['dt_count_my_fav_pin']= $this->PinModel->count_my_fav_pin();
		$data['dt_get_most_category']= $this->PinModel->get_most_category();
		$data['dt_get_latest_pin']= $this->PinModel->get_latest_pin();
		$data['dt_get_last_visit']= $this->PinModel->get_last_visit();
		$data['dt_get_most_visit']= $this->PinModel->get_most_visit();

		$this->load->view('dashboard/index', $data);
	}
}
