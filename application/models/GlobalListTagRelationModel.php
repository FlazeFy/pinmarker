<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class GlobalListTagRelationModel extends CI_Model {
		private $table = "global_list_tag_relation";
        const SESSION_KEY = 'user_id';
		private $role_key;

		public function __construct() {
			parent::__construct();
			$this->role_key = $this->session->userdata('role_key');
		}

		// Query
		public function get_favorite_tag_by_person($name, $user_id){
			$this->db->select("tag_name as context, COUNT(1) as total");
            $this->db->from($this->table);
            $this->db->join("global_list","global_list.id = $this->table.list_id");
            $this->db->join("global_list_pin_relation","global_list_pin_relation.list_id = global_list.id");
            $this->db->join("pin","pin.id = global_list_pin_relation.pin_id");
            $this->db->join("visit","visit.pin_id = pin.id");
            $this->db->where("visit.created_by", $user_id);
            $this->db->like("visit_with", $name);
            $this->db->group_by("context");
            $this->db->order_by("total", "desc");
            $res = $this->db->get()->result();

            $others = 0;
            $res_final = [];

            // If data variation more than 10
            foreach ($res as $idx => $dt) {
                if ($idx < 7) {
                    $res_final[] = [
                        'context' => $dt->context,
                        'total' => (int)$dt->total
                    ];
                } else {
                    $others += (int)$dt->total;
                }
            }

            // Add others only if exists
            if ($others > 0) {
                $res_final[] = [
                    'context' => 'Others Tag',
                    'total' => $others
                ];
            }

            return $res_final;
		}

        public function get_tag_list_by_id($list_id) {
            $this->db->select("tag_name");
            $this->db->from($this->table);
            $this->db->where("list_id", $list_id);
            $this->db->order_by("tag_name", "asc");
            return $this->db->get()->result();
        }

        // Command
        public function insert_tag_by_id($list_id, $list_tag) {
            if (empty($list_tag)) return;
        
            $tags = explode(',', $list_tag);
        
            foreach ($tags as $tag) {
                $tag_name = trim($tag);
                if (empty($tag_name)) continue;
        
                $data = [];
                $data['id'] = get_UUID();
                $data['list_id'] = $list_id;
                $data['tag_name'] = $tag_name;
                $data['created_at'] = date("Y-m-d H:i:s");
        
                $this->db->insert($this->table, $data);
            }
        }

        public function delete_tag_by_id($list_id) {
            return $this->db->delete($this->table, [
				'list_id' => $list_id
			]);
        }
	}
?>