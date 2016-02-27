<?php

class Users extends CI_Controller {


	public function show($user_id) {


		//$this->load->model('user_model')



		$data['results'] = $this->user_model->get_users($user_id,'rico');



		$this->load->view('user_view', $data);

		//foreach ($result as $object) {
		//	echo $object->username. "<br>";
		//}


	}

	public function insert() {

		$username = "peter";
		$password = "secret";

		$this->user_model->create_users([
			//'username' is the column name in the databse
			'username' => $username,
			'password' => $password

		]);
	}


	public function update() {
		$id = 5;
		$username = "wiliam";
		$password = "not so secret";

		$this->user_model->update_users([
			//'username' is the column name in the databse
			'username' => $username,
			'password' => $password

		], $id);
	}

	public function delete() {

		$id = 5;
		$this->user_model->delete_users($id);
	}


	public function register() {

		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[3]|matches[password]');


		if($this->form_validation->run() == FALSE) {

			$data['main_view'] = "users/register_view";

			$this->load->view('layouts/main', $data);

		}
		else {

			if($this->user_model->create_user()) {

				$this->session->set_flashdata('user_registered', 'User has been registered');
				redirect('home/index');

			} else {




			}

			
		}

		

	}

	public function login() {
		//echo $_POST['username']; old way to do
		//$this->input->post('password'); we can use this way to get password now
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]');//the second parameter means the name that used to put into the error message
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[3]|matches[password]');
		
		if($this->form_validation->run() == FALSE) {

			$data = array(

				'errors' => validation_errors()



				);
			$this->session->set_flashdata($data);//one time data save in session and will be automatically cleared in next request.
			redirect('home');// this function means come back to home controller.
		} else {

			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$user_id = $this->user_model->login_user($username, $password);



			if($user_id) { //if we find this user

				$user_data = array(


					'user_id' => $user_id,
					'username' => $username,
					'logged_in' => true


					);

				$this->session->set_userdata($user_data);

				$this->session->set_flashdata('login_success', 'You are now logged in' );
				
				redirect('home');

				// $data['main_view'] = "users/admin_view";

				// $this->load->view('layouts/main', $data);

			} else {

				$this->session->set_flashdata('login_failed', 'Sorry, you are not logged in' ); //pass the second parameter to the first parameter
				redirect('home');


			}

		}

	}



	public function logout() {

		$this->session->sess_destroy();

		redirect('home');

	}

}


?>