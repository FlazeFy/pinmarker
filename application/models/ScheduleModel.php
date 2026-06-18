<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class ScheduleModel extends CI_Model {
		private $table = "schedule";

		public function __construct() {
			parent::__construct();
			$this->load->helper('generator_helper');
		}

		public function rules($ext)
        {
            return [
				[
					'field' => 'schedule_day',
					'label' => 'Schedule Day',
					'rules' => 'required|max_length[3]|min_length[3]'
				],
				[
					'field' => 'schedule_hour_start',
					'label' => 'Schedule Hour Start',
					'rules' => 'max_length[5]',
					'null' => TRUE
				],
				[
					'field' => 'schedule_hour_end',
					'label' => 'Schedule Hour End',
					'rules' => 'max_length[5]',
					'null' => TRUE
				],
			];
        }

		// Query
		public function get_schedule_by_pin_id($pin_id) {
			$this->db->select("
				schedule_day, is_24_h, is_closed,
				DATE_FORMAT(schedule_hour_start, '%H:%i') AS schedule_hour_start,
				DATE_FORMAT(schedule_hour_end, '%H:%i') AS schedule_hour_end
			");
			$this->db->from($this->table);
			$this->db->where("pin_id", $pin_id);
			$this->db->order_by("FIELD(schedule_day, 'MON','TUE','WED','THU','FRI','SAT','SUN')", '', false);
		
			return $this->db->get()->result();
		}	
		
		public function get_all_schedule($search, $pin_category, $is_favorite, $is_visited, $is_open_only, $user_id) {
			$day_now = strtoupper(date('D'));
    		$hour_now = date('H:i');

			$this->db->select("
				pin.id, pin_name, schedule_day, is_24_h, is_closed, is_favorite,
				DATE_FORMAT(schedule_hour_start, '%H:%i') AS schedule_hour_start,
				DATE_FORMAT(schedule_hour_end, '%H:%i') AS schedule_hour_end
			");
			$this->db->from($this->table);
			$this->db->join("pin", "pin.id = $this->table.pin_id");
			$this->db->join('visit','visit.pin_id = pin.id','left');

			// Filtering
			if($search){
				$this->db->like('pin_name', $search, 'both');
			}
			if($pin_category){
				$category = str_replace("%20", " ", $category);
				$this->db->where('pin_category',$category);
			}
			if($is_favorite !== "all"){
				$this->db->where('is_favorite',(int)$is_favorite);
			}
			if ($is_open_only !== 'all' && (int)$is_open_only === 1) {
				$this->db->where('is_closed', 0);
				$this->db->group_start();
					// same day & open 24h
					$this->db->group_start();
						$this->db->where('schedule_day', $day_now);
						$this->db->where('is_24_h', 1);
					$this->db->group_end();
					// same day & current hour is within operating hours
					$this->db->or_group_start();
						$this->db->where('schedule_day', $day_now);
						$this->db->where('is_24_h', 0);
						$this->db->where("DATE_FORMAT(schedule_hour_start, '%H:%i') <=", $hour_now);
						$this->db->where("DATE_FORMAT(schedule_hour_end, '%H:%i') >=", $hour_now);
					$this->db->group_end();
				$this->db->group_end();
			}
			if($is_visited !== "all"){
				if ((int)$is_visited === 1) {
					$this->db->where('visit.id IS NOT NULL', null, false);
				} else {
					$this->db->where('visit.id IS NULL', null, false);
				}
			}

			$this->db->group_by("schedule_day, schedule_hour_start, schedule_hour_end, pin.id");
			$this->db->order_by("FIELD(schedule_day, 'MON','TUE','WED','THU','FRI','SAT','SUN')", '', false);
			$this->db->order_by('is_favorite','DESC');
			$res = $this->db->get()->result();

			foreach ($res as $dt) {
				$dt->is_favorite = (int)$dt->is_favorite;
				$dt->is_24_h = (int)$dt->is_24_h;
				$dt->is_closed = (int)$dt->is_closed;
			}

			return $res;
		}

		// Command
		public function insert_schedules($pin_id, $schedules){
			foreach ($schedules as $dt) {
				$data = [
					'id' => get_UUID(),
					'pin_id' => $pin_id,
					'schedule_day' => $dt['schedule_day'],
					'schedule_hour_start' => (int)$dt['is_closed'] === 1 ? null : $dt['schedule_hour_start'],
					'schedule_hour_end' => (int)$dt['is_closed'] === 1 ? null : $dt['schedule_hour_end'],
					'is_24_h' => $dt['is_24_h'],
					'is_closed' => $dt['is_closed']
				];
		
				$this->db->insert($this->table, $data);
			}
		
			return true;
		}
	}
?>