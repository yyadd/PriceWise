<?php

class EmailController extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

Public function index() {
		$config = Array(		
		    'protocol' => 'smtp',
		    'smtp_host' => 'ssl://smtp.googlemail.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'PriceWisePW@gmail.com',
		    'smtp_pass' => 'pricewise1234',
		    'smtp_timeout' => '4',
		    'mailtype'  => 'text', 
		    'charset'   => 'iso-8859-1'
		);

		$this->load->library('email', $config);
		
		$this->email->set_newline("\r\n");

		$this->email->from('PriceWisePW@gmail.com','PriceWise');
		//user email
		$this->email->to('tushar_chutani@yahoo.com>');
		//mail subject
		$this->email->subject('this is a test');
		//mail contents
		$this->email->message('it is working');

		if($this->email->send()) {
			echo 'good';
		}
		else {
			show_error($this->email->print_debugger());
		}
	}
}
?>