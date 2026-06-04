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
		public function get_review_by_context($limit, $start, $target, $type, $user_id){
			$ext = "";
			if($type == "person"){
				$ext = ", pin_name, pin.id as pin_id, pin_category";
			}
			$this->db->select("review_person, review_rate, review_body, review.created_at$ext");
			$this->db->from($this->table);
			$this->db->join('user', 'user.id = review.created_by');
			$this->db->join('visit', 'visit.id = review.visit_id');
		
			if($type == 'pin' || $type == 'person'){
				$this->db->join('pin', 'pin.id = visit.pin_id');
		
				if($type == 'pin'){
					$this->db->where("pin.id", $target);
				} else {
					$target = str_replace("%20", " ", $target);
					$this->db->where("review_person", $target);
				}
			} else if($type == 'visit'){
				$this->db->where("visit.id", $target);
			}
		
			$this->db->where("review.created_by", $user_id);
			$this->db->order_by('review.created_at', 'DESC');
		
			// Pagination count
			$db_count = clone $this->db;
			$total_rows = $db_count->get()->num_rows();
			$total_pages = ceil($total_rows / $limit);
		
			// Pagination data
			$this->db->limit($limit, $start);
			$res = $this->db->get()->result();

			// Integer safety
			foreach ($res as $dt) {
				$dt->review_rate = (int)$dt->review_rate;
			}

			$data['data'] = $res;
			$data['total_page'] = $total_pages;
		
			return $data;
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