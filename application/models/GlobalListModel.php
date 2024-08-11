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
	}
?>