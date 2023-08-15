<?php

Class Expenses_model extends MY_Model
{
 
  function __construct(){
	 parent::__construct();
  }
  
  function updateExpenseAction($data, $id){
    $this->db->where('id', $id);
    //$this->db->where('status', 'pending');
    $query = $this->db->update('expenses', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  //fetching DealerCategories for Dropdown 
  function getDealerCategories(){
    $query = $this->db->select('id, name as categoryName')->where(['status' => 1])->get('dealer_categories');
    if($query->num_rows() != 0){ return $query->result(); }else{ return false; }
  }

  //fetching DealerTypes for Dropdown
  function getDealerTypes(){
    $query = $this->db->select('id, name as typeName')->where(['status' => 1])->get('dealer_types');
    if($query->num_rows() != 0){ return $query->result(); }else{ return false; }
  }
  function insert(){
    $data = $this->input->post();
    // pr($data, 1);exit;
    $query = $this->db->insert('categories', $data);
    if($query != 0){ return true; }else{ return false; }
  }
  function update(){
    $data = $this->input->post();
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('categories', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('categories');
    $this->db->where_not_in('id', $this->input->post('id'));
    if($field == 'Category name'){
      $this->db->where('name', $this->input->post('name'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  function getExpenseItem($id){
    $this->db->SELECT('requested_amount,employee_id');
    $this->db->FROM('expenses');
    $this->db->WHERE('id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  } 

  function getExpenseItemByCategory($id){
    $this->db->SELECT('*');
    $this->db->FROM('expenses');
    $this->db->WHERE('category_id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

  function getEmpGradeByCat($id){
    $this->db->SELECT('*');
    $this->db->FROM('employee_grade_details');
    $this->db->WHERE('category_id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }

  function getExpensesByUserIds($users = NULL, $id = NULL){
    $this->db->SELECT('approved_user_id as uid, status, approved_amount, reason');
    $this->db->FROM('expenses_approved_process');
    $this->db->WHERE('expenses_id', $id);
    $this->db->where_in('approved_user_id', $users);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return false;
    }
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
  
  function expense_category_delete($id)
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

  function getSubadmin($user_id){


    // $data = $this->db->query("CALL `user_check_expenses_approved`(28, 29, @approv_all2, @approved_user_parent_id);");
    // $result = $data->result();
    // print_r($result);
    // $this->db->close();
    // $this->db->query(" SELECT @parent_id, @approved_user_parent_id; ");
    // $result = $data->result();
    // print_r($result);
    // exit;
    // if($result['all_subadmin'] != NULL){
    //   return $user_id.','.$result['all_subadmin'];
    // }else{
    //   return $user_id;
    // }


    $data = $this->db->query("CALL get_all_subadmin($user_id)");
    $result = $data->row_array();
    $this->db->close();
    if($result['all_subadmin'] != NULL){
      return $user_id.','.$result['all_subadmin'];
    }else{
      return $user_id;
    }

    /*DELIMITER $$

      DROP PROCEDURE IF EXISTS `get_all_subadmin` $$
      CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_subadmin`( IN loc_user_id int(11) )
      BEGIN

       SELECT GROUP_CONCAT(lv SEPARATOR ',') as all_subadmin FROM (SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR ',') FROM users WHERE parent_id IN (@pv)) AS lv FROM users JOIN (SELECT @pv:=loc_user_id)tmp WHERE parent_id IN (@pv)) a;

      END $$

      DELIMITER ;

      DELIMITER $$

      DROP PROCEDURE IF EXISTS `get_all_subadmin` $$
      CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_subadmin`( IN loc_user_id int(11) )
      BEGIN

       select  GROUP_CONCAT(id SEPARATOR ',') as all_subadmin  from  (select * from users order by parent_id, id) users,(select @pv := loc_user_id) initialisation where   find_in_set(parent_id, @pv) > 0 and  @pv := concat(@pv, ',', id);

      END $$

      DELIMITER ;


      

*/
    
  }

  function getEmployee($employee_ids){
    
    //$employee_ids = explode(',', $employee_ids);
    // $this->db->SELECT('id,user_type,parent_id,first_name,last_name,status');
    // $this->db->FROM('users');
    // $this->db->WHERE_IN('parent_id', $employee_ids);
    // $this->db->WHERE('user_type', 4);
    // $query = $this->db->get();
    // echo $this->db->last_query();
    $sql = "SELECT `id`, `user_type`, `parent_id`, `first_name`, `last_name`, `status` FROM `users` WHERE `parent_id` IN (".$employee_ids.") AND `user_type` = 4";
    $query = $this->db->query($sql); 
    $this->db->close();
    if($query->num_rows() > 0){ 
      return $query->result();       
    }
  }

   function getExpenseList_subadmin($id=NULL, $empId = NULL, $catId = NULL, $status = NULL, $startDate = NULL, $endDate = NULL, $sub_admin_ids = NULL ){
    $expImage = EXPENSES_IMAGE;
     //Employee name, Employee phone, category name, Requested Amount, Approved Amount, Place (Grade), status, created
     $this->db->SELECT('EP.id, EP.employee_id as empId, CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone,EGD.amount as allowAmount, EG.grade as empGrade, CAT.name as catName, CAT.type as categoryType, CAT.id as catId, EP.requested_amount as reqAmount,EP.approved_amount as approveAmount, EP.reason, CT.city_name as place, CT.grade as ctGrade, EP.status,EP.created_at, DATE_FORMAT(EP.created_at,"%d/%m/%Y %H:%i:%s %p") as reqDate, CONCAT("'.$expImage.'large/",EP.expense_photo) as expLargeImg, CONCAT("'.$expImage.'thumb/",EP.expense_photo) as expThumbImg, EP.expense_details, (SELECT approved_user_parent_id FROM expenses_approved_process WHERE expenses_id = EP.id ORDER BY created_date DESC limit 1) as allow_approved, USR.parent_id, (SELECT count(id) FROM expenses_approved_process WHERE expenses_id = EP.id AND approved_user_id = "'.$this->session->userdata['logged_in']['userid'].'") as login_user_approved, EP.currency_symbol, EP.currency_name', false);
     $this->db->FROM('expenses EP');
     $this->db->JOIN('users USR','EP.employee_id = USR.id','LEFT');
     $this->db->JOIN('categories CAT','EP.category_id = CAT.id','LEFT');
     $this->db->JOIN('city_grades CT','EP.city_id = CT.id','LEFT');
     $this->db->JOIN('emp_grade EG','USR.grade_id = EG.id','LEFT');
     $this->db->JOIN('employee_grade_details EGD','USR.grade_id = EGD.grade_id && CT.grade = EGD.city_grade && CAT.id = EGD.category_id','LEFT', false);
     //employee
     if($empId != NULL){
       $this->db->where('EP.employee_id', (int)$empId);
     }

     if($id != NULL){
       $this->db->where('EP.id', (int)$id);
     }

     //category
     if($catId != NULL){
       $this->db->where('EP.category_id', (int)$catId);
     }
     
     $this->db->order_by("created_at", "desc");

     //status
     if($status != NULL){
      $status = (int)$status;
       if($status > 0){
        $this->db->where('EP.status', $status);
       }
     }

     if($startDate != NULL){
      $this->db->where('EP.created_at >="'.date('Y-m-d H:i:s',strtotime($startDate)).'"');
     }

     if($endDate != NULL){
       $this->db->where('EP.created_at < "'.date('Y-m-d H:i:s', (strtotime($endDate) + 86340)).'"');
     }
     
     $uType = $this->session->userdata['logged_in']['usertype'];
     if($sub_admin_ids != NULL &&  $empId == NULL && $uType != 'Super Admin'){
       $this->db->where("parent_id IN (".$sub_admin_ids.")");
     }
     
     $query = $this->db->get();
     //echo $this->db->last_query();
     if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }


  function get_user_details($user_id, $user_ids = array(), $user_data = array()){
    $this->db->SELECT('id,user_type,parent_id,first_name,last_name,status');
    $this->db->FROM('users');
    $this->db->WHERE('id', $user_id);
    $this->db->WHERE('user_type', 3);
    $query = $this->db->get();
    $final_user_id = array();
    $final_user_data = array();
    if($query->num_rows() > 0){ 
      $result = $query->result(); 
      foreach ($result as $key => $value) {
          $final_user_id[]=$value->id;
          $final_user_data[]=$value;
          $result =  $this->get_user_details($value->id, array('id','user_type','parent_id','first_name','last_name','status'), 3);          
      }
    }else{ 
      return false;  
    } 
  }

  function get_user_parent_id($user_id){
    $this->db->SELECT('parent_id');
    $this->db->FROM('users');
    $this->db->WHERE('id', $user_id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      $result = $query->row_array(); 
      return $result['parent_id'];
    }
  }

  function expenses_approved_process($params){
    if($params != NULL){
        $query = $this->db->insert('expenses_approved_process', $params);
        if($this->db->insert_id()){
          return true;
        }else{
          return false;
        }
    }else{
        return false;
    }
  }
}