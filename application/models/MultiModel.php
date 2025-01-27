<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class MultiModel extends CI_Model {
		private $table = "gallery";
        const SESSION_KEY = 'user_id';
		private $role_key;

		public function __construct() {
			parent::__construct();
			$this->role_key = $this->session->userdata('role_key');
		}

		public function get_all_data($table, $des_table, $key,$ext){
			if($des_table){
				$this->db->select("$table.*, $des_table.id as ".$des_table."_id$ext");
			} else {
				$this->db->select("*$ext");
			}
			$this->db->from($table);

			if($des_table){
				$this->db->join("$des_table","$des_table.id = $table.created_by",'left');
			}

			return $data = $this->db->get()->result();
		}

		public function get_available_year(){
			// Query Pin
			$this->db->select("YEAR(created_at) as year");
			$this->db->from("pin");
			$condition = [
				'pin.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->group_by('year');
			$year_pin = $this->db->get()->result();

			// Query Visit
			$this->db->select("YEAR(created_at) as year");
			$this->db->from("visit");
			$condition = [
				'visit.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->group_by('year');
			$year_visit = $this->db->get()->result();

			// Combine year
			$combined_years = array_merge($year_pin, $year_visit);
			$unique_years = array_unique(array_column($combined_years, 'year'));

			// Add current year
			$current_year = date('Y');
			if (!in_array($current_year, $unique_years)) {
				$unique_years[] = $current_year;
			}
			
			// Sort year and make it arr of obj
			sort($unique_years);
			$data = array_map(function ($year) {
				return (object)['year' => $year];
			}, $unique_years);
		
			return $data;
		}

		// Command
		public function insert($table, $data){
			return $this->db->insert($table,$data);	
		}
		public function delete($table, $id){
			return $this->db->delete($table,['id'=>$id]);	
		}
		public function soft_delete($table, $id){
			return $this->db->update($table, ['deleted_at'=> date('Y-m-d H:i:s')], ['id' => $id]);
		}
	}
?>