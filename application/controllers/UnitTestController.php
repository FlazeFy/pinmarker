<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTestController extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('AuthModel');
        $this->load->model('PinModel');
        $this->load->model('VisitModel');

        $this->load->library('unit_test');
	}

    public function index() {
        echo "<h1 style='text-align:center;'>Unit Testing Test Case</h1>";
        echo "<h2 style='color:green;'>Positive Case</h2>";
        $this->test_tc_001();
        $this->test_tc_002();
        $this->test_tc_003();
        $this->test_tc_004();

        echo $this->unit->report();
    }

    public function test_tc_001(){
        $test_name = '<b>TC-001 : Login with username</b>';
        $data = [
            'username' => 'flazefy',
            'password' => 'admin'
        ];

        $test = $this->AuthModel->login($data['username'],$data['password']);
        
        $this->unit->run($test, 'is_true', $test_name);
    }

    public function test_tc_002(){
        $test_name = '<b>TC-002 : Login with email</b>';
        $data = [
            'username' => 'flazen.edu@gmail.com',
            'password' => 'admin'
        ];

        $test = $this->AuthModel->login($data['username'],$data['password']);
        
        $this->unit->run($test, 'is_true', $test_name);
    }

    public function test_tc_003(){
        $test_name = '<b>TC-003 : Get dashboard</b>';

        $test_count_my_pin = $this->PinModel->count_my_pin();
        $test_count_my_fav_pin = $this->PinModel->count_my_fav_pin();
        $test_get_most_category_one = $this->PinModel->get_most_category(1);
        $test_get_latest_pin = $this->PinModel->get_latest_pin();
        $test_get_most_category_six = $this->PinModel->get_most_category(6);
        $test_get_last_visit = $this->VisitModel->get_last_visit();
        
        $this->unit->run($test_count_my_pin, 'is_object', $test_name);
        $this->unit->run($test_count_my_fav_pin, 'is_object', $test_name);
        $this->unit->run($test_get_most_category_one, 'is_object', $test_name);
        $this->unit->run($test_get_latest_pin, 'is_object', $test_name);
        $this->unit->run($test_get_most_category_six, 'is_array', $test_name);
        $this->unit->run($test_get_last_visit, 'is_object', $test_name);
    }

    public function test_tc_004(){
        $test_name = '<b>TC-004 : Get statistic</b>';

        $test_get_most_visit_pin_name = $this->VisitModel->get_most_visit('pin_name',1);
        $test_get_most_visit_pin_category = $this->VisitModel->get_most_visit('pin_category',6);
        
        $this->unit->run($test_get_most_visit_pin_name, 'is_object', $test_name);
        $this->unit->run($test_get_most_visit_pin_category, 'is_array', $test_name);
    }
}
