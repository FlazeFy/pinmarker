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