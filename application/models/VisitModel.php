<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class VisitModel extends CI_Model {
		private $table = "visit";
        const SESSION_KEY = 'user_id';
		private $role_key;
		private $month_name;

		public function __construct() {
			parent::__construct();
			$this->role_key = $this->session->userdata('role_key');
			$this->month_name = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
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

		public function get_all_visit_with() {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$person_query = "LOWER(visit_with)";

			$this->db->select("$person_query AS context, CASE WHEN pin_name is null THEN SUBSTRING(visit_desc, LOCATE(' at ', visit_desc) + 4) ELSE pin_name END AS location, visit.created_at as visit_at",false);
			$this->db->from($this->table);
			$this->db->join("pin","pin.id = visit.pin_id","left");
			$condition = [
				'visit.created_by' => $user_id,
				'visit_with IS NOT NULL'
			];
			$this->db->where($condition);
			$data = $this->db->get()->result();
			
			$name_data = [];			
			foreach ($data as $row) {
				if (!empty($row->context)) {
					$names = preg_split('/, and |, /', $row->context);
		
					foreach ($names as $name) {
						$name = trim(strtolower($name));
						if (!empty($name)) {
							if (!isset($name_data[$name])) {
								$name_data[$name] = [
									'total' => 0,
									'locations' => [],
									'visit_at' => [],
								];
							}
		
							$name_data[$name]['total']++;
		
							if (!empty($row->location) && !in_array($row->location, $name_data[$name]['locations'])) {
								$name_data[$name]['locations'][] = $row->location;
							}
							if (!empty($row->visit_at) && !in_array($row->visit_at, $name_data[$name]['visit_at'])) {
								$name_data[$name]['visit_at'][] = $row->visit_at;
							}
						}
					}
				}
			}
		
			$result = [];
			foreach ($name_data as $name => $data) {
				$result[] = (object)[
					'name' => $name,
					'total' => $data['total'],
					'locations' => implode(', ', $data['locations']), 
					'visit_at' => implode(', ', $data['visit_at']) 
				];
			}
		
			usort($result, function ($a, $b) {
				return $b->total - $a->total;
			});
		
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
		
			$result = array_fill_keys($this->month_name, 0);
		
			foreach ($data as $row) {
				$result[$row->context] = $row->total;
			}
		
			$res = [];
			foreach ($result as $month => $total) {
				$res[] = (object) ['context' => $month, 'total' => $total];
			}			
		
			return $res;
		}

		public function get_total_appearance($name) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);

			// Found on visit with
			$this->db->select("COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->like('visit_with', $name);
    		$this->db->where('created_by', $user_id); 
			$data_visit = $this->db->get()->row();		
			$total_visit = $data_visit ? $data_visit->total : 0;

			// Found on pin
			$this->db->select('COUNT(1) as total');
			$this->db->from("pin");
			$this->db->like("pin_person", $name);
			$this->db->where("created_by", $user_id);
			$data_pin = $this->db->get()->row();
			$total_pin = $data_pin ? $data_pin->total : 0;

			$res = $total_pin + $total_visit; 	
		
			return $res;
		}

		public function get_visit_by_person($name,$limit,$start) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);

			$this->db->select("visit.id, visit_desc, visit_with, visit_with, visit.created_at, pin_name");
			$this->db->from($this->table);
			$this->db->join('pin','pin.id = visit.pin_id');
			$this->db->like('visit_with', $name);
    		$this->db->where('visit.created_by', $user_id); 
			$this->db->order_by('visit.created_at','DESC');

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

		public function get_visit_pertime_by_person($name,$type,$year = null) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);
			$ctx = $type == "month" ? "DATE_FORMAT(visit.created_at, '%M')" : "$type(visit.created_at)";

			$this->db->select("$ctx as context, COUNT(1) as total, IFNULL(GROUP_CONCAT(COALESCE(pin.pin_name, null) ORDER BY pin.pin_name ASC SEPARATOR ', '), '') as visit_list");
			$this->db->from($this->table);
			$this->db->join('pin','pin.id = visit.pin_id');
			$this->db->like('visit_with', $name);
    		$this->db->where('visit.created_by', $user_id); 
			$this->db->group_by($ctx);
			$data = $this->db->get()->result();

			if($type == "month"){
				$result = array_fill_keys($this->month_name, 0);
			
				foreach ($data as $row) {
					$result[$row->context] = $row->total;
				}
			
				$res = [];
				foreach ($result as $month => $total) {
					$res[] = (object) ['context' => $month, 'total' => $total];
				}
			} else{
				$res = $data;
			}

			return $res;
		}

		public function get_visit_location_by_person($name,$is_group) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);
			$ext = "";

			if($is_group){
				$ext = ",COUNT(1) as total_visit";
			}
			$this->db->select("pin.id, pin_name, pin_lat, pin_long, pin_category, pin_desc, dictionary_color as pin_color, is_favorite, visit_by, visit_with, visit.created_at as visit_at$ext");
			$this->db->from($this->table);
			$this->db->join('pin','pin.id = visit.pin_id');
			$this->db->join('dictionary','dictionary.dictionary_name = pin.pin_category');
			$this->db->like('visit_with', $name);
    		$this->db->where('visit.created_by', $user_id); 
			if($is_group){
				$this->db->group_by('pin.id');
			}
			$this->db->order_by('visit.created_at','DESC');

			$res = $this->db->get()->result();

			return $res;
		}

		public function get_visit_location_category_by_person($name) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);

			$this->db->select("pin_category as context, COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->join('pin','pin.id = visit.pin_id');
			$this->db->like('visit_with', $name);
    		$this->db->where('visit.created_by', $user_id); 
			$this->db->group_by('pin_category');
			$this->db->order_by('total','DESC');
			$this->db->limit(7);

			$res = $this->db->get()->result();

			return $res;
		}

		public function get_visit_location_favorite_by_person($name) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);

			$this->db->select("CASE WHEN pin.is_favorite = 1 THEN 'Favorited' ELSE 'Normal' END as context, COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->join('pin','pin.id = visit.pin_id');
			$this->db->like('visit_with', $name);
    		$this->db->where('visit.created_by', $user_id); 
			$this->db->group_by('context');
			$this->db->order_by('total','DESC');

			$res = $this->db->get()->result();

			return $res;
		}

		public function get_visit_daily_hour_by_person($name) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);

			$this->db->select("COUNT(1) as total, HOUR(visit.created_at) as hour, DAYNAME(visit.created_at) as day_name");
			$this->db->from($this->table);
			$this->db->join('pin', 'pin.id = visit.pin_id');
			$this->db->like('visit_with', $name);
			$this->db->where('visit.created_by', $user_id);
			$this->db->group_by(['day_name', 'hour']);
			$this->db->order_by("FIELD(DAYNAME(visit.created_at), 'Sunday', 'Saturday', 'Friday', 'Thursday', 'Wednesday', 'Tuesday', 'Monday')", false);
			$this->db->order_by('hour', 'ASC');

			$res = $this->db->get()->result();

			$full_day = ['Sunday', 'Saturday', 'Friday', 'Thursday', 'Wednesday', 'Tuesday', 'Monday'];
			$full_hour = ['00:00-00:59', '01:00-01:59', '02:00-02:59', '03:00-03:59','04:00-04:59', '05:00-05:59', '06:00-06:59', '07:00-07:59',
				'08:00-08:59', '09:00-09:59', '10:00-10:59', '11:00-11:59','12:00-12:59', '13:00-13:59', '14:00-14:59', '15:00-15:59',
				'16:00-16:59', '17:00-17:59', '18:00-18:59', '19:00-19:59','20:00-20:59', '21:00-21:59', '22:00-22:59', '23:00-23:59'
			];

			$final_res = [];
			foreach ($full_day as $day) {
				foreach (range(0, 23) as $hour) {
					$found = false;
					foreach ($res as $dt) {
						if ($dt->day_name == $day && (int)$dt->hour == $hour) {
							$final_res[] = [
								'total' => (int)$dt->total,
								'hour' => $full_hour[$hour],
								'day' => $day
							];
							$found = true;
							break;
						}
					}
		
					if (!$found) {
						$final_res[] = [
							'total' => 0,
							'hour' => $full_hour[$hour],
							'day' => $day
						];
					}
				}
			}

			return $final_res;
		}

		public function get_visit_person_summary($name){
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);

			$this->db->select("MAX(visit.created_at) as last_trip, MIN(visit.created_at) as first_trip, MAX(pin_category) as most_visited_category");
			$this->db->from($this->table);
			$this->db->join('pin', 'pin.id = visit.pin_id');
			$this->db->like('visit_with', $name);
			$this->db->where('visit.created_by', $user_id);
			$res = $this->db->get()->row();

			$res->ranking = $this->get_ranking_visit($name);
			$res->favorite_hour_context = $this->get_favorite_hour($name)->context;
			$res->favorite_hour_total = $this->get_favorite_hour($name)->total;

			return $res;
		}

		public function get_visit_trends($name){
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);
			$last_month = date('m Y', strtotime("first day of -1 month"));
			$last2_month = date('m Y', strtotime("first day of -2 month"));
		
			$this->db->select("COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->like('visit_with', $name);
			$this->db->where('visit.created_by', $user_id);
			$this->db->where('DATE_FORMAT(visit.created_at, "%m %Y") =', $last_month);  
			$res_last_month = $this->db->get()->row();
		
			$this->db->select("COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->like('visit_with', $name);
			$this->db->where('visit.created_by', $user_id);
			$this->db->where('DATE_FORMAT(visit.created_at, "%m %Y") =', $last2_month);  
			$res_last2_month = $this->db->get()->row();

			// Count growth
			$total_last_month = $res_last_month->total ?? 0;
    		$total_last2_month = $res_last2_month->total ?? 0;
			if ($total_last2_month > 0) {
				$growth = (($total_last_month - $total_last2_month) / $total_last2_month) * 100;
			} else {
				$growth = ($total_last_month > 0) ? 100 : 0;
			}
		
			return $growth;
		}

		public function get_top_visit_person_journey() {
			$user_id = $this->session->userdata(self::SESSION_KEY);
		
			// Fetching
			$this->db->select("LOWER(visit_with) AS context, DATE_FORMAT(visit.created_at, '%b %Y') as visit_at", false);
			$this->db->from($this->table);
			$condition = [
				'visit.created_by' => $user_id,
				'visit_with IS NOT NULL'
			];
			$this->db->where($condition);		
			$this->db->where('visit.created_at >=', date('Y-m-01', strtotime('-12 months')));
			$this->db->where('visit.created_at <', date('Y-m-01', strtotime('+1 month')));
			$data = $this->db->get()->result();
		
			// Month generate
			$months = [];
			for ($i = 0; $i < 12; $i++) {
				$months[] = date('M Y', strtotime("first day of -$i month"));
			}
			$months = array_reverse($months);
		
			$result = [];
			// Mapping name
			foreach ($data as $row) {
				if (!empty($row->context)) {
					$names = preg_split('/, and |, /', $row->context);
		
					foreach ($names as $name) {
						$name = trim(strtolower($name));
						if (!empty($name)) {
							$key = "{$name}_{$row->visit_at}";
							if (!isset($result[$key])) {
								$result[$key] = (object)[
									'name' => $name,
									'total' => 0,
									'visit_at' => $row->visit_at,
								];
							}
							$result[$key]->total++;
						}
					}
				}
			}
		
			// Mapping name with month visited
			$name_groups = [];
			foreach ($result as $entry) {
				$name_groups[$entry->name][$entry->visit_at] = $entry->total;
			}
		
			// Assign to generated month
			$final_result = [];
			foreach ($name_groups as $name => $month_data) {
				foreach ($months as $month) {
					$final_result[] = (object)[
						'name' => $name,
						'total' => $month_data[$month] ?? 0,
						'visit_at' => $month,
					];
				}
			}
		
			$person_total_visits = [];
			foreach ($name_groups as $name => $month_data) {
				$person_total_visits[$name] = array_sum($month_data);
			}
		
			arsort($person_total_visits);
		
			// Limit top person
			$top_person = array_slice(array_keys($person_total_visits), 0, 7);
			$filtered_result = array_filter($final_result, function ($dt) use ($top_person) {
				return in_array($dt->name, $top_person);
			});
		
			// Sorting
			usort($filtered_result, function ($a, $b) {
				if ($a->name === $b->name) {
					return strtotime($a->visit_at) - strtotime($b->visit_at);
				}
				return strcmp($a->name, $b->name);
			});
		
			return $filtered_result;
		}
						

		public function get_ranking_visit($find_name) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$find_name = str_replace("%20"," ",$find_name);
			$person_query = "LOWER(visit_with)";

			$this->db->select("$person_query AS context",false);
			$this->db->from($this->table);
			$condition = [
				'visit.created_by' => $user_id,
				'visit_with IS NOT NULL'
			];
			$this->db->where($condition);
			$data = $this->db->get()->result();
			
			$name_data = [];			
			foreach ($data as $row) {
				if (!empty($row->context)) {
					$names = preg_split('/, and |, /', $row->context);
		
					foreach ($names as $name) {
						$name = trim(strtolower($name));
						if (!empty($name)) {
							if (!isset($name_data[$name])) {
								$name_data[$name] = [
									'total' => 0,
								];
							}
		
							$name_data[$name]['total']++;
						}
					}
				}
			}
		
			$result = [];
			foreach ($name_data as $name => $data) {
				$result[] = (object)[
					'name' => $name,
					'total' => $data['total'],
				];
			}
		
			usort($result, function ($a, $b) {
				return $b->total - $a->total;
			});

			$ranking = null;
			foreach ($result as $idx => $res) {
				if($res->name == $find_name){
					$ranking = $idx+1;
					break;
				}
			}
		
			return $ranking;
		}

		public function get_favorite_hour($name) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20"," ",$name);

			$this->db->select("HOUR(visit.created_at) as context, COUNT(1) as total");
			$this->db->from($this->table);
			$this->db->like('visit_with', $name);
			$this->db->where('visit.created_by', $user_id); 
			$this->db->group_by("HOUR(visit.created_at)");
			$this->db->order_by("total", "DESC");
			$this->db->limit(1);
			$res = $this->db->get()->row();

			return $res;
		}

		// For attached pin to global list
		public function get_visit_location_favorite_tag_by_person($name) {
			$user_id = $this->session->userdata(self::SESSION_KEY);
			$name = str_replace("%20", " ", $name);
		
			$this->db->select("JSON_UNQUOTE(JSON_EXTRACT(list_tag, '$[*].tag_name')) AS tag_names");
			$this->db->from($this->table);
			$this->db->join('pin', 'pin.id = visit.pin_id');
			$this->db->join('global_list_pin_relation', 'global_list_pin_relation.pin_id = pin.id');
			$this->db->join('global_list', 'global_list.id = global_list_pin_relation.list_id');
			$this->db->like('visit_with', $name);
			$this->db->where('visit.created_by', $user_id);
			$this->db->where('list_tag IS NOT NULL');
			$query = $this->db->get();
		
			$tags = [];
			foreach ($query->result() as $row) {
				if (!empty($row->tag_names)) {
					$tag_list = json_decode($row->tag_names);
					foreach ($tag_list as $tag_name) {
						if (isset($tags[$tag_name])) {
							$tags[$tag_name]++;
						} else {
							$tags[$tag_name] = 1;
						}
					}
				}
			}
		
			$output = [];
			foreach ($tags as $tag_name => $total) {
				$output[] = (object) [
					'context' => $tag_name,
					'total' => $total,
				];
			}
		
			return $output;
		}
		
		// Command
		public function insert_visit($data){
			return $this->db->insert($this->table,$data);	
		}
		public function update_visit($data,$id){
			$this->db->where('id', $id);
			return $this->db->update($this->table,$data);	
		}
		public function delete_visit($id){
			return $this->db->delete($this->table,['id'=>$id]);	
		}
	}
?>