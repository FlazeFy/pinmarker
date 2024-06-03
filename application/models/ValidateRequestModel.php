<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class ValidateRequestModel extends CI_Model {
		// Command
		public function insert_request($data){
			return $this->db->insert('validate_request',$data);	
		}
	}
?>