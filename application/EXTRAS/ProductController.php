<?php 



class ProductController extends CI_Controller {

	public function __construct() {

	parent::__construct();

}

	public function display($user_id) {


		$data['product_data'] = $this->product_model->get_product($user_id);

	}

	public function add() {

			$data = array (

				// 'product_user_id' => $this->input->post('product_user_id'),
				// 'product_id' => $this->input->post('product_id'),
				// 'product_title' => $this->input->post('product_title'),
				// 'link' => $this->input->post('link'),
				// 'website' => $this->input->post('website'),
				// 'current_price' => $this->input->post('current_price')

				'product_user_id' => 'yuyan',
				'product_id' => 'ishmeet',
				'product_title' => 'PS4_500G',
				'link' => 'www.amazon.ca',
				'website' => 'www.google.com',
				'current_price' => '322.7'
			);

			$this->product_model->add_product($data);

	}


	public function delete($product_id) {


		$this->product_model->delete_product($product_id);


	}

}

?>