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
		public function get_review_by_context($per_page, $offset, $target, $type, $user_id){
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
			$total_pages = ceil($total_rows / $per_page);
		
			// Pagination data
			$this->db->limit($per_page, $offset);
			$res = $this->db->get()->result();
			$start_item = $total_rows > 0 ? $offset + 1 : 0;
			$end_item = min($offset + $per_page, $total_rows);
		
			foreach ($res as $dt) {
				$dt->review_rate = (int)$dt->review_rate;
			}
		
			$data['data'] = $res;
			$data['total_page'] = $total_pages;
			$data['total_item'] = $total_rows;
			$data['start_item'] = $start_item;
			$data['end_item'] = $end_item;
		
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