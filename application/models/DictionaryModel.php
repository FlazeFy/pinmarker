<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class DictionaryModel extends CI_Model {
		private $table = "dictionary";
        const SESSION_KEY = 'user_id';

        public function get_dictionary_by_type($type){
			$this->db->select('id, dictionary_name, dictionary_color');
			$this->db->from($this->table);
			$condition = [
                'dictionary_type' => $type,
				'created_by' => null
            ];
			$condition2 = [
                'dictionary_type' => $type,
				'created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->or_where($condition2);
			$this->db->order_by('dictionary_name','asc');

			return $data = $this->db->get()->result();
		}
	}
?>