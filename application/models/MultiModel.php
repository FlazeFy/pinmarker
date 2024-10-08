<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class MultiModel extends CI_Model {
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