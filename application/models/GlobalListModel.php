<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class GlobalListModel extends CI_Model {
		private $table = "global_list";
        const SESSION_KEY = 'user_id';

		public function get_global_list(){
			$this->db->select("$this->table.id, IFNULL(GROUP_CONCAT(COALESCE(pin.pin_name, null) ORDER BY pin.pin_name ASC SEPARATOR ', '), '') as pin_list, 
				IFNULL(COUNT(pin.pin_name), 0) as total, list_name, list_desc, list_tag, $this->table.created_at, user.username as created_by");
			$this->db->from($this->table);
			$this->db->join('global_list_pin_relation',"global_list_pin_relation.list_id = $this->table.id");
			$this->db->join('pin',"pin.id = global_list_pin_relation.pin_id");
			$this->db->join('user',"user.id = $this->table.created_by");
			$this->db->order_by("$this->table.created_at",'desc');
			$this->db->group_by("$this->table.id");

			return $data = $this->db->get()->result();
		}

		public function get_detail_list_by_id($id){
			$this->db->select("$this->table.id, list_name, list_desc, list_tag, $this->table.created_at, $this->table.updated_at, username as created_by");
			$this->db->from($this->table);
			$this->db->join('user',"user.id = $this->table.created_by");
			$condition = [
				"$this->table.id" => $id,
            ];
			$this->db->where($condition);

			return $data = $this->db->get()->row();
		}

		public function get_pin_list_by_id($id){
			$this->db->select("global_list_pin_relation.id, pin_name, pin_desc, pin_lat, pin_long, pin_call, pin_category, global_list_pin_relation.created_at, pin_address, user.username as created_by");
			$this->db->from("global_list_pin_relation");
			$this->db->join('pin',"global_list_pin_relation.pin_id = pin.id");
			$this->db->join('user',"user.id = global_list_pin_relation.created_by");
			$this->db->order_by("global_list_pin_relation.created_at",'desc');

			return $data = $this->db->get()->result();
		}

		public function get_global($search){
			$this->db->select("$this->table.id, IFNULL(GROUP_CONCAT(COALESCE(pin.pin_name, null) ORDER BY pin.pin_name ASC SEPARATOR ', '), '') as pin_list, 
				IFNULL(COUNT(pin.pin_name), 0) as total, list_name, list_desc, list_tag, $this->table.created_at, user.username as created_by");
			$this->db->from($this->table);
			$this->db->join('global_list_pin_relation',"global_list_pin_relation.list_id = $this->table.id");
			$this->db->join('pin',"pin.id = global_list_pin_relation.pin_id");
			$this->db->join('user',"user.id = $this->table.created_by");
			$this->db->like('list_name', $search);
			$this->db->or_like('list_tag', $search);
			$this->db->order_by("$this->table.created_at",'desc');
			$this->db->group_by("$this->table.id");

			return $data = $this->db->get()->result();
		}
	}
?>