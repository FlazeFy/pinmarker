<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class GalleryModel extends CI_Model {
        // Command
		public function insert_gallery($data){
			$this->db->insert('gallery',$data);	
		}
	}
?>