<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class VisitModel extends CI_Model {
		// Query
        public function get_all_my_visit_header(){
			$this->db->select('visit.id, visit_desc, pin_name ,visit.created_at');
			$this->db->from('visit');
			$this->db->join('pin','visit.pin_id = pin.id');
			$condition = [
                'pin.deleted_at' => null,
				'pin.created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_most_visit($ctx, $limit){
			$this->db->select("$ctx as context, COUNT(1) as total");
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id');
			$condition = [
                'deleted_at' => null,
				'pin.created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
            $this->db->group_by($ctx);
            $this->db->order_by('total','desc');
            $this->db->limit($limit);

			if($limit == 1){
				return $data = $this->db->get()->row();
			} else if($limit > 1) {
				return $data = $this->db->get()->result();
			}
		}

		public function get_visit_history_by_pin_id($id){
			$this->db->select('visit_by, visit_desc, visit.created_at, visit_with');
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id');
			$condition = [
				'visit.pin_id' => $id,
                'deleted_at' => null,
				'visit.created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->result();
		}

        public function get_last_visit(){
			$this->db->select('pin_name, visit_desc');
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id');
			$condition = [
                'deleted_at' => null,
				'pin.created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
            $this->db->order_by('visit.created_at','desc');
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}

		// Command
		public function insert_visit($data){
			$this->db->insert('visit',$data);	
		}
	}
?>