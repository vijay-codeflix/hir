<?php

Class Zones_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }
  
  function getZonesList()
  {
    $this->db->SELECT('ZO.zone_id, ZO.zone_name, CO.country_id, ZO.created_at,CO.country_name');
    $this->db->FROM('zones ZO');
    $this->db->JOIN('countries CO','CO.country_id = ZO.country_id','LEFT');
    $this->db->WHERE('ZO.is_deleted',0);    
    $this->db->order_by('ZO.created_at', 'desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }
  
  function insert(){
    $data = $this->input->post();
    $query = $this->db->insert('zones', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function getZonesDetails($id)
  {
    $this->db->SELECT('ZO.zone_id, ZO.zone_name, CO.country_id, ZO.created_at,CO.country_name');
    $this->db->FROM('zones ZO');
    $this->db->JOIN('countries CO','CO.country_id = ZO.country_id','LEFT');
    $this->db->WHERE('ZO.zone_id',$id);    
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

  function update(){
    $data = $this->input->post();
    $this->db->where('zone_id', $this->input->post('zone_id'));
    $query = $this->db->update('zones', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('zones');
    $this->db->where_not_in('zone_id', $this->input->post('zone_id'));
    if($field == 'Country name'){
      $this->db->where('zone_name', $this->input->post('zone_name'));
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
        $this->db->delete('categories'); 
        return true;
    }else{
        return false;
    }   
  }
  
  function delete_zone($id)
  {
    if(!empty($id))
    {
      $this->db->where('zone_id', $id);
      $param = array('is_deleted'=> 1);
      $query = $this->db->update('zones', $param);
      return true;
    }else{
        return false;
    }   
  }

 
}