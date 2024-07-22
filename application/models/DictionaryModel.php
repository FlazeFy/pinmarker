<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class DictionaryModel extends CI_Model {
		private $table = "dictionary";
        const SESSION_KEY = 'user_id';

		public function rules()
        {
            return [
				[
					'field' => 'dictionary_name',
					'label' => 'Dictionary Name',
					'rules' => 'required|min_length[2]|max_length[36]'
				],
				[
					'field' => 'dictionary_color',
					'label' => 'Dictionary Color',
					'rules' => 'required|max_length[36]',
				],
			];
        }

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

		public function get_my_pin_category(){
			$this->db->select('id, dictionary_name, dictionary_color, count(1) as total_pin');
			$this->db->from($this->table);
			$condition = [
                'dictionary_type' => 'pin_category',
				'created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->order_by('dictionary_name','asc');

			return $data = $this->db->get()->result();
		}

		// Command
		public function update_table($data, $id){
			$this->db->where('id', $id);
			return $this->db->update($this->table,$data);	
		}

		public function insert_table($data){
			return $this->db->insert($this->table,$data);
		}
	}
?>