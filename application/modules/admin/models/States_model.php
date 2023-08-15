<?php

Class States_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }
  
  function getStatesList()
  {
    $this->db->SELECT('STA.state_id, STA.state_name, STA.zone_id, STA.created_at,ZO.zone_name, COU.country_name');
    $this->db->FROM('states STA');
    $this->db->JOIN('zones ZO','ZO.zone_id = STA.zone_id','LEFT');
    $this->db->JOIN('countries COU','COU.country_id = STA.country_id','LEFT');
    $this->db->WHERE('STA.is_deleted',0);    
    $this->db->order_by('STA.created_at', 'desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }
  
  function insert(){
    $data = $this->input->post();
    $query = $this->db->insert('states', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function getStatesDetails($id)
  {
    $this->db->SELECT('STA.state_id, STA.state_name, STA.zone_id, STA.created_at,ZO.zone_name, COU.country_name, STA.country_id');
    $this->db->FROM('states STA');
    $this->db->JOIN('zones ZO','ZO.zone_id = STA.zone_id','LEFT');
    $this->db->JOIN('countries COU','COU.country_id = STA.country_id');
    $this->db->WHERE('STA.state_id',$id);    
    $query = $this->db->get();
    //echo $this->db->last_query();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

  function getCountriesListByZone($id)
  {
    $this->db->SELECT('COU.country_id, COU.country_name, COU.zone_id');
    $this->db->FROM('countries COU');
    $this->db->WHERE('COU.zone_id',$id);    
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }


  function getZonesListByCountry($id)
  {
    $this->db->SELECT('ZO.zone_id, ZO.zone_name, ZO.country_id');
    $this->db->FROM('zones ZO');
    $this->db->WHERE('ZO.country_id',$id);    
    $this->db->WHERE('ZO.is_deleted',0);    
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

  function getStatesListByZone($id)
  {
    $this->db->SELECT('state_id, state_name, zone_id');
    $this->db->FROM('states');
    $this->db->WHERE('zone_id',$id);    
    $this->db->WHERE('is_deleted',0);    
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }



  
  function update(){
    $data = $this->input->post();
    $this->db->where('state_id', $this->input->post('state_id'));
    $query = $this->db->update('states', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('states');
    $this->db->where_not_in('state_id', $this->input->post('state_id'));
    if($field == 'State name'){
      $this->db->where('state_name', $this->input->post('state_name'));
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
        // $this->db->delete('states'); 
        // return true;
        
        $this->db->where('state_id', $id);
      $param = array('is_deleted'=> 1);
      $query = $this->db->update('states', $param);
      return true;
    }else{
        return false;
    }   
  }

 
}