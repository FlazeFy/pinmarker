<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class PinModel extends CI_Model {
		public function get_all_my_pin($from){
            $extra = "";
            if($from == 'list'){
                $extra = ", pin_call, pin_email, pin_address, COUNT(1) as total_visit";
            }
			$this->db->select("pin.id, pin_name, pin_desc, pin_lat, pin_long, pin_category, pin_person, is_favorite, pin.created_at, dictionary_color as pin_color, $extra");
			$this->db->join('dictionary','dictionary.dictionary_name = pin.pin_category');

            if($from == 'list'){
                $this->db->join('visit','visit.pin_id = pin.id','left');
            }

			$this->db->from('pin');
			$condition = [
                'pin.deleted_at' => null
            ];
			$this->db->where($condition);

            if($from == 'list'){
                $this->db->group_by('id');
            }

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

		public function insert_marker($data){
			$this->db->insert('pin',$data);	
		}
	}
?>