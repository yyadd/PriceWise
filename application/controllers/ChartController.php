<?php

class ChartController extends CI_Controller{
    
    public function index(){

        $product_id = $_POST['product_id'];
        //$product_id = 'B00DIUGW6A';
        $this->load->model('ChartRepository');
        $chartReporitory = new ChartRepository();
       	$chart_data = $chartReporitory->get_trend($product_id);
        
        
        echo json_encode($chart_data);
    }
    
    
}
?>

