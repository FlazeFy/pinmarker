<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class VisitModel extends CI_Model {
		private $table = "visit";
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
					'field' => 'visit_desc',
					'label' => 'Visit Desc',
					'rules' => 'max_length[255]',
					'null' => TRUE
				],
				[
					'field' => 'visit_by',
					'label' => 'Visit By',
					'rules' => 'required|max_length[75]'
				],
				[
					'field' => 'visit_with',
					'label' => 'Visit With',
					'rules' => 'max_length[500]',
					'null' => TRUE
				],
			];
        }
		
		// Query
        public function get_all_my_visit_header(){
			$user_id = $this->session->userdata(self::SESSION_KEY);

			$this->db->select('visit.id, visit_desc, pin_name ,visit.created_at');
			$this->db->from($this->table);
			$this->db->join('pin','visit.pin_id = pin.id', 'left');
			$condition = [
                'pin.deleted_at' => null,
				'pin.created_by' => $user_id
            ];
			$this->db->where($condition);
			$this->db->or_where('visit.created_by', $user_id);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_visit_by_id($id){
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$select_query = 'pin_name, pin_desc, pin_lat, pin_long, pin_category, pin.id as pin_id, visit_desc, visit_by, visit_with, visit.created_at, visit.updated_at';

			$this->db->select($select_query);
			$this->db->from($this->table);
			$this->db->join('pin','visit.pin_id = pin.id', 'left');
			$condition_normal = [
                'pin.deleted_at' => null,
				'visit.id' => $id,
				'pin.created_by' => $user_id,
				'visit.created_by' => $user_id
            ];
			$this->db->where($condition_normal);
			$this->db->limit(1);
			$data = $this->db->get()->row();
			if($data){
				return $data;
			} else {
				$this->db->select($select_query);
				$this->db->from($this->table);
				$this->db->join('pin','visit.pin_id = pin.id', 'left');
				$condition_custom = [
					'visit.id' => $id,
					'visit.created_by' => $user_id
				];
				$this->db->where($condition_custom);
				$this->db->order_by('visit.created_at','desc');
				$this->db->limit(1);
				$data = $this->db->get()->row();
				return $data;
			}
		}

		public function get_total_all(){
			$this->db->select('COUNT(1) as total');
			$this->db->from($this->table);

			return $data = $this->db->get()->result();
		}

		public function get_all_my_visit_detail(){
			$user_id = $this->session->userdata(self::SESSION_KEY);

			$this->db->select('visit_desc, visit_by, visit_with, pin_name, pin_category, visit.created_at');
			$this->db->from($this->table);
			$this->db->join('pin','visit.pin_id = pin.id', 'left');
			$condition = [
                'pin.deleted_at' => null,
				'pin.created_by' => $user_id
            ];
			$this->db->where($condition);
			$this->db->or_where('visit.created_by', $user_id);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_most_visit($ctx, $limit){
			$user_id = $this->session->userdata(self::SESSION_KEY);

			$this->db->select("$ctx as context, COUNT(1) as total");
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id', $ctx == 'pin_category' || $ctx == 'visit_by' ?'inner':'left');
			$condition['deleted_at'] = null; 
			if($this->role_key == 1){
				$condition['pin.created_by'] = $this->session->userdata(self::SESSION_KEY); 
			}
			$this->db->where($condition);
			$this->db->or_where('visit.created_by', $user_id);
            $this->db->group_by($ctx);
            $this->db->order_by('total','desc');
            $this->db->limit($limit);

			if($limit == 1){
				return $data = $this->db->get()->row();
			} else if($limit > 1) {
				return $data = $this->db->get()->result();
			}
		}

		public function get_visit_history_by_pin_id($id, $limit, $start){
			$this->db->select('visit_by, visit_desc, visit.created_at, visit_with');
			$this->db->from('pin');
            $this->db->join('visit','visit.pin_id = pin.id');
			$condition = [
				'visit.pin_id' => $id,
                'deleted_at' => null,
				'visit.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);

			if($limit != null){
				$db_count = clone $this->db;
				$total_rows = $db_count->get()->num_rows();
				$total_pages = ceil($total_rows / $limit);

				$this->db->limit($limit, $start);
				$data['data'] = $this->db->get()->result();
				$data['total_page'] = $total_pages;
			} else {
				$data = $this->db->get()->result();
			}
			return $data;
		}

        public function get_last_visit(){
			$this->db->select('pin_name, visit_desc');
			$this->db->from('visit');
            $this->db->join('pin','visit.pin_id = pin.id','left');
			$condition = [
				'pin.created_by' => $this->session->userdata(self::SESSION_KEY),
                'deleted_at' => null,
				'visit.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$condition_2 = [
				'visit.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->group_start();
			$this->db->where($condition);
			$this->db->or_where($condition_2);
			$this->db->group_end();
            $this->db->order_by('visit.created_at','desc');
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}

		public function count_my_visit(){
			$this->db->select('COUNT(1) as total');
			$this->db->from('visit');
            $this->db->join('pin','visit.pin_id = pin.id');
			$condition['deleted_at'] = null; 
			if($this->role_key == 1){
				$condition['pin.created_by'] = $this->session->userdata(self::SESSION_KEY); 
			}
            $this->db->limit(1);

			return $data = $this->db->get()->row();
		}

		public function get_visit_activity($year){
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$date_query = "DATE_FORMAT(created_at, '%Y-%m-%d')";
			
			$this->db->select("$date_query AS context, COUNT(1) AS total");
			$this->db->from($this->table);
			$condition = [
                'YEAR(created_at)' => $year,
				'created_by' => $user_id
            ];
			$this->db->where($condition);
            $this->db->group_by("$date_query");
            $this->db->order_by('created_at','asc');

			return $data = $this->db->get()->result();
		}

		public function get_most_visit_with($limit) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$person_query = "LOWER(visit_with)";

			$this->db->select("$person_query AS context");
			$this->db->from($this->table);
			$condition = [
				'created_by' => $user_id,
				'visit_with IS NOT NULL'
			];
			$this->db->where($condition);
			$data = $this->db->get()->result();
			
			$name_counts = [];			
			foreach ($data as $row) {
				if (!empty($row->context)) {
					// Separate using ", " and ", and "
					$names = preg_split('/, and |, /', $row->context);
					
					foreach ($names as $name) {
						$name = trim(strtolower($name)); 
						if (!empty($name)) {
							if (isset($name_counts[$name])) {
								$name_counts[$name]++;
							} else {
								$name_counts[$name] = 1;
							}
						}
					}
				}
			}
			arsort($name_counts);

			$result = [];
			$i = 0;
			foreach ($name_counts as $context => $total) {
				$result[] = (object)['context' => $context, 'total' => $total];
				
				if (++$i >= $limit) {
					break;
				}
			}
		
			return $result;
		}
		

		public function get_visit_activity_by_date($date){
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$date_query = "DATE_FORMAT(visit.created_at, '%Y-%m-%d') =";

			$this->db->select('pin_name, visit_desc, visit_by, visit_with, visit.created_at');
			$this->db->from($this->table);
			$this->db->join('pin','visit.pin_id = pin.id', 'left');
			$condition = [
                'pin.deleted_at' => null,
				$date_query => $date,
				'pin.created_by' => $user_id
            ];
			$condition_external_visit = [
				$date_query => $date,
				'visit.created_by' => $user_id
            ];
			$this->db->where($condition);
			$this->db->where($condition_external_visit);
            $this->db->order_by('visit.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_total_visit_by_by_pin_id($id){
			$this->db->select('visit_by as context, COUNT(1) as total');
			$this->db->from($this->table);
			$condition = [
				'created_by' => $this->session->userdata(self::SESSION_KEY),
				'pin_id' => $id
            ];
			$this->db->where($condition);
            $this->db->group_by('context');

			return $data = $this->db->get()->result();
		}

		public function get_total_visit_by_month() {
			$user_id = $this->session->userdata(self::SESSION_KEY);

			$this->db->select("DATE_FORMAT(visit.created_at, '%M') as context, COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->join('pin', 'visit.pin_id = pin.id', 'left');
			$condition = [
				'pin.deleted_at' => null,
				'pin.created_by' => $user_id,
				'YEAR(visit.created_at)' => date('Y')
			];
			$condition_external_visit = [
				'visit.created_by' => $user_id,
				'YEAR(visit.created_at)' => date('Y')
			];
			$this->db->where($condition);
			$this->db->where($condition_external_visit);
			$this->db->group_by("context");
			$this->db->order_by("context", 'desc');
			$data = $this->db->get()->result();

			$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		
			$result = array_fill_keys($months, 0);
		
			foreach ($data as $row) {
				$result[$row->context] = $row->total;
			}
		
			$res = [];
			foreach ($result as $month => $total) {
				$res[] = (object) ['context' => $month, 'total' => $total];
			}			
		
			return $res;
		}

		// Command
		public function insert_visit($data){
			return $this->db->insert($this->table,$data);	
		}
		public function update_visit($data,$id){
			$this->db->where('id', $id);
			return $this->db->update($this->table,$data);	
		}
	}
?>