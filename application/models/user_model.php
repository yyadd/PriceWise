<?php


class User_model extends CI_Model {


	public function get_users($user_id,$username) {


	//	$config['hostname'] = "localhost";
	//	$config['username'] = "root";
	//	$config['password'] = "root";
	//	$config['database'] = "errand_db";



	//	$connection = $this->load->database($config);

		// get all user's information
		//$this->db->where('id', $user_id);
		$this->db->where([
			
			'id' => $user_id,
			'username' => $username

			]);

		$query = $this->db->get('users');

		return $query->result();
	}

	//	$query = $this->db->query("SELECT * FROM users");//same result as $query = $this->db->get('users')


		//return $query->num_rows();
	//	return $query->num_fields();
	

		// public function create_users($d) {

		// 	$this->db->insert('users', $d);



		// }


		// public function update_users($d,$id) {
		// 	$this->db->where(['id'=>$id]);
		// 	$this->db->update('users', $d);



		// }



		// public function delete_users($id) {
		// 	$this->db->where(['id'=>$id]);
		// 	$this->db->delete('users');
			


		// }


		public function create_user() {


			$options = ['cost' => 12];//the time that password_hash() function will be excute, the more time, the more safer the password is

			//in this way, u can not see the real password in phpmyadmin
			$encripted_pass = password_hash($this->input->post('password'), PASSWORD_BCRYPT, $options); //encripted the password then the password will be more security



			$data = array(


				'first_name' => $this->input->post('first_name'), //array we do not use ';' in listing attributes, we use ','
				'last_name'  => $this->input->post('last_name'),
				'email' 	 => $this->input->post('email'),
				'username' 	 => $this->input->post('username'),
				'password' 	 => $encripted_pass
				


				);


			$insert_data = $this->db->insert('users', $data);

			return $insert_data;


		}

		public function login_user($user,$password) {

			$this->db->where('username', $user);
			//$this->db->where('password', $password); //when password is encripted, the query is not useful when log in

			$result = $this->db->get('users');


			$db_password = $result->row(0)->password;

			//password_verify() is a password verification function from php, will decription the encripted parameter $db_password
			//we can only use it when our password in db is hashed
			if(password_verify($password, $db_password)) {

				return $result->row(0)->id;


			} else {


				return false;



			}



		}



}




?>