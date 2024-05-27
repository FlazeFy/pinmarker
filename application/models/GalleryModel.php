<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class GalleryModel extends CI_Model {
		// Query
		public function get_all_gallery_by_pin($pin_id){
			$this->db->select('id, gallery_type, gallery_url, gallery_caption, created_at');
			$this->db->from('gallery');
			$condition = [
				'pin_id' => $pin_id,
				'created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
			$this->db->order_by('created_at','desc');

			return $data = $this->db->get()->result();
		}

		public function get_most_gallery($limit){
			$this->db->select('pin_name as context, COUNT(1) as total');
			$this->db->from('gallery');
			$this->db->join('pin','pin.id = gallery.pin_id');
			$condition = [
				'gallery.created_by' => $this->session->userdata('user_id')
            ];
			$this->db->where($condition);
			$this->db->group_by('pin_name');
			$this->db->limit($limit);

			return $data = $this->db->get()->result();
		}

        // Command
		public function insert_gallery($data){
			$this->db->insert('gallery',$data);	
		}
	}
?>