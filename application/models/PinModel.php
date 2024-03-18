<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class PinModel extends CI_Model {
		// Query
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

			$search_pin_name = $this->session->userdata('search_pin_name_key');
			if($search_pin_name != null && $search_pin_name != ""){
				$this->db->like('pin_name', $search_pin_name, 'both');
			}

            if($from == 'list'){
                $this->db->group_by('id');
				$this->db->order_by('is_favorite','DESC');
            }
			$this->db->order_by('created_at','DESC');

			return $data = $this->db->get()->result();
		}

		public function get_all_my_pin_name(){
			$this->db->select('id, pin_name');
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

        public function get_most_category($limit){
			$this->db->select('pin_category as context, COUNT(1) as total');
			$this->db->from('pin');
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);
            $this->db->order_by('total','desc');
            $this->db->group_by('pin_category');
            $this->db->limit($limit);

			if($limit == 1){
				return $data = $this->db->get()->row();
			} else if($limit > 1) {
				return $data = $this->db->get()->result();
			}
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

		// Command
		public function insert_marker($data){
			$this->db->insert('pin',$data);	
		}
	}
?>