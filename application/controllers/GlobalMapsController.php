<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GlobalMapsController extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('AuthModel');
		$this->load->model('GlobalListModel');
	}

	public function index()
	{
		$data['dt_active_search'] = null;
		$data['is_mobile_device'] = is_mobile_device();

		$this->load->view('global_maps/index',$data);
	}

	public function view($search)
	{
		$data = [];
		$search = str_replace('%20', ' ', $search);

		$data['dt_global'] = $this->GlobalListModel->get_global_list($search);
		$data['dt_active_search'] = $search;
		$data['is_mobile_device'] = is_mobile_device();

		$this->load->view('global_maps/index',$data);
	}
}
