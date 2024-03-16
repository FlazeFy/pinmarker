<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class PinModel extends CI_Model {
		public function get_all_my_pin(){
			$this->db->select('id, pin_name, pin_desc, pin_lat, pin_long, pin_category, pin_person, is_favorite, created_at');
			$this->db->from('pin');
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->result();
		}

        public function count_my_pin(){
			$this->db->select('count(1) as total');
			$this->db->from('pin');
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->row();
		}

        public function count_my_fav_pin(){
			$this->db->select('count(1) as total');
			$this->db->from('pin');
			$condition = [
                'deleted_at' => null,
                'is_favorite' => 1
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->row();
		}

        public function get_most_category(){
			$this->db->select('pin_category as context, COUNT(1) as total');
			$this->db->from('pin');
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);
            $this->db->order_by('total','desc');
            $this->db->group_by('pin_category');
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}

        public function get_latest_pin(){
			$this->db->select('pin_name');
			$this->db->from('pin');
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);
            $this->db->order_by('created_at','desc');
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}

        public function get_most_visit(){
			$this->db->select('pin_name as context, COUNT(1) as total');
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id');
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);
            $this->db->group_by('pin_name');
            $this->db->order_by('total','desc');
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}

        public function get_last_visit(){
			$this->db->select('pin_name, visit_desc');
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id');
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);
            $this->db->order_by('visit.created_at','desc');
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}
	}
?>