<?php

class ComparisonRepository extends CI_Model{
    
    
    
    public function saveComparison($data){
        $this->db->insert('comparsions', $data); 
     
     return $this->db->affected_rows();
    }
    public function getComparisons($username){
        $this->db->select('*');
        $this->db->from('comparsions');
        $this->db->where('username',$username);
        $query = $this->db->get();
        if($query->num_rows() >= 1){
            
            return $query->result();
          
        }else{
            return false;
        }
  }
    
}