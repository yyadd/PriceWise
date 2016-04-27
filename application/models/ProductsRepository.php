<?php

class ProductsRepository extends CI_Model{
    

  
  public function getProducts(){
        $this->db->select('product_id,website,current_price,username,alert');
        $this->db->from('product');
        $query = $this->db->get();
        if($query->num_rows() >= 1){
            
            return $query->result();
          
        }else{
            return false;
        }
  }
  
  public function updateProduct($product_id,$new_Price){
            $sql = "UPDATE product SET current_price = ? WHERE product_id = ?";
            $query = $this->db->query($sql, array($new_Price , $product_id));
            
  }
  
}