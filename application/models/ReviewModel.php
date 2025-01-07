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

		// Query
		public function get_review_by_pin($id){
			$this->db->select("review_person, review_rate, review.created_at");
			$this->db->from($this->table);
			$this->db->join('user', 'user.id = review.created_by');
			$this->db->join('visit', 'visit.id = review.visit_id');
			$this->db->join('pin', 'pin.id = visit.pin_id');
			$this->db->where("pin.id", $id);
			$this->db->order_by('review.created_at','DESC');
			$query = $this->db->get();

			return $query->result();
		}

		public function get_review_by_visit($id){
			$this->db->select("review_person, review_rate, review.created_at");
			$this->db->from($this->table);
			$this->db->join('user', 'user.id = review.created_by');
			$this->db->join('visit', 'visit.id = review.visit_id');
			$this->db->where("visit.id", $id);
			$this->db->order_by('review.created_at','DESC');
			$query = $this->db->get();

			return $query->result();
		}
		
		// Command
		public function insert_review($data){
			return $this->db->insert($this->table,$data);	
		}

		public function delete_review_by_visit($id){
			return $this->db->delete($this->table,['visit_id'=>$id]);	
		}
	}
?>