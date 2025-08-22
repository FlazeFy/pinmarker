<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class BotRelModel extends CI_Model {
		private $table = "bot_relation";
        const SESSION_KEY = 'user_id';
		private $role_key;
		private $month_name;

		public function __construct() {
			parent::__construct();
			$this->role_key = $this->session->userdata('role_key');
		}
		
		// Query
		public function distribution_bot_by_context($ctx,$limit = 7){
			$this->db->select("$ctx as context, count(1) as total");
			$this->db->from($this->table);
			$this->db->group_by($ctx);
			$this->db->order_by('total');
            $this->db->limit($limit);

			return $data = $this->db->get()->result();
		}
	}
?>