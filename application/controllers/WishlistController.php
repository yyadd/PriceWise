<?php 



class WishlistController extends CI_Controller {

	public function __construct() {

	parent::__construct();

}

	public function getWishlist() {

                $username = $this->input->post('username');
                        
		$this->load->model('WishlistRepository');
                $wishlistRepository = new WishlistRepository();
                $wishList = $wishlistRepository->getWishlist($username);
                
                echo json_encode($wishList);

	}

	public function add() {

			$data = array (

				 
				 'product_id' => $this->input->post('product_id'),
                                 'website' => $this->input->post('website'),
				 'title' => $this->input->post('product_title'),
				 'link' => $this->input->post('link'),
				 'current_price' => $this->input->post('current_price'),
                                 'username' => $this->input->post('product_user_id')

				
			);
                        //var_dump($data);
                        $this->load->model('WishlistRepository');
                        $wishlistRepository = new WishlistRepository();
			$rows = $wishlistRepository->addToWishlist($data);
                        echo json_encode($rows);
	}


	public function delete() {

                $username = $this->input->post('username');
                $product_id = $this->input->post('product_id');
                
                $this->load->model('WishlistRepository');
                $wishlistRepository = new WishlistRepository();
		$rows = $wishlistRepository->removeFromWishlist($product_id,$username);
                echo json_encode($rows);


	}
        
        public function toggleAlert() {

                $username = $this->input->post('username');
                $product_id = $this->input->post('product_id');
                
                $this->load->model('WishlistRepository');
                $wishlistRepository = new WishlistRepository();
		$rows = $wishlistRepository->toggleWishlistAlert($product_id,$username);
                echo json_encode($rows);


	}
        
        public function checkAlert(){
                $username = $this->input->post('username');
                $product_id = $this->input->post('product_id');
                $this->load->model('WishlistRepository');
                $wishlistRepository = new WishlistRepository();
		$alert = $wishlistRepository->checkWishlistAlert($product_id,$username);
                echo json_encode($alert);
        }
        
        public function checkInWishlist(){
            $prodIds = $this->input->post('prodIds');
            $username = $this->input->post('username');
            $prodIds = json_decode($prodIds);
            $prodsInWishlist = array();
            
            $this->load->model('WishlistRepository');
            $wishlistRepository = new WishlistRepository();
            foreach($prodIds as $prodId){
                $res = $wishlistRepository->checkProductInWishlist($prodId,$username);
                if(sizeof($res) !=0){
                    array_push($prodsInWishlist, $res[0]->product_id);
                }
            }
            echo json_encode($prodsInWishlist);
        } 

}

?>