<?php

Class Messages_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }
  
  function insert(){
    $data = $this->input->post();
    $query = $this->db->insert('messages', $data);
    if($query != 0){ return true; }else{ return false; }
  }
  function update(){
    $data = $this->input->post();

    //print_r($data);
    $data['punch_in_date'] = date("Y-m-d H:i:s", strtotime($data['punch_in_date']));
    $data['punch_out_date'] = date("Y-m-d H:i:s", strtotime($data['punch_out_date']));
    // print_r($data);
    // exit;
    $this->db->where('message_id', 1);
    $query = $this->db->update('messages', $data);
    // echo $this->db->last_query();
    // exit;
    if($query != 0){ return true; }else{ return false; }
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('messages');
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
        $this->db->delete('messages'); 
        return true;
    }else{
        return false;
    }   
  }

  

   function getMessagesList($id = NULL){
    $this->db->SELECT('*');
    $this->db->FROM('messages');
    if($id != NULL){ 
      $this->db->WHERE('message_id', $id);
    }
    $this->db->order_by('created_at');    
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

    function getMessagesRequestList()
    {
      $this->db->SELECT('ofr.*, us.first_name, us.last_name, us.phone');
      $this->db->FROM('messages_request as ofr');
      $this->db-> join('users us', 'us.id =  ofr.emp_user_id','LEFT');
      
      $this->db->order_by('created_at');    
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }

    public function getOffDayUser($id)
    {
      
      $this->db->SELECT('emp_user_id, date,');
      $this->db->FROM('messages_request');
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

    public function checkDayOff()
    {
        $this->db->select('title, date, type');
        $this->db->from('offdays');
        //$this->db->where('date', date('Y-m-d'));
        $query = $this->db->get();
        $data = $query->result();
        $month = date('Y-m');
        $lastDay = date('t',strtotime('today'));
        
        foreach ($data as $key => $value) {
            $start_date = $value->date;
            $output_format = 'Ymd';
            $start = strtotime($start_date);
            
            $event_repetition_type = $value->type;
            switch ($event_repetition_type) {
                case "1":
                    $interval_days = 7;
                    break;
                case "2":
                    $interval_days = date("t");
                    break;
                case "3":
                    $interval_days = 365;
                    break;
                default:
                    $interval_days = "none";
            }

            $end = strtotime(date('Y-m-d'));
            if($interval_days !== "none"){
                if($interval_days == 7 ){
                    if(date("w",$end) === date("w",$start)){
                        return true;
                    }
                }elseif($interval_days == date("t")){
                    if(date('d', $end) === date('d', $start)){
                        return true;
                    }
                }elseif($interval_days == 365){
                    if(date('m-d', $end) === date('m-d', $start)){
                        return true;
                    }
                }
            }else{
                if(date('Y-m-d', $end) === date('Y-m-d', $start)){
                    return true;
                }   
            }
        }

        return false;
        
    }
}