<?php
session_start();
class HomeController extends CI_Controller{
    function __construct()
 {
   parent::__construct();
 }
    
    public function index(){
      
   if($this->session->userdata('logged_in'))
   {
     $session_data = $this->session->userdata('logged_in');
     $data['username'] = $session_data['username'];
     $data['firstName'] = $session_data['firstName'];
     $data['lastName'] = $session_data['lastName'];
     $this->load->view('LoggedInView', $data);
   }
   else  
      $this->load->view('HomeView');
    }
    
    function logout()
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('HomeController', 'refresh');
    }
 
    
    
    
    
}


