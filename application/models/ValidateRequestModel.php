<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class ValidateRequestModel extends CI_Model {
		// Query
		public function get_my_active_request($type, $user_id){
			$this->db->select('id, request_context, created_at');
			$this->db->from('validate_request');
			$condition = [
                'request_type' => $type,
				'created_by' => $user_id
            ];
			$this->db->where($condition);
            $query = $this->db->get();
    
            return $query->row();
		}

		// Command
		public function insert_request($data){
			return $this->db->insert('validate_request',$data);	
		}

		public function delete_request($id){
			return $this->db->delete('validate_request',['id'=>$id]);	
		}
	}
?>