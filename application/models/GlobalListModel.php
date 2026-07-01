<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class GlobalListModel extends CI_Model {
		private $table = "global_list";
		private $table_rel_pin = "global_list_pin_relation";
		private $table_rel_tag = "global_list_tag_relation";
        const SESSION_KEY = 'user_id';

		public function rules()
        {
            return [
				[
					'field' => 'list_code',
					'label' => 'List Code',
					'rules' => 'max_length[6]',
					'null' => TRUE
				],
				[
					'field' => 'list_name',
					'label' => 'List Name',
					'rules' => 'required|max_length[75]',
				],
				[
					'field' => 'list_desc',
					'label' => 'List Description',
					'rules' => 'max_length[255]|min_length[2]',
					'null' => TRUE
				]
			];
        }

		public function get_global_list_name($user_id){
			$this->db->select("id,list_name");
			$this->db->from($this->table);
			$this->db->where('created_by',$user_id);
			$this->db->order_by("created_at","ASC");

			return $this->db->get()->result();
		}

		public function get_my_global_list($search, $with_companion, $visit_with, $limit, $start, $sorting, $user_id) {
			// Main query
			$extra = "";
			if($with_companion === "1"){
				$extra .= ",
					IFNULL(GROUP_CONCAT(DISTINCT visit.visit_with ORDER BY visit.visit_with ASC SEPARATOR ', '), '') as visit_with
				";
			}
		
			$this->db->select("
				$this->table.id, list_name, list_desc, $this->table.created_at, $this->table.updated_at,
				IFNULL(GROUP_CONCAT(DISTINCT pin.pin_name ORDER BY pin.pin_name ASC SEPARATOR ', '), '') as pin_list,
				IFNULL(GROUP_CONCAT(DISTINCT global_list_tag_relation.tag_name ORDER BY global_list_tag_relation.tag_name ASC SEPARATOR ', '), '') as tag_list,
				IFNULL(COUNT(DISTINCT pin.id), 0) as total_pin,
				IFNULL(COUNT(DISTINCT visit.id), 0) as total_visit
				$extra
			");
			$this->db->from($this->table);
			$this->db->join('global_list_pin_relation', "global_list_pin_relation.list_id = $this->table.id");
			$this->db->join('global_list_tag_relation', "global_list_tag_relation.list_id = $this->table.id");
			$this->db->join('pin', "pin.id = global_list_pin_relation.pin_id");
			$this->db->join('visit', 'visit.pin_id = pin.id', 'left');
		
			$condition = [
				"$this->table.created_by" => $user_id
			];
			$this->db->where($condition);
		
			// Filtering
			if ($search) {
				$search = strtolower($search);
		
				$this->db->group_start();
				$this->db->like('LOWER(list_name)', $search);
				$this->db->or_like('LOWER(pin.pin_name)', $search);
				$this->db->group_end();
			}
		
			if ($visit_with !== "-all-") {
				$companions = array_map('trim', explode(',', urldecode($visit_with)));
		
				$this->db->group_start();
				foreach ($companions as $companion) {
					$this->db->or_like('visit.visit_with', $companion, 'both');
				}
				$this->db->group_end();
			}
		
			$this->db->group_by("$this->table.id");
		
			// Sorting
			$sorting_split = explode('-', $sorting);
			$target_sort = $sorting_split[0];
			$value_sort = $sorting_split[1];
			$this->db->order_by($target_sort, $value_sort);
		
			// Pagination count
			$db_count = clone $this->db;
			$total_rows = $db_count->get()->num_rows();
			$total_pages = ceil($total_rows / $limit);
			$start_item = $total_rows > 0 ? $start + 1 : 0;
			$end_item = min($start + $limit, $total_rows);
		
			$this->db->limit($limit, $start);
		
			$data['data'] = $this->db->get()->result();
			// Tidy up the visit with, and aggregate col
			foreach ($data['data'] as &$row) {
				if(empty($row->visit_with)){
					$row->visit_with = null;
					continue;
				}

				$row->total_pin = (int)$row->total_pin;
				$row->total_visit = (int)$row->total_visit;
			
				$companions = [];
				foreach (explode(',', $row->visit_with) as $name) {
					$name = trim($name);
			
					if (stripos($name, 'and ') === 0) $name = trim(substr($name, 4));
					if ($name !== '') $companions[] = $name;
				}
			
				$companions = array_unique($companions);
				sort($companions);
			
				$row->visit_with = count($companions) ? implode(', ', $companions) : null;
			}
			unset($row);

			$data['total_page'] = $total_pages;
			$data['total_item'] = $total_rows;
			$data['start_item'] = $start_item;
			$data['end_item'] = $end_item;
		
			return $data;
		}

		public function get_detail_list_by_id($id, $user_id = null){
			$this->db->select("$this->table.id, list_name, list_desc, $this->table.created_at, $this->table.updated_at, username as created_by");
			$this->db->from($this->table);
			$this->db->join('user',"user.id = $this->table.created_by");
			$this->db->where("$this->table.id", $id);
			if ($user_id) $this->db->where("created_by", $user_id);

			return $data = $this->db->get()->row();
		}

		public function get_pin_list_by_id($id){
			$this->db->select("global_list_pin_relation.id, pin_name, pin_desc, pin_lat, pin_long, pin_call, pin_category, global_list_pin_relation.created_at, pin_address, user.username as created_by,
				IFNULL(GROUP_CONCAT(COALESCE(gallery_url, null) SEPARATOR ', '), '') as gallery_url,IFNULL(GROUP_CONCAT(COALESCE(gallery_caption, null) SEPARATOR ', '), '') as gallery_caption,IFNULL(GROUP_CONCAT(COALESCE(gallery_type, null) SEPARATOR ', '), '') as gallery_type");
			$this->db->from("global_list_pin_relation");
			$this->db->join('pin',"global_list_pin_relation.pin_id = pin.id");
			$this->db->join('user',"user.id = global_list_pin_relation.created_by");
			$this->db->join('gallery',"pin.id = gallery.pin_id",'left');
			$condition = [
				"global_list_pin_relation.list_id" => $id,
            ];
			$this->db->where($condition);
			$this->db->group_by("global_list_pin_relation.id");
			$this->db->order_by("global_list_pin_relation.created_at",'desc');

			return $data = $this->db->get()->result();
		}

		public function get_global_list_pin_by_pin_id($pin_id) {
			$this->db->select("list_name, $this->table.id");
			$this->db->from($this->table);
			$this->db->join($this->table_rel_pin,"$this->table.id = $this->table_rel_pin.list_id");
			$this->db->join('pin',"$this->table_rel_pin.pin_id = pin.id");
			$condition = [
				'pin_id' => $pin_id,
				'pin.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->group_by("$this->table.id");
			$this->db->order_by("list_name",'desc');

			return $data = $this->db->get()->result();
		}

		public function get_global_list_tag_by_pin_id($pin_id) {
			$this->db->select("tag_name");
			$this->db->from($this->table);
			$this->db->join($this->table_rel_pin,"$this->table.id = $this->table_rel_pin.list_id");
			$this->db->join("global_list_tag_relation","global_list_tag_relation.list_id = $this->table_rel_pin.list_id");
			$this->db->join('pin',"$this->table_rel_pin.pin_id = pin.id");
			$condition = [
				'pin_id' => $pin_id,
				'pin.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->group_by("tag_name");
			$this->db->order_by("tag_name",'desc');

			return $data = $this->db->get()->result();
		}

		public function check_pin_edit_mode($id, $user_id){
			$this->db->select("1");
			$this->db->from($this->table);
			$condition = [
				"$this->table.id" => $id,
				"$this->table.created_by" => $user_id,
            ];
			$this->db->where($condition);
			$data = $this->db->get()->result();

			if($user_id && $data && count($data) > 0){
				return true;
			} else {
				return false;
			}
		}

		public function get_owner_list($id){
			$this->db->select("list_name, list_desc, username, telegram_user_id");
			$this->db->from($this->table);
			$this->db->join('user', "user.id = $this->table.created_by");
			$this->db->where("$this->table.id", $id);
			$this->db->where("user.telegram_is_valid", 1);
			$query = $this->db->get();

			return $query->row();
		}

		public function get_recommended_pin_address($limit = 6) {
			$this->db->select("pin_address");
			$this->db->from($this->table);
			$this->db->join("global_list_pin_relation","$this->table.id = global_list_pin_relation.list_id");
			$this->db->join("pin","pin.id = global_list_pin_relation.pin_id");

			// Delete soon!
			$this->db->where("pin_address IS NOT NULL");
			$this->db->where("pin_address !=", "");

			$this->db->group_by("$this->table.id");
			$this->db->order_by('RAND()');
			$this->db->limit($limit);
			$query = $this->db->get();

    		return $query->result();
		}

		public function get_recommended_tag($limit = 12) {
			$this->db->select("tag_name");
			$this->db->from($this->table);
			$this->db->join("global_list_pin_relation","$this->table.id = global_list_pin_relation.list_id");
			$this->db->join("global_list_tag_relation","$this->table.id = global_list_tag_relation.list_id");
			$this->db->join("pin","pin.id = global_list_pin_relation.pin_id");
			$this->db->group_by("$this->table.id");
			$this->db->order_by('RAND()');
			$this->db->limit($limit);
			$query = $this->db->get();

    		return $query->result();
		}

		public function is_name_available($user_id, $list_name, $id = null) {
			$this->db->select("1");
			$this->db->from($this->table);
			$this->db->where("created_by", $user_id);
			$this->db->where("LOWER(list_name)", strtolower($list_name));
			if ($id) $this->db->where("id", "!=", $id);
			$data = $this->db->get()->result();

			return $data ? false : true;
		}

		// Command
		public function insert($data){
			return $this->db->insert($this->table,$data);	
		}

		public function insert_rel($data){
			return $this->db->insert($this->table_rel_pin,$data);	
		}

		public function update_list($id, $data, $user_id){
			$data['updated_at'] = date('Y-m-d H:i:s');
            return $this->db->update($this->table, $data, [
				'id' => $id,
				'created_by' => $user_id,
			]);
        }

		public function delete_global_list($id, $user_id){
    		return $this->db->delete($this->table,[
				'id' => $id,
				'created_by' => $user_id
			]);
		}

		public function delete_global_list_pin($list_id){
    		return $this->db->delete($this->table_rel_pin, [
				'list_id' => $list_id
			]);
		}

		public function delete_global_list_tag($list_id){
    		return $this->db->delete($this->table_rel_tag, [
				'list_id' => $list_id
			]);
		}
	}
?>