<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class VerifyLogin extends CI_Controller {
 
 function __construct()
 {
   parent::__construct();
   $this->load->model('user','',TRUE);
 }
 
 function index()
 {
    $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
     
    
   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.  User redirected to login page
     //$this->load->view('login_view');
   }
   else
   {
     //Go to private area
     redirect('HomeView', 'refresh');
   }
 }
 }