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
					'rules' => 'required|min_length[2]|max_length[36]',
				],
			];
        }

        public function get_dictionary_by_type($type){
			$select_query = "$this->table.id, dictionary_name, dictionary_color";
			if($type == "pin_category"){
				$select_query .= ", IFNULL(count(pin.id), 0) as total_pin";
			}

			$this->db->select($select_query);
			$this->db->from($this->table);

			if($type == "pin_category"){
				$this->db->join('pin',"pin.pin_category = $this->table.dictionary_name",'left');
			}
			$condition = [
                'dictionary_type' => $type,
				"$this->table.created_by" => null
            ];
			$condition2 = [
                'dictionary_type' => $type,
				"$this->table.created_by" => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->or_where($condition2);

			if($type == "pin_category"){
				$this->db->order_by('total_pin','desc');
				$this->db->group_by("$this->table.id");
			} else {
				$this->db->order_by('dictionary_name','asc');
			}

			return $data = $this->db->get()->result();
		}

		public function get_my_pin_category(){
			$this->db->select("$this->table.id, dictionary_name, dictionary_color, IFNULL(count(pin.id), 0) as total_pin");
			$this->db->from($this->table);
			$this->db->join('pin',"pin.pin_category = $this->table.dictionary_name",'left');
			$condition = [
                'dictionary_type' => 'pin_category',
				"$this->table.created_by" => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->order_by('dictionary_name','asc');
			$this->db->group_by("$this->table.id");

			return $data = $this->db->get()->result();
		}

		public function get_available_dct($name, $type){
			$this->db->select("1");
			$this->db->from($this->table);

			if($type == "pin_category"){
				$condition = [
					'dictionary_type' => $type,
					'dictionary_name' => $name,
					"$this->table.created_by" => $this->session->userdata(self::SESSION_KEY)
				];
			} else {
				$condition = [
					'dictionary_type' => $type,
					'dictionary_name' => $name,
				];
			}
			
			$this->db->where($condition);
			$data = $this->db->get()->result();

			if(count($data) > 0){
				return false;
			} else {
				return true;
			}
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