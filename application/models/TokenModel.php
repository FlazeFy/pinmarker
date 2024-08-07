<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class TokenModel extends CI_Model {
		private $table = "token";

		// Query
		public function get_token($key){
			$this->db->select('token_value');
			$this->db->from($this->table);
			$condition = [
                'token_key' => $key
            ];
			$this->db->where($condition);
            $query = $this->db->get();
    
            return $query->row()->token_value;
		}
	}
?>