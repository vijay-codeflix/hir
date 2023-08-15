<?php

Class Settings_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('currencies');
    $this->db->where_not_in('id', $this->input->post('id'));
    if($field == 'Currency name'){
      $this->db->where('name', $this->input->post('name'));
    }else if($field == 'Currency symbol'){
      $this->db->where('symbol', $this->input->post('symbol'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }
  
  function delete($id = NULL){
    if($id != NULL)
    {
        $this->db->where('id', $id);
        $this->db->delete('currencies'); 
        return true;
    }else{
        return false;
    }  
  }

  function getSitesetting_list(){
    $utype = $this->session->userdata['logged_in']['usertype'];
    $this->db->select('*');
    $this->db->from('site_setting');
    if($utype == 'Admin'){
      $this->db->limit(3,2);
    }
    $query = $this->db->get();
    return json_decode(json_encode($query->result()), true);
  }

    function getAppVersionSetting(){
        $this->db->SELECT('*', true);
		$this->db->FROM('app_version');
		$this->db->limit(1);
		$this->db->order_by('created_at', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$result_Data = $query->result();
			return (array)$result_Data[0];
		}else{
		    return false;
		}
    }
  function UpdateSitesetting($data){
    
    $update_key = array_keys($data);
    foreach ($update_key as $key => $value) {
      $this->db->where('setting_name', $value);
      $query = $this->db->update('site_setting', array('setting_value'=> $data[$value])); 
    }    
    if($query != 0){ 
      return true; 
    }else{ 
      return false; 
    }
  }
  function updateApp_setting($insert_data){
    $query = $this->db->insert('app_version',$insert_data);
    if($this->db->insert_id()){
      return $this->db->insert_id();
    }else{
      return false;
    }
  }
}