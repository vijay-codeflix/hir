<?php

Class City_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }

  function insert(){
    $data = $this->input->post();
    $query = $this->db->insert('city_grades',$data);

    if($this->db->insert_id()){
      return true;
    }else{
      return false;
    }
  }
  function getCityById($id = NULL){
    if($id){
      $this->db->SELECT('*');
      $this->db->FROM('city_grades');
      $this->db->WHERE('id', $id);
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else{
      return false;
    }
  }

  function update(){
    $data = $this->input->post();
    $passwordValidate = $this->input->post('passwordValidate');
    if($passwordValidate){ 
      $data['password'] = md5($this->input->post('password')); 
    }else{
      unset($data['password']);
    }
    unset($data['user_type']);
    unset($data['email']);
    unset($data['passwordValidate']);
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('city_grades', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function delete($id)
  {
    if(!empty($id))
    {
        $this->db->where('id', $id);
        $this->db->delete('city_grades'); 
        return true;
    }else{
        return false;
    }   
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('city_grades');
    $this->db->where_not_in('id', $this->input->post('id'));
    if($field == 'City name'){
      $this->db->where('city_name', $this->input->post('city_name'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }
}