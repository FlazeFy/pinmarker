<?php 
	defined('BASEPATH') OR exit('No direct script access alowed');
    class AuthModel extends CI_Model
    {
        private $table_user = "user";
        private $table_admin = "admin";

        const SESSION_KEY = 'user_id';

        public function rules()
        {
            return [
                [
                    'field' => 'username',
                    'label' => 'Username or Email',
                    'rules' => 'required|min_length[5]'
                ],
                [
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'required|max_length[255]|min_length[5]'
                ]
            ];
        }

        public function rules_user()
        {
            return [
                [
                    'field' => 'username',
                    'label' => 'Username or Email',
                    'rules' => 'required|min_length[5]'
                ],
                [
                    'field' => 'fullname',
                    'label' => 'Fullname',
                    'rules' => 'required|max_length[50]|min_length[2]'
                ],
                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'required|max_length[255]|min_length[10]'
                ]
            ];
        }

        public function get_total_all(){
			$this->db->select('COUNT(1) as total');
			$this->db->from($this->table_user);

			return $data = $this->db->get()->result();
		}

        private function find_user($uname, $table)
        {
            $this->db->where('email', $uname)
                    ->or_where('username', $uname);
                    
            $query = $this->db->get($table);
            return $query->row();
        }

        public function login($uname, $pass)
        {
            $role = 1;
            $img_url = null;

            $user = $this->find_user($uname, $this->table_user);
            if (!$user) {
                $role = 0;
                $user = $this->find_user($uname, $this->table_admin);
            } else {
                $img_url = $user->img_url;
            }
            
            if (!$user) {
                return FALSE;
            }

            if (!password_verify($pass, $user->password)) {
                return FALSE;
            }

            $this->session->set_userdata([
                self::SESSION_KEY => $user->id,
                'user_img_url' => $img_url,
                'role_key' => $role
            ]);

            if($role == 1){
                $this->update_last_login($user->id);
            }

            return $this->session->has_userdata(self::SESSION_KEY);
        }

        public function current_user()
        {
            if (!$this->session->has_userdata(self::SESSION_KEY)) {
                return null;
            }

            $user_id = $this->session->userdata(self::SESSION_KEY);
            $user = $this->db->get_where($this->table_user, ['id' => $user_id]);

            if (!$user->row()) {
                $user = $this->db->get_where($this->table_admin, ['id' => $user_id]);
            }

            return $user->row();
        }

        public function get_user_by_id($id){
			$this->db->select('id, fullname, username, email, telegram_user_id, telegram_is_valid, password, img_url, created_at, updated_at, last_login');
			$this->db->from($this->table_user);
            $condition = [
				'id' => $id
            ];
			$this->db->where($condition);
            $query = $this->db->get();
    
            return $query->row();
		}

        public function get_user_by_username($username){
			$this->db->select('*');
			$this->db->from($this->table_user);
            $condition = [
				'username' => $username
            ];
			$this->db->where($condition);
            $query = $this->db->get();
    
            return $query->row();
		}

        public function logout()
        {
            $this->session->unset_userdata(self::SESSION_KEY);
            return !$this->session->has_userdata(self::SESSION_KEY);
        }

        private function update_last_login($id)
        {
            $data = [
                'last_login' => date("Y-m-d H:i:s"),
            ];

            return $this->db->update($this->table_user, $data, ['id' => $id]);
        }

        public function update_user($id,$data)
        {
            return $this->db->update($this->table_user, $data, ['id' => $id]);
        }

        public function insert_user($data)
        {
            return $this->db->insert($this->table_user, $data);
        }
    }
?>