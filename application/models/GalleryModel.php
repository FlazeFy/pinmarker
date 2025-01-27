<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class GalleryModel extends CI_Model {
		private $table = "gallery";
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
					'field' => 'pin_id',
					'label' => 'Pin ID',
					'rules' => 'required|callback_valid_length[36]'
				],
				[
					'field' => 'gallery_type',
					'label' => 'Gallery Type',
					'rules' => 'required|max_length[14]',
				],
				[
					'field' => 'gallery_url',
					'label' => 'Gallery Url',
					'rules' => 'required|max_length[1000]'
				],
				[
					'field' => 'gallery_caption',
					'label' => 'Gallery Caption',
					'rules' => 'required|max_length[500]',
				],
			];
        }

		// Query
		public function get_all_gallery_by_pin($pin_id){
			$this->db->select('id, gallery_type, gallery_url, gallery_caption, created_at');
			$this->db->from($this->table);
			$condition = [
				'pin_id' => $pin_id,
				'created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->order_by('created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_all_my_gallery(){
			$this->db->select('pin.id as pin_id, gallery.id, pin_name, pin_category, gallery_type, gallery_url, gallery_caption, gallery.created_at');
			$this->db->from($this->table);
			$this->db->join('pin','pin.id = gallery.pin_id');
			$condition = [
				'gallery.created_by' => $this->session->userdata(self::SESSION_KEY)
            ];
			$this->db->where($condition);
			$this->db->order_by('gallery.created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_most_gallery($limit, $year = null){
			$this->db->select('pin_name as context, COUNT(1) as total');
			$this->db->from($this->table);
			$this->db->join('pin','pin.id = gallery.pin_id');

			$condition['deleted_at'] = null; 
			if($this->role_key == 1){
				$condition['gallery.created_by'] = $this->session->userdata(self::SESSION_KEY); 
			}
			if($year){
				$condition['YEAR(gallery.created_at)'] = $year;
			}
			$this->db->where($condition);
			$this->db->group_by('pin_name');
			$this->db->limit($limit);

			return $data = $this->db->get()->result();
		}

        // Command
		public function insert_gallery($data){
			return $this->db->insert($this->table,$data);	
		}

		public function delete_gallery($id){
			return $this->db->delete($this->table,['id'=>$id]);	
		}
	}
?>