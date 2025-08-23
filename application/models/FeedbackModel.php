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

		// Query
		public function feedback_distribution(){
			$lowest_rate = 1;
			$max_rate = 5;

			$this->db->select("feedback_rate, COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->group_by("feedback_rate");
			$query = $this->db->get()->result_array();

			$resultMap = [];
			foreach ($query as $row) {
				$resultMap[$row['feedback_rate']] = (int) $row['total'];
			}

			$finalResult = [];
			for ($i = $lowest_rate; $i <= $max_rate; $i++) {
				$finalResult[] = (object) [
					'context' => $i,
					'total' => isset($resultMap[$i]) ? $resultMap[$i] : 0
				];
			}

			return $finalResult;
		}
	}
?>