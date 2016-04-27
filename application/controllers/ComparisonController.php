<?php

class ComparisonController extends CI_Controller{

     public function index(){
         
     }
    public function save_comparison(){
        //$email = $_POST['username'];
        //$productInfo = $_POST['prod_info'];
        
        $data = array (	 
				'username' => $this->input->post('username'),
                                'product_info' => $this->input->post('prod_info'),
                                'comparison_name' => $this->input->post('comparison_name')
                      );
                        $this->load->model('ComparisonRepository');
                        $ComparisonRepository = new ComparisonRepository();
			$rows = $ComparisonRepository->saveComparison($data);
                        echo json_encode($rows);        
    }
    
    public function getMyComparisons(){
        $username= $_POST['username'];
        $this->load->model('ComparisonRepository');
        $ComparisonRepository = new ComparisonRepository();
        $list = $ComparisonRepository->getComparisons($username);
        echo json_encode($list);
        
        
    }
    
    
    
    
    
}

