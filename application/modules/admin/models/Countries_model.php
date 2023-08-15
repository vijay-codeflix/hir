<?php

Class Countries_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }
  
  function getCountryList()
  {
    $this->db->SELECT('*');
    $this->db->FROM('countries');
    $this->db->WHERE('is_deleted',0);
    $this->db->order_by('created_at', 'desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }
  
  function insert(){
    $data = $this->input->post();
    $query = $this->db->insert('countries', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function getCountryDetails($id)
  {
    $this->db->SELECT('*');
    $this->db->FROM('countries');
    $this->db->WHERE('country_id',$id);
    $this->db->order_by('created_at', 'desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

  function update(){
    $data = $this->input->post();
    $this->db->where('country_id', $this->input->post('country_id'));
    $query = $this->db->update('countries', array('country_name' => $data['country_name']));
    if($query != 0){ return true; }else{ return false; }
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('countries');
    $this->db->where_not_in('country_id', $this->input->post('country_id'));
    if($field == 'Country name'){
      $this->db->where('country_name', $this->input->post('country_name'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  
  
  function delete($id)
  {
    if(!empty($id))
    {
        // $this->db->where('id', $id);
        // $this->db->delete('categories'); 
        // return true;
        
      $this->db->where('country_id', $id);
      $param = array('is_deleted'=> 1);
      $query = $this->db->update('countries', $param);
      return true;
    
    }else{
        return false;
    }   
  }

 
}