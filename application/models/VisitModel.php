<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class VisitModel extends CI_Model {
		// Query
        public function get_all_my_visit_header(){
			$this->db->select('visit.id, visit_desc, pin_name ,visit.created_at');
			$this->db->from('visit');
			$this->db->join('pin','visit.pin_id = pin.id');
			$condition = [
                'pin.deleted_at' => null
            ];
			$this->db->where($condition);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}
	}
?>