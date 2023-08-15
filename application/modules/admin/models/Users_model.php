<?php

Class Users_model extends MY_Model
{
 
  function __construct(){
   parent::__construct();
  }

  function insert(){
    $data = $this->input->post();
    if(isset($data['password']) && !empty($data['password'])){
      $data['password'] = md5($data['password']);
    }
    
    if($data['parent_id'] == ''){ unset($data['parent_id']); }
    $query = $this->db->insert('users',$data);

    if($this->db->insert_id()){
      return true;
    }else{
      return false;
    }
  }
  
  function checkIsDeletedDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('users');
    $this->db->where('is_deleted', 0);
    if($field == 'Phone'){
      $this->db->where('phone', $this->input->post('phone'));
    }else if($field == 'Email'){
      $this->db->where('email', $this->input->post('email'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    // pr($queryRes);
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  function insertGrade(){
    $grade = $this->input->post('grade');
    $query = $this->db->insert('emp_grade',array('grade' => $grade));

    if($this->db->insert_id()){
      return $this->db->insert_id();
    }else{
      return false;
    }
  }
  
  function insertGradeDetails($param = NULL, $type = NULL){
    if($type == NULL){
      $query = $this->db->insert_batch('employee_grade_details', $param);
    }else{
      $query = $this->db->update_batch('employee_grade_details', $param, 'id');
    }
    if($query){
      return true;
    }else{
      return false;
    }
  }
  
  function insertGradeDetailsSingle($param = NULL){
    $query = $this->db->insert('employee_grade_details', $param);
    if($this->db->insert_id()){
      return true;
    }else{
      return false;
    }
  }
  
  function updateGradeDetails($param = NULL){
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('employee_grade_details', $param);
    if($query != 0){ 
      return true; 
    }else{ 
      return false; 
    }
  }

  function getEmpGradelist(){
    $this->db->SELECT('*');
    $this->db->FROM('emp_grade');
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

  function getGradeById($id = NULL){
    $this->db->SELECT('EG.id as gid, EG.grade, GD.*');
    $this->db->FROM('emp_grade EG');
    $this->db->JOIN('employee_grade_details GD','EG.id = GD.grade_id','LEFT');
    $this->db->WHERE('EG.id', base64_decode($id));
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

  function getuserslist($userid = NULL){
    $user_id = $this->session->userdata['logged_in']['userid'];
    $uType = $this->session->userdata['logged_in']['usertype'];

    $ids = ($uType == 'Super Admin')? array($user_id,1) : array($user_id,1,2);
    $this->db->SELECT('USR.*, UT.type as userType, CONCAT(PRNT.first_name," ",PRNT.last_name) as parent_name');
    $this->db->FROM('users USR');
    $this->db->JOIN('users PRNT', 'USR.parent_id = PRNT.id', 'LEFT');
    $this->db->JOIN('user_types UT', 'USR.user_type = UT.id', 'LEFT');
    $this->db->where_not_in('USR.id', $ids);
    if($userid != NULL){
      $this->db->where('USR.id', base64_decode($userid));
    }
    $this->db->group_by('USR.id');
    $this->db->order_by('USR.first_name', 'desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }


  function getUserType(){
    $this->db->SELECT('*');
    $this->db->FROM('user_types');
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

  function getSubAdmins($ids = NULL){
    $user_id = $this->session->userdata['logged_in']['userid'];

    $this->db->SELECT('*');
    $this->db->FROM('users');
    $this->db->WHERE('user_type',3);
    if($ids != NULL){
      $this->db->where_not_in('id', $ids);
    }
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }


  function getSubAdmins_new($ids = NULL, $new_ids = NULL, $user_type = 3){
    $user_id = $this->session->userdata['logged_in']['userid'];
    $uType = $this->session->userdata['logged_in']['usertype'];
    $sql = "SELECT id,user_type,parent_id,first_name,last_name FROM users WHERE user_type = 3 "; // user_type = $user_type
    // if($ids != NULL){
    //   $sql .= "AND  id NOT IN('".implode(",", $ids)."')";
    // }
    if($new_ids != NULL && $uType !== 'Super Admin'){
      $sql .= " AND (id IN (".$new_ids.") OR id IN (".$user_id."))";
    }else{
      if($uType !== 'Super Admin' ) {
        $sql .= " AND ( parent_id IN (".$user_id.") OR id IN (".$user_id.")) ";  
      }      
    }

  
    $query = $this->db->query($sql); 
    $this->db->close();
    
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

   /*Login user Sub admin Ids*/
  function getSubadminIds($user_id){
    $data = $this->db->query("CALL get_all_sub_admin_ids($user_id);");
    // $data = $this->db->query("select GROUP_CONCAT(id SEPARATOR ',') as all_subadmin from (select * from users order by parent_id, id) users,(select @pv := $user_id) initialisation where (find_in_set(parent_id, @pv) > 0 AND user_type = 3 ) and @pv := concat(@pv, ',', id)");
    $result = $data->row_array();
    $this->db->close();
    if($result['all_subadmin'] != NULL){
      return $result['all_subadmin'];
    }else{
      return null;
    }    
  }
  function getGradeList(){
    $this->db->SELECT('*');
    $this->db->FROM('emp_grade');
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }  
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
    $query = $this->db->update('users', $data);
    if($query != 0){ return true; }else{ return false; }
  }

  function getParentIds($ids = NULL){
      $allIds = array();
      $currentUser = $this->session->userdata['logged_in']['userid'];
      $parentIds = ($ids == NULL)? [$currentUser] : $ids;

      $getList = $this->getParentIdList($ids,$ids);
      
  }

  function getParentIdList(&$ids, $new){
      $parentIds = $new;
      $this->db->SELECT('id');
      $this->db->FROM('users');
      $this->db->where_in('parent_id', $parentIds);
     // $this->db->where('user_type', 4);
      $query = $this->db->get();
      
      if($query->num_rows() > 0){ 
        $response = $query->result();
        if(count($response) > 0){
          $news = array();
          foreach ($response as $key => $value) {
            $news[] = $value->id;
          }
          $this->getParentIdList($ids, $news);
        }else{
          return $ids;
        }
        //return $idsVal;
      }else{ 
        return $ids;
      }
  }

  function getAttendanceList_old($method = NULL){
    //$this->getParentIds();return;
    if($method == 'punch-in'){
      $currentUser = $this->session->userdata['logged_in']['userid'];
      $this->db->SELECT('EPD.*,CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone');
      $this->db->FROM('employee_punch_details EPD');
      $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
      $this->db->where('EPD.punch_out_date', NULL);
      $this->db->where('EPD.punch_in_date', date('Y-m-d'));
      $this->db->order_by('EPD.created_at', 'desc');

      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else if($method == 'punch-out'){
      $this->db->SELECT('EPD.*,CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone');
      $this->db->FROM('employee_punch_details EPD');
      $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
      $this->db->where('EPD.punch_out_date', date('Y-m-d'));
      $this->db->where('EPD.punch_in_date', date('Y-m-d'));
      $this->db->order_by('EPD.created_at', 'desc');

      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else{
      $this->db->SELECT('EPD.*,CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone');
      $this->db->FROM('employee_punch_details EPD');
      $this->db->JOIN('users USR', "EPD.user_id = USR.id", 'RIGHT',false);
      $this->db->where('USR.user_type', 4);
      $this->db->order_by('USR.first_name');

      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }
  }

  function getAttendanceList($method = NULL){
    //$this->getParentIds();return;
    $userId = $this->session->userdata['logged_in']['userid'];
    $uType = $this->session->userdata['logged_in']['usertype'];
    $sud_admin = $this->users_model->getSubUser($userId);
    $sql = '';
    if($uType !== 'Super Admin'){
      $sql = " USR.id IN (".$sud_admin.")";
      $sql2 = " AND users.id IN (".$sud_admin.")";

    }
    $this->db->initialize();
    //$sql = "USR.id IN ('38','113','156','158','157')";
    
    if($method == 'punch-in'){
      $currentUser = $this->session->userdata['logged_in']['userid'];
      $this->db->SELECT('EPD.*,CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone');
      $this->db->FROM('employee_punch_details EPD');
      $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
      $this->db->where('EPD.punch_out_date', NULL);
      $this->db->where('EPD.punch_in_date', date('Y-m-d'));
      if($sql){
        $this->db->where($sql);
      }
      $this->db->order_by('EPD.created_at','desc');

      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else if($method == 'punch-out'){
      $this->db->SELECT('EPD.*,CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone');
      $this->db->FROM('employee_punch_details EPD');
      $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
      $this->db->where('EPD.punch_out_date', date('Y-m-d'));
      $this->db->where('EPD.punch_in_date', date('Y-m-d'));
      if($sql){
        $this->db->where($sql);
      }
      $this->db->order_by('EPD.created_at','desc');

      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else if($method == 'absent'){
      $query =  $this->db->query("SELECT CONCAT(`users`.`first_name`,' ',`users`.`last_name`) as empName,`users`.`phone` FROM `users` WHERE user_type = 4 AND is_deleted = 0 AND `users`.id NOT IN (SELECT `employee_punch_details`.`user_id` FROM employee_punch_details WHERE `employee_punch_details`.`punch_in_date` = CURRENT_DATE() ORDER BY `employee_punch_details`.`created_at` DESC ) ". $sql2);
      // print_r($query->result());
      // exit;
      //$query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else{
      $this->db->SELECT('EPD.*,CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone, EPL.created_at as last_punch');
      $this->db->FROM('employee_punch_details EPD');
      $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
      $this->db->JOIN('employee_punch_logs EPL', 'EPL.user_id = EPD.user_id AND EPL.employee_punch_details_id = EPD.id', 'LEFT');
      $this->db->where('EPD.punch_out_date', NULL);
      $startTime = date('Y-m-d H:i:s');
      $convertedTime = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($startTime)));
      $this->db->where('EPD.punch_in_date', date('Y-m-d'));
      $this->db->where('( EPL.created_at < "'.$convertedTime.'" OR EPL.created_at IS NULL )');
      $this->db->where('USR.is_deleted', '0');
      $this->db->group_by('EPD.user_id');
      $this->db->order_by('EPL.created_at','desc');
      $this->db->order_by('EPD.created_at','desc');
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }
  }

  function delete($id)
  {
    if(!empty($id))
    {
        // $this->db->where('id', $id);
        // $this->db->delete('users'); 
      $this->db->where('id', $id);
      $param = array('is_deleted'=> 1);
      $query = $this->db->update('users', $param);
      return true;
    }else{
        return false;
    }   
  }

  function deleteGrade($id){
    if(!empty($id))
    {
        $this->db->where('id', $id);
        $this->db->delete('emp_grade'); 
        return true;
    }else{
        return false;
    }
  }
  
  function checkDuplicate($val, $field){
    $this->db->select('count(*) as duplicate');
    $this->db->from('users');
    $this->db->where_not_in('id', $this->input->post('id'));
    if($field == 'Employee ID'){
      $this->db->where('emp_id', $this->input->post('emp_id'));
    }else if($field == 'Email'){
      $this->db->where('email', $this->input->post('email'));
    }
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  function checkDuplicateGrade($val, $field){  
      $this->db->select('count(*) as duplicate');
      $this->db->from('emp_grade');
      $this->db->where_not_in('id', $this->input->post('gid'));
      $this->db->where('grade', $this->input->post('grade'));
      $query = $this->db->get();
      $queryRes = $query->result();
      if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
  }

  function getTodaysLiveLocations_old(){
      $this->db->SELECT('CONCAT(USR.first_name," ", USR.last_name) as name, ePunch.current, ePunch.user_id as id');
      $this->db->FROM('employee_punch_details as ePunch');
      $this->db->WHERE('ePunch.punch_in_date', date('Y-m-d'));
      $this->db->WHERE('ePunch.punch_out_date', NULL);
      $this->db->JOIN('users USR', "ePunch.user_id = USR.id", 'LEFT');
    
      $query = $this->db->get();
    
      if($query->num_rows() > 0){ 
          $queryRes = $query->result();
          $newResponse = array();
          foreach ($queryRes as $key => $value) {
            $current = json_decode($value->current);
            $cord = $current->location->coordinates;

            $dateVal = $this->getLocalDateTime($current->date);
            //echo $dt->format('d/m/Y h:i:s A');
            $newResponse[] = array('name' => $value->name,'id'=>  str_replace('/', '_',rtrim(base64_encode($value->id), '=')),'dateTime' => $dateVal, 'lat' => $cord[0], 'long' => $cord[1]);
          }
          return $newResponse;
      }else{ 
        return false;  
      }
  }

  function getTodaysLiveLocations(){
      $user_id = $this->session->userdata['logged_in']['userid'];
      $uType = $this->session->userdata['logged_in']['usertype'];

      $res = $this->getSubUser($user_id);

      // echo $res;
      // exit;

      //$sql = "SELECT CONCAT(`USR`.`first_name`,' ', `USR`.`last_name`) as name, `ePunch`.`current`, `ePunch`.`user_id` as id FROM employee_punch_details as ePunch LEFT JOIN users USR ON  `ePunch`.`user_id` = `USR`.`id` WHERE `ePunch`.`punch_in_date` = '".date('Y-m-d')."' AND `ePunch`.`punch_out_date` IS NULL ";
      $sql = "SELECT CONCAT(`USR`.`first_name`,' ', `USR`.`last_name`) as name, `ePunch`.`current`, `ePunch`.`user_id` as id, `ePunchLog`.`lat`, `ePunchLog`.`lng`, `ePunchLog`.`created_at`,`ePunchLog`.`isGpsOn`, `ePunchLog`.`battery`, `ePunchLog`.`mobile_network` FROM employee_punch_details as ePunch LEFT JOIN users USR ON  `ePunch`.`user_id` = `USR`.`id` LEFT JOIN employee_punch_logs ePunchLog ON `ePunchLog`.`employee_punch_details_id` = `ePunch`.`id` WHERE `ePunch`.`punch_in_date` = '".date('Y-m-d')."' AND `ePunch`.`punch_out_date` IS NULL ";

      $ids = ($uType == 'Super Admin')? array($user_id,1) : array($user_id,1,2);
      if($res != NULL && $uType !== 'Super Admin'){
        $sql .= "AND USR.id IN (".$res.")";
      }else{
        if($uType !== 'Super Admin' ) {
          $sql .= "AND  USR.parent_id IN (".$user_id.")";  
        }      
      }
      $sql .= " GROUP BY `ePunch`.`user_id` ORDER BY `ePunchLog`.`created_at` DESC ";
      $query = $this->db->query($sql);
    //   echo $this->db->last_query();
    //   exit;
      //$result = $data->row_array();
    //   echo $this->db->last_query();
    //   exit;
      // if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }

      
      // $this->db->SELECT('CONCAT(USR.first_name," ", USR.last_name) as name, ePunch.current, ePunch.user_id as id');
      // $this->db->FROM('employee_punch_details as ePunch');
      // $this->db->WHERE('ePunch.punch_in_date', date('Y-m-d'));
      // $this->db->WHERE('ePunch.punch_out_date', NULL);
      // $this->db->JOIN('users USR', "ePunch.user_id = USR.id", 'LEFT');
      
      // $query = $this->db->get();
       //print_r($query->result()); 
      if($query->num_rows() > 0){ 
          $queryRes = $query->result();
          $newResponse = array();
          foreach ($queryRes as $key => $value) {
            $current = json_decode($value->current);
            $cord = $current->location->coordinates;

            //$dateVal = $this->getLocalDateTime($current->date);
            //echo $dt->format('d/m/Y h:i:s A');
            //$newResponse[] = array('name' => $value->name,'id'=>  str_replace('/', '_',rtrim(base64_encode($value->id), '=')),'dateTime' => $dateVal, 'lat' => $cord[0], 'long' => $cord[1]);
            //print_r($value->isGpsOn);
            
            if($value->isGpsOn == null){
                $dateVal = $this->getLocalDateTime($value->created_at);
                $newResponse[] = array('name' => $value->name,'id'=>  str_replace('/', '_',rtrim(base64_encode($value->id), '=')),'dateTime' => $dateVal, 'lat' => $cord[0], 'long' => $cord[1]);
            }else{
                $dateVal = $this->getLocalDateTime($value->created_at);
                $newResponse[] = array('name' => $value->name,'id'=>  str_replace('/', '_',rtrim(base64_encode($value->id), '=')),'dateTime' => $dateVal, 'lat' => $value->lat, 'long' => $value->lng, 'gps' => $value->isGpsOn, 'battery' => $value->battery, 'network' => $value->mobile_network);
            }
          }
          return $newResponse;
      }else{ 
        return false;  
      }
  }

  function getLiveUserRoute($id, $date = null){
    //if($date == null)  $date = date('Y-m-d');
    //$this->db->SELECT('lat,lng,TIME_FORMAT(TIME(created_at),"%h:%i:%s %p") as time');
    $this->db->SELECT('lat,lng,created_at as time');
    $this->db->FROM('employee_punch_logs');
    //$this->db->WHERE('(created_at BETWEEN "'. $date.' 00:00:00" AND "'. $date.' 23:59:59")' );
    $this->db->WHERE('employee_punch_details_id', $id);
    $this->db->order_by('created_at','desc');
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result();
    }else{
      return false;
    }
  }

  function getLiveUserRoutePunchDetails($id){
    // $user_id = $this->session->userdata['logged_in']['userid'];
    // $sud_admin = $this->getSubUser($user_id);
    // $uType = $this->session->userdata['logged_in']['usertype'];
    // $ids = ($uType == 'Super Admin')? array($user_id,1) : array($user_id,1,2);
    // if($sud_admin != NULL && $uType !== 'Super Admin'){
    //   $sql = " USR.id IN (".$sud_admin.")";
    // }elseif($uType !== 'Super Admin' ) {
    //   $sql = " USR.parent_id IN (".$user_id.")";  
    // }else{
    //   if($_POST && $sud_admin != NULL){ 
    //     $sql = " USR.id IN (".$sud_admin.")";
    //   }
    // }

    $this->db->SELECT('punch_in,punch_out');
    $this->db->FROM('employee_punch_details');
    $this->db->WHERE('id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      $data = $query->result();
      
      $punch_in = json_decode($data[0]->punch_in);
      if(!empty(($data[0]->punch_out))){
        $punch_out = json_decode($data[0]->punch_out);
      }else{
        $punch_out = array('location'=> array('coordinates' => array(null, null)),  'time' => '', 'date' => '');
        $punch_out = json_decode(json_encode($punch_out), FALSE); //(object) $punch_out;
      }
      $returnData = array( 'start' => array(
        'lat'=> $punch_in->location->coordinates[0],
        'lng'=> $punch_in->location->coordinates[1],
        'time'=> $punch_in->date),
      'end' => array(
        'lat'=> $punch_out->location->coordinates[0],
        'lng'=> $punch_out->location->coordinates[1],
        'time'=> $punch_out->date ) );
      return $returnData;
    }else{
      return false;
    }
  }

  function employeedetails($userid = NULL){
    $this->db->SELECT('USR.*, UT.type as userType, CONCAT(PRNT.first_name," ",PRNT.last_name) as parent_name');
    $this->db->FROM('users USR');
    $this->db->JOIN('users PRNT', 'USR.parent_id = PRNT.id', 'LEFT');
    $this->db->JOIN('user_types UT', 'USR.user_type = UT.id', 'LEFT');
    //$this->db->where('USR.user_type', 4);
    $this->db->where('USR.id', $userid);
    $this->db->group_by('USR.id');
    $this->db->order_by('USR.first_name');

    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->row(); }else{ return false;  }
  }

  function employeeattendance($userid = NULL){
    $this->db->select('*');
    $this->db->where('user_id', $userid);
    $this->db->order_by('punch_in_date', 'DESC');
    $query =  $this->db->get('employee_punch_details');
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

  function resetDevice($userid){
    $this->db->where('id', $userid);
    $param = array('device_model'=> NULL, 'device_id' => NULL);
    $query = $this->db->update('users', $param);
    if($query != 0){ 
      return true; 
    }else{ 
      return false; 
    }
  }

  function child_user_count($userid){
    $this->db->select('count(*) as child_user');
    $this->db->from('users');
    $this->db->where('parent_id', $userid);
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->child_user > 0){ return FALSE; }else{ return TRUE; }
  }

  function getuserdetails($userid = NULL){
    $this->db->SELECT('USR.*, UT.type as userType, CONCAT(PRNT.first_name," ",PRNT.last_name) as parent_name');
    $this->db->FROM('users USR');
    $this->db->JOIN('users PRNT', 'USR.parent_id = PRNT.id', 'LEFT');
    $this->db->JOIN('user_types UT', 'USR.user_type = UT.id', 'LEFT');
    //$this->db->where('USR.user_type', 4);
    $this->db->where('USR.id', $userid);
    $this->db->group_by('USR.id');
    $this->db->order_by('USR.first_name');

    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->row(); }else{ return false;  }
  }

  function check_old_password($user_id, $old_password)
  {
    $this->db->select('count(*) as password');
    $this->db->from('users');
    $this->db->where('id', $user_id);
    $this->db->where('password', md5($old_password));
    $query = $this->db->get();
    $queryRes = $query->result();
    if($queryRes[0]->password > 0){ return TRUE; }else{ return FALSE; }
  }
  function update_password($user_id, $password)
  {
    $this->db->where('id', $user_id);
    $query = $this->db->update('users', array('password' => md5($password)));
    if($query != 0){ 
      return true; 
    }else{ 
      return false; 
    }
  }

  function employeeattendanceById($id)
  {
    $this->db->SELECT('EPD.punch_in_date,EPD.punch_out_date,CONCAT(USR.first_name," ",USR.last_name) as empName, USR.phone, EPD.user_id');
    $this->db->FROM('employee_punch_details EPD');
    $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
    $this->db->where('EPD.id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->row(); }else{ return false;  }
  }

  function employeeattendanceNext_date($current_date, $user_id)
  {
    $this->db->SELECT('id,punch_in_date,punch_out_date');
    $this->db->FROM('employee_punch_details EPD');
    $this->db->where('user_id', $user_id);
    $this->db->where('punch_in_date > "'.$current_date.'"');
    $this->db->order_by('punch_in_date', 'asc');
    $this->db->limit(5);
    $query = $this->db->get();
    // echo $this->db->last_query();
    // exit;
    if($query->num_rows() > 0){ $result = $query->result();
      return end($result); }else{ return false;  }
  }

  function employeeattendancePrevious_date($current_date, $user_id)
  {
    $this->db->SELECT('id,punch_in_date,punch_out_date');
    $this->db->FROM('employee_punch_details EPD');
    $this->db->where('user_id', $user_id);
    $this->db->where('punch_in_date < "'.$current_date.'"');
    $this->db->order_by('punch_in_date', 'DESC');
    $this->db->limit(6);

    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }
    
  function gettotal_distance($user_id, $punch_id){
        $this->db->SELECT('total_distance');
        $this->db->FROM('employee_punch_details EPD');
        $this->db->where('user_id', $user_id);
        $this->db->where('id', $punch_id);
        $query = $this->db->get();
        if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }
  
  function getlocationReportDetails($id){
    $this->db->SELECT('setting_value');
    $this->db->FROM('site_setting');
    $this->db->WHERE('setting_name', 'location_interval');
    $query = $this->db->get();
    if($query->num_rows() > 0){
        $result = $query->result();
        $setting_value = $result[0]->setting_value;
    }else{
        $setting_value = 30;
    }
    $setting_value = $setting_value * 60;
    $this->db->SELECT('lat,lng,TIME_FORMAT(TIME(created_at),"%h:%i:%s %p") as time, user_id,FROM_UNIXTIME(
         CEILING(UNIX_TIMESTAMP(`created_at`)/'.$setting_value.')*'.$setting_value.'
                    ) AS HOUR,mobile_network,battery,IF(isGpsOn = 0, "No", "Yes") as isGpsOn,distance ');
    $this->db->FROM('employee_punch_logs');
    //$this->db->WHERE('(created_at BETWEEN "'. $date.' 00:00:00" AND "'. $date.' 23:59:59")' );
    $this->db->WHERE('employee_punch_details_id', $id);
    $this->db->order_by('created_at','desc');
    $this->db->group_by('HOUR');
    $query = $this->db->get();
    // echo $this->db->last_query();
    // exit;
    if($query->num_rows() > 0){ 
      return $query->result();
    }else{
      return false;
    }    
  }
}