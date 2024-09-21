<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class DictionaryModel extends CI_Model {
		private $table = "dictionary";
        const SESSION_KEY = 'user_id';
		private $role_key;

		public function __construct() {
			parent::__construct();
			$this->role_key = $this->session->userdata('role_key');
		}

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
					'rules' => 'min_length[2]|max_length[36]',
					'null' => TRUE
				],
			];
        }

        public function get_dictionary_by_type($type){
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$this->db->select('1');
			$this->db->from('pin');
			$this->db->where([
				'created_by' => $user_id
			]);
			$check = $this->db->get()->row();

			$select_query = "$this->table.id, dictionary_name, dictionary_color, $this->table.created_by";
			if($type == "pin_category" && ($check || $this->role_key == 0)){
				$select_query .= ", IFNULL(count(pin.id), 0) as total_pin";
			} else {
				$select_query .= ", 0 as total_pin";
			}

			$this->db->select($select_query);
			$this->db->from($this->table);

			if($type == "pin_category"){
				$this->db->join('pin',"pin.pin_category = $this->table.dictionary_name",'left');
			}

			if($this->role_key == 1){
				$condition1 = [
					'dictionary_type' => $type,
					"$this->table.created_by" => null
				];
				$condition2 = [
					'dictionary_type' => $type,
					"$this->table.created_by" => $user_id
				];

				if(!$check){
					$this->db->group_start();
					$this->db->where($condition1);
					$this->db->or_where($condition2);
					$this->db->group_end();
				} else {
					$this->db->where($condition1);
					if($type == "pin_category"){
						$this->db->or_where($condition2);
					}
				}
				
				if ($type == "pin_category" && $check) {
					$this->db->where('pin.created_by', $user_id);
				}
			} else {
				$condition = [
					'dictionary_type' => $type,
				];

				$this->db->where($condition);
			}
			
			if($type == "pin_category"){
				if($check){
					$this->db->order_by('total_pin','desc');
				}
				$this->db->group_by("$this->table.id");
			} else {
				$this->db->order_by('dictionary_name','asc');
			}

			$data = $this->db->get()->result();

			if(!$check && $this->role_key == 1){
				$dct = [];
				foreach($data as $dt){
					if($dt->created_by == null || $dt->created_by == $user_id){
						array_push($dct, $dt);
					}
				}
			} else {
				$dct = $data;
			}

			return $dct;
		}

		public function get_all_dct(){
			$this->db->select("$this->table.id, dictionary_type, dictionary_name, dictionary_color, IFNULL(count(pin.id), 0) as total_pin, IFNULL(count(visit.id), 0) as total_visit, username as created_by");
			$this->db->from($this->table);
			$this->db->join('pin',"pin.pin_category = $this->table.dictionary_name",'left');
			$this->db->join('visit',"visit.visit_by = $this->table.dictionary_name",'left');
			$this->db->join('user',"user.id = $this->table.created_by",'left');
			$this->db->order_by('dictionary_name','asc');
			$this->db->group_by("$this->table.id");

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

		public function get_dct_by_id($id){
			$this->db->select('*');
			$this->db->from($this->table);
			$condition = [
				'id' => $id
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->row();
		}

		public function get_owner_list($id){
			$this->db->select("dictionary_name, dictionary_color, username, telegram_user_id");
			$this->db->from($this->table);
			$this->db->join('user', "user.id = $this->table.created_by");
			$this->db->where("$this->table.id", $id);
			$this->db->where("user.telegram_is_valid", 1);
			$query = $this->db->get();

			return $query->row();
		}

		// Command
		public function update_table($data, $id){
			$this->db->where('id', $id);
			return $this->db->update($this->table,$data);	
		}

		public function update_mass_dictionary($table, $col, $old, $new){
			$this->db->where($col, $old);
			return $this->db->update($table,[ $col => $new]);	
		}

		public function insert_table($data){
			return $this->db->insert($this->table,$data);
		}
	}
?>