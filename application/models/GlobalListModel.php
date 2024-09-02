<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class GlobalListModel extends CI_Model {
		private $table = "global_list";
		private $table_rel = "global_list_pin_relation";
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
				],
				[
					'field' => 'list_tag',
					'label' => 'List Tag',
					'null' => TRUE
				],
			];
        }

		public function get_global_list_name($user_id){
			$this->db->select("id,list_name");
			$this->db->from($this->table);
			$this->db->where('created_by',$user_id);
			$this->db->order_by("created_at","ASC");

			return $this->db->get()->result();
		}

		public function get_global_list($search) {
			$this->db->select("$this->table.id, 
				IFNULL(GROUP_CONCAT(COALESCE(pin.pin_name, null) ORDER BY pin.pin_name ASC SEPARATOR ', '), '') as pin_list, 
				IFNULL(COUNT(pin.pin_name), 0) as total, 
				list_name, list_desc, list_tag, $this->table.created_at, 
				user.username as created_by");
			$this->db->from($this->table);
			$this->db->join('global_list_pin_relation', "global_list_pin_relation.list_id = $this->table.id");
			$this->db->join('pin', "pin.id = global_list_pin_relation.pin_id");
			$this->db->join('user', "user.id = $this->table.created_by");
			if (!empty($search)) {
				$search = strtolower($search);
				$this->db->like('LOWER(list_name)', $search);
				$this->db->or_like('LOWER(pin.pin_name)', $search);
				$this->db->or_like('LOWER(user.username)', $search);
				$this->db->or_like('LOWER(list_tag)', $search);
			}
			$this->db->order_by("$this->table.created_at", 'desc');
			$this->db->group_by("$this->table.id");
		
			return $this->db->get()->result();
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

		public function get_global_tag(){
			$this->db->select("list_tag");
			$this->db->from($this->table);
			$data = $this->db->get()->result();
			
			$res = [];
			$addedSlugs = [];

			foreach($data as $dt){		
				if($dt->list_tag){
					$tags = json_decode($dt->list_tag);

					foreach($tags as $tg){
						if (!isset($addedSlugs[$tg->tag_name])) {
							$addedSlugs[$tg->tag_name] = 1;
			
							array_push($res, (object)[
								'tag_name' => $tg->tag_name,
								'count' => 1
							]);
						} else {
							$addedSlugs[$tg->tag_name]++;
			
							foreach ($res as $item) {
								if ($item->tag_name == $tg->tag_name) {
									$item->count = $addedSlugs[$tg->tag_name];
									break;
								}
							}
						}
					}
				}
			}

			usort($res, function($a, $b) {
				return $b->count - $a->count;
			});

			return $res;
		}

		public function get_list_tag(){
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

		// Command
		public function insert($data){
			return $this->db->insert($this->table,$data);	
		}
		public function insert_rel($data){
			return $this->db->insert($this->table_rel,$data);	
		}
		public function update_list($id,$data)
        {
            return $this->db->update($this->table, $data, ['id' => $id]);
        }
		public function delete_global_list($id){
    		return $this->db->delete($this->table,[
				'id'=>$id
			]);
		}
		public function delete_global_list_rel($data){
    		return $this->db->delete($this->table_rel,$data);
		}
	}
?>