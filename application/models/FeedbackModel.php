<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class FeedbackModel extends CI_Model {
        // Command
		public function insert_feedback($data){
			return $this->db->insert('feedback',$data);	
		}
	}
?>