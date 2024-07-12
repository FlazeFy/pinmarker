<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class VisitModel extends CI_Model {
		// Query
        public function get_all_my_visit_header(){
			$user_id = $this->session->userdata('user_id');

			$this->db->select('visit.id, visit_desc, pin_name ,visit.created_at');
			$this->db->from('visit');
			$this->db->join('pin','visit.pin_id = pin.id', 'left');
			$condition = [
                'pin.deleted_at' => null,
				'pin.created_by' => $user_id
            ];
			$this->db->where($condition);
			$this->db->or_where('visit.created_by', $user_id);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_all_my_visit_detail(){
			$user_id = $this->session->userdata('user_id');

			$this->db->select('visit_desc, visit_by, visit_with, pin_name, pin_category, visit.created_at');
			$this->db->from('visit');
			$this->db->join('pin','visit.pin_id = pin.id', 'left');
			$condition = [
                'pin.deleted_at' => null,
				'pin.created_by' => $user_id
            ];
			$this->db->where($condition);
			$this->db->or_where('visit.created_by', $user_id);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_most_visit($ctx, $limit){
			$user_id = $this->session->userdata('user_id');

			$this->db->select("$ctx as context, COUNT(1) as total");
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id','left');
			$condition = [
                'deleted_at' => null,
				'pin.created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
			$this->db->or_where('visit.created_by', $user_id);
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

		public function get_visit_activity($year){
			$user_id = $this->session->userdata('user_id');
			$date_query = "DATE_FORMAT(created_at, '%Y-%m-%d')";
			
			$this->db->select("$date_query AS context, COUNT(1) AS total");
			$this->db->from('visit');
			$condition = [
                'YEAR(created_at)' => $year,
				'created_by' => $user_id
            ];
			$this->db->where($condition);
            $this->db->group_by("$date_query");
            $this->db->order_by('created_at','asc');

			return $data = $this->db->get()->result();
		}

		public function get_visit_activity_by_date($date){
			$user_id = $this->session->userdata('user_id');
			$date_query = "DATE_FORMAT(visit.created_at, '%Y-%m-%d') =";

			$this->db->select('pin_name, visit_desc, visit_by, visit_with, visit.created_at');
			$this->db->from('visit');
			$this->db->join('pin','visit.pin_id = pin.id', 'left');
			$condition = [
                'pin.deleted_at' => null,
				$date_query => $date,
				'pin.created_by' => $user_id
            ];
			$condition_external_visit = [
				$date_query => $date,
				'visit.created_by' => $user_id
            ];
			$this->db->where($condition);
			$this->db->where($condition_external_visit);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_total_visit_by_by_pin_id($id){
			$this->db->select('visit_by as context, COUNT(1) as total');
			$this->db->from('visit');
			$condition = [
				'created_by' => $this->session->userdata('user_id'),
				'pin_id' => $id
            ];
			$this->db->where($condition);
            $this->db->group_by('context');

			return $data = $this->db->get()->result();
		}

		public function get_total_visit_by_month() {
			$user_id = $this->session->userdata('user_id');

			$this->db->select("DATE_FORMAT(visit.created_at, '%M') as context, COUNT(1) as total");
			$this->db->from('visit');
			$this->db->join('pin', 'visit.pin_id = pin.id', 'left');
			$condition = [
				'pin.deleted_at' => null,
				'pin.created_by' => $user_id,
				'YEAR(visit.created_at)' => date('Y')
			];
			$condition_external_visit = [
				'visit.created_by' => $user_id,
				'YEAR(visit.created_at)' => date('Y')
			];
			$this->db->where($condition);
			$this->db->where($condition_external_visit);
			$this->db->group_by("context");
			$this->db->order_by("context", 'desc');
			$data = $this->db->get()->result();

			$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		
			$result = array_fill_keys($months, 0);
		
			foreach ($data as $row) {
				$result[$row->context] = $row->total;
			}
		
			$res = [];
			foreach ($result as $month => $total) {
				$res[] = (object) ['context' => $month, 'total' => $total];
			}			
		
			return $res;
		}

		// Command
		public function insert_visit($data){
			$this->db->insert('visit',$data);	
		}
	}
?>