<?php

Class Offdays_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }
  
  function insert(){
    $data = $this->input->post();
    $query = $this->db->insert('offdays', $data);
    if($query != 0){ return true; }else{ return false; }
  }
  function update(){
    $data = $this->input->post();
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('offdays', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('offdays');
    $this->db->where_not_in('id', $this->input->post('id'));
    if($field == 'Category name'){
      $this->db->where('name', $this->input->post('name'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  
  function delete($id)
  {
    if(!empty($id))
    {
        $this->db->where('id', $id);
        $this->db->delete('offdays'); 
        return true;
    }else{
        return false;
    }   
  }

  

   function getOffdaysList($id = NULL){
    $this->db->SELECT('*');
    $this->db->FROM('offdays');
    if($id != NULL){ 
      $this->db->WHERE('id', $id);
    }
    $this->db->order_by('created_at','desc');    
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

    function getOffdaysRequestList()
    {
      $this->db->SELECT('ofr.*, us.first_name, us.last_name, us.phone');
      $this->db->FROM('offdays_request as ofr');
      $this->db-> join('users us', 'us.id =  ofr.emp_user_id','LEFT');
      
      $this->db->order_by('created_at','desc');    
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }

    public function getOffDayUser($id)
    {
      
      $this->db->SELECT('emp_user_id, date,');
      $this->db->FROM('offdays_request');
      $this->db->WHERE('id', $id);
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->row(); }else{ return false;  }
    }

    function deleteAttedance($where = null)
    { 
      if($where != null){
        $this->db->where($where);
        $this->db->delete('employee_punch_details'); 
        //echo $this->db->last_query();
        return true;    
      }else{
        return false;    
      }
      
    }
}