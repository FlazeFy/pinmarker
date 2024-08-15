<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class MultiModel extends CI_Model {

		public function get_all_data($table){
			$this->db->select('*');
			$this->db->from($table);

			return $data = $this->db->get()->result();
		}
	}
?>