<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class DictionaryModel extends CI_Model {
        public function get_dictionary_by_type($type){
			$this->db->select('id, dictionary_name, dictionary_color');
			$this->db->from('dictionary');
			$condition = [
                'dictionary_type' => $type,
				'created_by' => null
            ];
			$this->db->where($condition);
			$this->db->order_by('dictionary_name','asc');

			return $data = $this->db->get()->result();
		}
	}
?>