<?php

class WishlistRepository extends CI_Model{
    
    
  
    
  public function addToWishlist($data){
        
     $this->db->insert('product', $data); 
     
     return $this->db->affected_rows();
  }
  
  public function getWishlist($username){
        $this->db->select('product_id,website,title,link,current_price');
        $this->db->from('product');
        $this->db->where('username',$username);
        $query = $this->db->get();
        if($query->num_rows() >= 1){
            
            return $query->result();
          
        }else{
            return false;
        }
  }
  
  public function removeFromWishlist($product_id,$username){
        
        //$this->db->where('username',$username);
        //$this->db->where('product_id',$product_id);
        //$this->db->delete('product');
        
        $sql = "DELETE FROM product WHERE username = ? AND product_id = ?";
        $query = $this->db->query($sql, array($username , $product_id));
        //$query = $this->db->get();
        //if($query->num_rows() >= 1){
            
           //return $this->db->affected_rows();
          
        //}else{
        //    return false;
        //}
  } 
   
  public function toggleWishlistAlert($product_id,$username){
        
        $this->db->select('alert');
        $this->db->from('product');
        $this->db->where('username',$username);
        $this->db->where('product_id',$product_id);
        $query = $this->db->get();
        //echo($query->result()[0]->alert);
        
        if($query->result()[0]->alert == 1){
            //echo($query->result()[0]->alert);
            $sql = "UPDATE product SET alert = 0 WHERE username = ? AND product_id = ?";
            $q = $this->db->query($sql, array($username , $product_id));
            return 0;
          
        }else if($query->result()[0]->alert == 0){
           // echo($query->result()[0]->alert);
            $sql = "UPDATE product SET alert = 1 WHERE username = ? AND product_id = ?";
            $q = $this->db->query($sql, array($username , $product_id));
            return 1;
        }
        
      
       
  }
  
  public function checkWishlistAlert($product_id,$username){
        $this->db->select('alert');
        $this->db->from('product');
        $this->db->where('username',$username);
        $this->db->where('product_id',$product_id);
        $query = $this->db->get();
        return $query->result();
  }
  
  public function checkProductInWishlist($product_id,$username){
        $this->db->select('product_id');
        $this->db->from('product');
        $this->db->where('username',$username);
        $this->db->where('product_id',$product_id);
        $query = $this->db->get();
        return $query->result();
  }
  
}
