<?php

Class Dealers_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }
  
  function insert(){
    $data = $this->input->post();
    $query = $this->db->insert('dealer_categories', $data);
    if($query != 0){ return true; }else{ return false; }
  }
  function update(){
    $data = $this->input->post();
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('dealer_categories', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function delete($id)
  {
    if(!empty($id))
    {
        $this->db->where('id', $id);
        $this->db->update('dealer_categories', ['is_deleted' => 1]); 
        return true;
    }else{
        return false;
    }   
  }

  function checkDuplicatedealercategories($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('dealer_categories');
    $this->db->where_not_in('id', $this->input->post('id'));
    if($field == 'name'){
      $this->db->where('name', $this->input->post('name'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  function getDealerCategoriesDetails($id){
    $this->db->SELECT('*');
    $this->db->FROM('dealer_categories');
    $this->db->WHERE('id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  } 

  function getDealerCategoriesList(){
    $this->db->SELECT('*');
    $this->db->FROM('dealer_categories');
    $this->db->WHERE('is_deleted', 0);
    $this->db->order_by('created_at', 'desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

  // Dealer Type 
  function DealerTypeinsert(){
    $data = $this->input->post();
    $query = $this->db->insert('dealer_types', $data);
    if($query != 0){ return true; }else{ return false; }
  }
  
  function DealerTypeupdate(){
    $data = $this->input->post();
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('dealer_types', $data);
    if($query != 0){ return true; }else{ return false; }
  }
  
  function DealerTypedelete($id)
  {
    if(!empty($id))
    {
        $this->db->where('id', $id);
        $this->db->update('dealer_types', ['is_deleted' => 1]); 
        return true;
    }else{
        return false;
    }   
  }

  function checkDuplicatedealertypes($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('dealer_types');
    $this->db->where_not_in('id', $this->input->post('id'));
    if($field == 'name'){
      $this->db->where('name', $this->input->post('name'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  function getDealerTypeDetails($id){
    $this->db->SELECT('*');
    $this->db->FROM('dealer_types');
    $this->db->WHERE('id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  } 

  function getDealerTypeList(){
    $this->db->SELECT('*');
    $this->db->FROM('dealer_types');
    $this->db->WHERE('is_deleted', 0);
    $this->db->order_by('created_at', 'desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

}