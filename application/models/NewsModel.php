<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');

	class NewsModel extends CI_Model {
		private $table = "news";
        const SESSION_KEY = 'user_id';

		function __construct(){
            parent::__construct();
            $this->load->helper('generator_helper');
        }

		public function rules($ext)
        {
            return [
				[
					'field' => 'news_title',
					'label' => 'News Title',
					'rules' => 'required|max_length[255]'
				],
				[
					'field' => 'news_source',
					'label' => 'News Source',
					'rules' => 'required|max_length[36]'
				],
			];
        }

        // Command
		public function create_news($pin_id, $news_title, $news_url, $news_source, $published_at){
			$data = [
                'id' => get_UUID(), 
                'pin_id' => $pin_id,
                'news_title' => $news_title, 
				'news_url' => $news_url, 
				'news_source' => $news_source, 
				'published_at' => $published_at, 
                'created_at' => date("Y-m-d H:i:s"), 
            ];

			return $this->db->insert($this->table, $data);	
		}

		// Query
		public function get_news_by_pin_id($per_page, $offset, $pin_id, $user_id){
			$this->db->select('news_title, news_url, news_source, published_at');
			$this->db->from($this->table);
			$this->db->join("pin", "pin.id = $this->table.pin_id");
			$condition = [
				'pin_id' => $pin_id,
				'created_by' => $user_id
            ];
			$this->db->where($condition);
            $this->db->order_by('published_at','desc');
		
			// Pagination count
			$db_count = clone $this->db;
			$total_rows = $db_count->get()->num_rows();
			$total_pages = ceil($total_rows / $per_page);
		
			// Pagination data
			$this->db->limit($per_page, $offset);
			$res = $this->db->get()->result();
			$start_item = $total_rows > 0 ? $offset + 1 : 0;
			$end_item = min($offset + $per_page, $total_rows);
		
			foreach ($res as $dt) {
				$dt->review_rate = (int)$dt->review_rate;
			}
		
			$data['data'] = $res;
			$data['total_page'] = $total_pages;
			$data['total_item'] = $total_rows;
			$data['start_item'] = $start_item;
			$data['end_item'] = $end_item;
		
			return $data;
		}
	}
?>