<?php

class AlertController extends CI_Controller{
    
    public function index(){
        
        $config = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'ssl://smtp.gmail.com',
      'smtp_port' => 465,
      'smtp_user' => 'mail.ishmeet@gmail.com',
      'smtp_pass' => '9999233601',
    );


    $this->load->library('email', $config);
            

            $this->email->from('mail.ishmeet@gmail.com', 'Your Name');
            $this->email->to('ishmeetsingh.mail@gmail.com');
            //$this->email->cc('another@another-example.com');
            //$this->email->bcc('them@their-example.com');

            $this->email->subject('Email test');
            $this->email->message('Testing the email class.');

             if($this->email->send())
     {
     echo 'Your email was sent.';
     }
     else
     {
     show_error($this->email->print_debugger());
     }
    
        
    }
}
