<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LandingController extends CI_Controller {
	function __construct(){
		parent::__construct();
	}

    public function index()
	{
		$data = [];
		$data['is_mobile_device'] = is_mobile_device();

		$data['title_page'] = 'PinMarker | Marks Your World';
		$data['content'] = $this->load->view('landing/index',$data,true);
		$this->load->view('others/layout', $data);
	}
}
