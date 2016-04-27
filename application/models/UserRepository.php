<?php

class UserRepository extends CI_Model{
    
    
  public function getData(){
      $query = $this->db->get('user');
      return $query->result();
    }  
    
  public function login($email, $password){
     
        $this->load->model('User');
        $this->db->select('id,email,password,first_name,last_name');
        $this->db->from('user');
        $this->db->where('email',$email);
        $this->db->where('password',$password);
        $query = $this->db->get();
        
        if($query->num_rows() == 1){
            
            return $query->result();
          
        }else{
            return false;
        }
        
    }
    
    public function registerUser($email , $password, $firstName, $lastName){
        
        $this->db->select('email');
        $this->db->from('user');
        $this->db->where('email',$email);
        $q = $this->db->get();
        
        if($q->num_rows() == 1){    
            return false;
        }else{
            $sql = "INSERT INTO user SET email = ?,password = ?,first_name = ?, last_name = ?";
            $query = $this->db->query($sql, array($email , $password, $firstName, $lastName));
            return $query;
        }
        
    }
    public function getUser($email){
        $this->load->model('User');
        $this->db->select('email,first_name,last_name');
        $this->db->from('user');
        $this->db->where('email',$email);
        $query = $this->db->get();
        
        if($query->num_rows() == 1){
            
            return $query->result();
          
        }else{
            return false;
        }
    }
   
}
