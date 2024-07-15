<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class HistoryModel extends CI_Model {
        function __construct(){
            parent::__construct();
            $this->load->helper('generator_helper');
        }

        // Query
        public function get_my_activity($limit,$start){
			$this->db->select('id, history_type, history_context, created_at');
			$this->db->from('history');
			$condition = [
				'created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
            $this->db->order_by('created_at','desc');

            $db_count = clone $this->db;
            $total_rows = $db_count->get()->num_rows();
            $total_pages = ceil($total_rows / $limit);

            $this->db->limit($limit, $start);
            $data['data'] = $this->db->get()->result();
            $data['total_page'] = $total_pages;
            return $data;
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