<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class ReviewModel extends CI_Model {
		private $table = "review";
        const SESSION_KEY = 'user_id';
		private $role_key;
		private $month_name;

		public function __construct() {
			parent::__construct();
			$this->role_key = $this->session->userdata('role_key');
		}

		public function rules()
        {
            return [
				[
					'field' => 'review_person',
					'label' => 'Review Person',
					'rules' => 'max_length[36]',
				],
			];
        }
		
		// Command
		public function insert_review($data){
			return $this->db->insert($this->table,$data);	
		}
	}
?>