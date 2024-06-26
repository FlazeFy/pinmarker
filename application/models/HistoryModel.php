<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class HistoryModel extends CI_Model {
        function __construct(){
            parent::__construct();
            $this->load->helper('generator_helper');
        }

        // Query
        public function get_my_activity(){
			$this->db->select('id, history_type, history_context, created_at');
			$this->db->from('history');
			$condition = [
				'created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
            $this->db->order_by('created_at','desc');

			return $data = $this->db->get()->result();
		}

        // Command
		public function insert_history($type, $ctx){
            $data = [
                'id' => get_UUID(), 
                'history_type' => $type,
                'history_context' => $ctx, 
                'created_at' => date("Y-m-d H:i:s"), 
			    'created_by' => $this->session->userdata('user_id'),
            ];

			$this->db->insert('history',$data);	
		}
	}
?>