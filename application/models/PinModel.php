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
	}
?>