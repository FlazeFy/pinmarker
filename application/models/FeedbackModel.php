<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class FeedbackModel extends CI_Model {
		private $table = "feedback";
        const SESSION_KEY = 'user_id';

		public function rules($ext)
        {
            return [
				[
					'field' => 'feedback_rate',
					'label' => 'Feedback Rate',
					'rules' => 'required|max_length[2]'
				],
				[
					'field' => 'feedback_body',
					'label' => 'Feedback Body',
					'rules' => 'required|max_length[255]',
				],
			];
        }

        // Command
		public function insert_feedback($data){
			return $this->db->insert($this->table,$data);	
		}
	}
?>