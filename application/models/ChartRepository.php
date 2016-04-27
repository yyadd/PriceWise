<?php

class ChartRepository extends CI_Model {


	public function get_price($product_id) {

		$sql = "SELECT `Day1`, `Day2`, `Day3`, `Day4`, `Day5`, `Day6`, `Day7`, `Day8`, `Day9`, `Day10`, `Day11`, `Day12`, `Day13`, `Day14`, `Day15` FROM price_trends WHERE product_id = ?";
                
		$query = $this->db->query($sql,$product_id);
		return $query->result(); 

	}
        
        public function get_trend($product_id){
            $myfile = file(base_url()."price_trends/".$product_id.".txt");
            
            return $myfile;
        }
        
        public function update_trend_repository($product_id,$current_price){
            $myfile = fopen(base_url()."price_trends/".$product_id.".txt", "w");
            fwrite($myfile, $current_price);
            fclose($myfile);
        }
        
	public function get_price_d($day) {


	//	$config['hostname'] = "localhost";
	//	$config['username'] = "root";
	//	$config['password'] = "root";
	//	$config['database'] = "errand_db";



	//	$connection = $this->load->database($config);

		// get all user's information
		//$this->db->where('id', $user_id);
		$this->db->where([
			
			'day' => $day,

			]);

		$query = $this->db->get('googlechart');

		return $query->result();
	}

}
?>