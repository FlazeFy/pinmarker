<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class PinModel extends CI_Model {
		private $table = "pin";
        const SESSION_KEY = 'user_id';
		private $role_key;

		public function __construct() {
			parent::__construct();
			$this->role_key = $this->session->userdata('role_key');
		}

		public function rules($ext)
        {
            return [
				[
					'field' => 'pin_name',
					'label' => 'Pin Name',
					'rules' => 'required|max_length[75]|min_length[2]'
				],
				[
					'field' => 'pin_desc',
					'label' => 'Pin Description',
					'rules' => 'max_length[500]',
					'null' => TRUE
				],
				[
					'field' => 'pin_lat',
					'label' => 'Pin Latitude',
					'rules' => 'required|decimal'
				],
				[
					'field' => 'pin_long',
					'label' => 'Pin Longitude',
					'rules' => 'required|decimal'
				],
				[
					'field' => 'pin_category',
					'label' => 'Pin Category',
					'rules' => 'required|max_length[36]'
				],
				[
					'field' => 'pin_person',
					'label' => 'Pin Person',
					'rules' => 'max_length[75]',
					'null' => TRUE
				],
				[
					'field' => 'pin_call',
					'label' => 'Pin Call',
					'rules' => 'max_length[16]',
					'null' => TRUE
				],
				[
					'field' => 'pin_email',
					'label' => 'Pin Email',
					'rules' => 'valid_email|max_length[255]',
					'null' => TRUE
				],
				[
					'field' => 'pin_address',
					'label' => 'Pin Address',
					'rules' => 'max_length[500]',
					'null' => TRUE
				],
			];
        }

		// Query
		public function get_all_my_pin($from,$category,$limit,$start){
            $extra = "";
            if($from == 'list'){
                $extra = ", pin_call, pin_email, pin_address, IFNULL(COUNT(visit.id), 0) as total_visit, MAX(visit.created_at) as last_visit";
            }
			if($this->role_key == 0){
				$extra .= ", username as created_by, pin.updated_at, deleted_at";
			}
			$this->db->select("pin.id, pin_name, pin_desc, pin_lat, pin_long, pin_category, pin_person, is_favorite, pin.created_at, dictionary_color as pin_color, $extra");
			$this->db->join('dictionary','dictionary.dictionary_name = pin.pin_category');

            if($from == 'list'){
                $this->db->join('visit','visit.pin_id = pin.id','left');
            }
			if($this->role_key == 0){
                $this->db->join('user','pin.created_by = user.id','left');
			}

			$this->db->from($this->table);

			if($this->role_key == 1){
				$condition['pin.deleted_at'] = null;
				if($this->role_key == 1){
					$condition['pin.created_by'] = $this->session->userdata(self::SESSION_KEY);
				} 
				
				$this->db->where($condition);
			}

			$search_pin_name = $this->session->userdata('search_pin_name_key');
			if($search_pin_name != null && $search_pin_name != ""){
				$this->db->like('pin_name', $search_pin_name, 'both');
			}

            if($from == 'list'){
                $this->db->group_by('id');
				$this->db->order_by('is_favorite','DESC');
            }
			$this->db->order_by('created_at','DESC');

			if($category){
				$category = str_replace("%20", " ", $category);
				$this->db->where('pin_category',$category);
			}

			if($from == 'maps'){
				$category_filter = $this->session->userdata('filter_pin_by_cat');
				if($category_filter){
					if($category_filter != "favorite"){
						$this->db->where('pin_category',$category_filter);
					} else {
						$this->db->where('is_favorite',1);
					}
				} 
			}

			if($limit > 0 && $start >= 0){
				$db_count = clone $this->db;
				$total_rows = $db_count->get()->num_rows();
				$total_pages = ceil($total_rows / $limit);

				$this->db->limit($limit, $start);
				$data['data'] = $this->db->get()->result();
				$data['total_page'] = $total_pages;
				return $data;
			} else {
				return $data = $this->db->get()->result();
			}
		}

		public function get_total_all(){
			$this->db->select('COUNT(1) as total');
			$this->db->from($this->table);
			$condition = [
                'deleted_at' => null
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->result();
		}

		public function get_all_my_pin_name(){
			$this->db->select('id, pin_name, pin_lat, pin_long');
			$this->db->from($this->table);
			$condition = [
                'deleted_at' => null,
				'created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->order_by('is_favorite','DESC');
			$this->db->order_by('pin_name','ASC');

			return $data = $this->db->get()->result();
		}

		public function get_pin_name_by_id($id){
			$this->db->select('pin_name');
			$this->db->from($this->table);
			$condition = [
                'id' => $id,
            ];
			$this->db->where($condition);
			$query = $this->db->get();
    
            return $query->row()->pin_name;
		}

		public function get_pin_list_by_category($user_id){
			$this->db->select("IFNULL(GROUP_CONCAT(COALESCE(pin.pin_name, null) ORDER BY pin.pin_name ASC SEPARATOR ', '), '') as pin_list, 
				dictionary.dictionary_name, IFNULL(COUNT(pin.pin_name), 0) as total");
			$this->db->from('dictionary');
			$this->db->join('pin', 'pin.pin_category = dictionary.dictionary_name AND pin.created_by = "'.$user_id.'" AND pin.deleted_at IS NULL', 'left');
			$condition = [
				'dictionary.dictionary_type' => 'pin_category', 
                'pin.created_by' => $user_id,
            ];
			$condition_2 = [
				'dictionary.dictionary_type' => 'pin_category', 
                'pin.created_by' => null
            ];
			$this->db->group_start();
			$this->db->where($condition);
			$this->db->or_where($condition_2);
			$this->db->group_end();

			if($this->role_key == 1){
				$condition3 = [
					'dictionary.dictionary_type' => 'pin_category', 
					'dictionary.created_by' => null
				];
				$condition4 = [
					'dictionary.dictionary_type' => 'pin_category', 
					'dictionary.created_by' => $this->session->userdata(self::SESSION_KEY)
				];
				$this->db->where($condition3);
				$this->db->or_where($condition4);
			}

			$this->db->group_by('dictionary.dictionary_name');
			$this->db->order_by('dictionary.dictionary_name', 'ASC');
			$query = $this->db->get();

			return $query->result();
		}

		public function get_pin_by_id($id){
			$this->db->select('pin.id, pin_name, pin_desc, pin_lat, pin_long, pin_category, pin_person, pin_call, pin_email, pin_address, is_favorite, pin.created_at, pin.created_by, pin.updated_at, pin.deleted_at, dictionary_color as pin_color');
			$this->db->from($this->table);
			$this->db->join('dictionary','dictionary.dictionary_name = pin.pin_category');
			$condition = [
				'pin.id' => $id,
                'deleted_at' => null,
				'pin.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->row();
		}

		public function get_pin_by_category($cat, $id){
			$this->db->select('id, pin_name, pin_desc, pin_lat, pin_long, created_at');
			$this->db->from($this->table);

			$condition = [
				'pin_category' => $cat,
                'deleted_at' => null,
				'pin.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];

			if($id != null){
				$condition['id !='] = $id;
			}
			
			$this->db->where($condition);

			return $data = $this->db->get()->result();
		}

        public function count_my_pin(){
			$this->db->select('count(1) as total');
			$this->db->from($this->table);

			$condition['deleted_at'] = null; 
			if($this->role_key == 1){
				$condition['created_by'] = $this->session->userdata(self::SESSION_KEY); 
			}
			$this->db->where($condition);

			return $data = $this->db->get()->row();
		}

        public function count_my_fav_pin(){
			$this->db->select('count(1) as total');
			$this->db->from($this->table);
			$condition = [
                'deleted_at' => null,
                'is_favorite' => 1,
            ];
			if($this->role_key == 1){
				$condition['created_by'] = $this->session->userdata(self::SESSION_KEY); 
			}
			$this->db->where($condition);

			return $data = $this->db->get()->row();
		}

        public function get_most_category($limit){
			$this->db->select('pin_category as context, COUNT(1) as total');
			$this->db->from($this->table);
			$condition['deleted_at'] = null; 
			if($this->role_key == 1){
				$condition['created_by'] = $this->session->userdata(self::SESSION_KEY); 
			}
			$this->db->where($condition);
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
			$this->db->from($this->table);
			$condition['deleted_at'] = null; 
			if($this->role_key == 1){
				$condition['created_by'] = $this->session->userdata(self::SESSION_KEY); 
			}
			$this->db->where($condition);
            $this->db->order_by('created_at','desc');
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}

		public function get_person_in_contact(){
			$this->db->select("pin_person, IFNULL(GROUP_CONCAT(COALESCE(pin.pin_name, null) ORDER BY pin.pin_name ASC SEPARATOR ', '), '') as pin_list");
			$this->db->from($this->table);
			$condition = [
				'created_by' => $this->session->userdata(self::SESSION_KEY),
            ];
			$this->db->where($condition);
			$this->db->where('LOWER(pin_person) !=', 'me'); 
			$this->db->where('pin_person !=', ''); 
			$this->db->group_by('pin_person');
            $this->db->order_by('pin_person','asc');

			return $data = $this->db->get()->result();
		}

		public function get_pin_availability($pin_name, $id, $type){
			$this->db->select('pin_name');
			$this->db->from($this->table);

			$condition = [
				'pin_name' => $pin_name,
				'created_by' => $this->session->userdata(self::SESSION_KEY)
			];

			$this->db->where($condition);

			if($type == 'update'){
				$this->db->where('id !=', $id);
			}

            $this->db->limit(1);
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return false; 
			} else {
				return true;
			}
		}

		public function get_deleted_pin(){
			$user_id = $this->session->userdata(self::SESSION_KEY);

			$this->db->select("pin.id, pin_name, pin_category, pin.created_at, pin.updated_at, pin.deleted_at, COUNT(1) as visit_attached");
			$this->db->from($this->table);
			$this->db->join('visit', 'visit.pin_id = pin.id', 'left');
			$this->db->where('pin.created_by', $user_id);
			$this->db->where('pin.deleted_at IS NOT NULL');
			$this->db->group_by('pin.id');
			$this->db->order_by('pin.deleted_at', 'DESC');
			$query = $this->db->get();

			return $query->result();
		}

		public function get_owner_pin($id){
			$this->db->select("pin_name, pin_category, username, telegram_user_id");
			$this->db->from($this->table);
			$this->db->join('user', 'user.id = pin.created_by');
			$this->db->where("$this->table.id", $id);
			$this->db->where("user.telegram_is_valid", 1);
			$query = $this->db->get();

			return $query->row();
		}

		public function template_avg_count($table, $col) {
			$this->db->select("CAST(AVG({$col})as SIGNED) as average");
			$this->db->from("(SELECT COUNT(1) as {$col} FROM {$table} GROUP BY created_by) as q");
			$query = $this->db->get();
		
			return $query->row();
		}
		public function get_avg_gallery_pin() {
			return $this->template_avg_count('gallery', 'gallery_count');
		}
		
		public function get_avg_pin_user() {
			return $this->template_avg_count($this->table, 'pin_count');
		}
		
		public function get_avg_visit_pin() {
			return $this->template_avg_count('visit', 'visit_count');
		}

		// Command
		public function insert_marker($data){
			return $this->db->insert($this->table,$data);	
		}

		public function update_marker($data, $id){
			$this->db->where('id', $id);
			return $this->db->update($this->table,$data);	
		}
	}
?>