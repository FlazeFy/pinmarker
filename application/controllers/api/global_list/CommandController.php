<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'controllers/api/BaseApiController.php');

use GuzzleHttp\Client;

class CommandController extends BaseApiController {    
    protected $httpClient;

    function __construct(){
        parent::__construct();
        $this->load->model("PinModel");
        $this->load->model("GlobalListModel");
        $this->load->model("HistoryModel");
        $this->load->model("AuthModel");

        $this->load->helper('validator_helper');
        $this->load->helper('generator_helper');
        $this->load->library('form_validation');
        $this->load->library('TelegramHelper'); 
    }

    public function put_update_global_list_by_id($id) {
        // Auth guard
        $this->authenticate();
        $user_id = $this->auth_user_id;

        // Validate path param
        if (!check_uuid($id)) return api_response(400, 'failed', 'id must be valid uuid', null);

        // Validate list
        $rules = $this->GlobalListModel->rules(null);
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == FALSE){
            return api_response(400, 'failed', generate_message(false,'edit','list','validation failed'), validation_errors());
        } else {
            $list_name = cleanTrimNull($this->input->post('list_name'));

            // Model : Get list by id
            $listOld = $this->GlobalListModel->get_detail_list_by_id($id, $user_id);
            if (!$listOld) return api_response(200, 'success', 'No list found', null);

            if ($listOld->list_name !== $list_name) {
                // Model : Check name avaiability
                $used = $this->GlobalListModel->is_name_available($user_id, $list_name, $id);
                if (!$used) return api_response(409, 'failed', 'List name already used', null);
            }

            $data = [
                'list_name' => $list_name, 
                'list_desc' => cleanTrimNull($this->input->post('list_desc'))
            ];

            // Model : Update list by id
            $list = $this->GlobalListModel->update_list($id, $data, $user_id);
            if ($list) {
                // Model : Add updated global list history
                $this->HistoryModel->insert_history('Update list', $list_name, $user_id);			

                // Return API response
                return api_response(200, 'success', "List updated", $data);
            } else {
                return api_response(500, 'failed', 'Something went wrong', null);
            }
        } 
    }
}