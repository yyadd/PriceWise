<?php

class UserController extends CI_Controller{
    
    public function index(){
       // $this->load->database();
        $this->load->model('UserRepository');
        //echo(phpinfo());
        $UserRepository = new UserRepository();
        $results = $UserRepository->getData();
        var_dump($results);
        
    }
    
    
    public function login(){
        
        $this->load->model('User');
        $email = $_POST['username'];
        $password = $_POST['password'];
        
        
        $this->load->model('UserRepository');
        $UserRepository = new UserRepository();
        $result = $UserRepository->login($email,$password);
        
        if($result != false){
        $email = $result[0]->email;
        $firstName = $result[0]->first_name;
        $lastName = $result[0]->last_name;
        
        $user = new User();
        
        $loggedInUser = $user->CreateUser($email, $firstName, $lastName);
        
        $sess_array = array();
        foreach($result as $row)
        {
            $sess_array = array(
                'id' => $row->id,
                'username' => $row->email,
                'firstName'=> $row->first_name,
                'lastName' =>  $row->last_name,
            );
            //var_dump($sess_array);
           $this->session->set_userdata('logged_in', $sess_array);
           
        }
      
     echo json_encode($loggedInUser);
   }
        else 
            echo FALSE;
        
        
       
        
        //$user = new User();
        
        //$loggedInUser = $user->CreateUser($email, $firstName, $lastName);
        
       // echo json_encode($loggedInUser);
        
        
        //$this->load->view('HomeView',$loggedInUser);
        
    }
    
    public function verifyLogin(){
        
    }
    
    public function registerUser(){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        
        $this->load->model('UserRepository');
        $UserRepository = new UserRepository();
        $result = $UserRepository->registerUser($email , $password, $firstName, $lastName);
        echo json_encode($result);
        
        
    }
    
    
    
}