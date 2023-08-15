<?php
Class MY_Model extends CI_Model
{
  public $child_ids = array();

  function __construct()
  {
    parent::__construct();

  }
  
  function getUsersTreeView($user_id){
    $user_id =$this->session->userdata['logged_in']['userid'];
    $sql = "select  users_sorted.id, parent_id as pid, CONCAT(`first_name`,' ', `last_name`) as name, user_types.type as title, email, phone 
            from    (select * from users order by parent_id, id) users_sorted,
                    (select @iv := '$user_id') initialisation
            join    user_types as user_types
            where   find_in_set(parent_id, @iv)
            and     length(@iv := concat(@iv, ',', users_sorted.id))
            and     users_sorted.user_type = user_types.id 
            and 	is_deleted = 0
            order by users_sorted.id";
    $qquery = $this->db->query($sql);
    $query = $this->db->query($sql)->result();
    $user_sql = "SELECT users.id, parent_id as pid, CONCAT(`first_name`,' ', `last_name`) as name, user_types.type as title, email, phone FROM users join user_types WHERE users.id = $user_id AND users.user_type = user_types.id AND is_deleted = 0";
    $user_query = $this->db->query($user_sql)->result();
    unset($user_query[0]->pid);
    array_unshift($query, $user_query[0]);
                        
    // print_r($query);exit;
    if($qquery->num_rows() > 0)
    { 
        return $query; 
        
    }else{ 
        return false;  
        
    }
    
  }
  
  function getPartyCategories(){
    $this->db->SELECT('*');
    $this->db->FROM('dealer_categories');
    // $this->db->WHERE('id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }
  
  function getPartyByCategory($id = NULL){
    $this->db->SELECT('*');
    $this->db->FROM('dealers');
    $this->db->WHERE('dealer_category', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }
  
  function getProductByCategory($id = NULL){
    $this->db->SELECT('*');
    $this->db->FROM('products');
    $this->db->WHERE('category_id', $id);
    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      return $query->result(); 
    }else{ 
      return false;  
    }
  }
      
  function getCatList($id = NULL){
    $this->db->SELECT('*');
    $this->db->FROM('categories');
    if($id != NULL){ $this->db->WHERE('id', $id); }
    $this->db->order_by('name');
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }
  
  function getUserByType($type = NULL, $id = NULL, $sub_user_ids = NULL){
      $sql = '';
      if(!empty($sub_user_ids) && $sub_user_ids != null){
        $sql = " id IN (".$sub_user_ids.")";
      }
      $this->db->SELECT('*');
      $this->db->FROM('users');
      if($type != NULL){ $this->db->WHERE('user_type', $type); }
      
      if($id != NULL){ $this->db->WHERE('id', $id); }
      if($sql){
        $this->db->where($sql);
      }
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }
  
    function getUserByTypeandAuth($type = NULL, $id = NULL, $auth = NULL) {
    	$this->db->SELECT('*');
    	$this->db->FROM('users');
    	if ($type != NULL) {$this->db->WHERE('user_type', $type);}
    
    	if ($id != NULL) {$this->db->WHERE('id', $id);}
    	$this->db->WHERE('authorization', $auth);
    	$query = $this->db->get();
    	if ($query->num_rows() > 0) {return $query->result();} else {return false;}
    }

  function getParties($empId = NULL){
      $this->db->SELECT('*');
      $this->db->FROM('dealers');
      if($empId != NULL){ $this->db->WHERE('employee_id', $empId); }
      $query = $this->db->get();
      if($query->num_rows() > 0){
          $query = $query->result();
            $response = [];
            foreach ($query as $party){
                $data['id'] = $party->id;
                $data['employee_id'] = $party->employee_id;
                $data['firm_name'] = $party->firm_name;
                $data['address'] = $party->address;
                $data['city_or_town'] = $party->city_or_town;
                $data['gst_number'] = $party->gst_number;
                $data['contact_person_aadhar'] = $party->dealer_aadhar;
                $data['dealer_category'] = $party->dealer_category;
                // $data['dealer_type'] = $party->dealer_type;
                //$data['dealer_name'] = $party->dealer_name;
                // $data['contact_details'][] = array();
                if($party->dealer_name == null){
                    
                    // echo"jsdcka";exit;
                    $this->db->select('dealer_owners.owner_name, dealer_owners.phone_no, dealer_owners.dob, dealer_owners.doa');
                    $this->db->from('dealer_owners');
                    $this->db->where('dealer_owners.dealer_id', $party->id);
                    $model = $this->db->get();
                    if($model->num_rows() > 0){
                        $owner= $model->result();
                        
                            // $data['dealer_name'][] = $owner;
                        foreach ($owner as $value){
                            $data['contact_details'][] = array(
                                'name' => $value->owner_name,
                                'number' => $value->phone_no,
                                'dob' => $value->dob,
                                'doa' => $value->doa,
                                );
                            // $data['dealer_name'][] = ;
                            // $data['dealer_phone'][] = ;
                            // $data['dealer_dob'][] = ;
                            // $data['dealer_doa'][] = ;
                        }
                        
                        $data['dealer_name'] = $data['contact_details'][0]['name'];
                        
                        // print_r($data);exit;
                        // return $data;
                        
                    }
                } else{
                    $data['contact_details'][] = array(
                                'name' => $party->dealer_name,
                                'number' => $party->dealer_phone,
                                'dob' => '',
                                'doa' => '',
                                );
                    // $data['contact_details']['name'] = $party->dealer_name;
                    // $data['contact_details']['number'] = $party->dealer_phone;
                    // $data['contact_details']['dob'] = '';
                    // $data['contact_details']['doa'] = '';
                $data['dealer_name'] = $data['contact_details'][0]['name'];
                
                // return $data;
                    
                }
                $response[] = $data;
                $data = [];
            }
            return  $response; 
          
      }else{
          return false;  
          
      }
  }
  
  
  function getPartyProductsList($dealer_id)
    {
        // echo $dealer_id;exit;
        // $this->db->SELECT('dealer_product.*, dealers.firm_name, products.name, products.mrp, product_categories.name AS catgory_name');
        // $this->db->SELECT('products.*, dealer_product.dealer_price, product_categories.name AS category_name, dealers.firm_name');
        $this->db->SELECT('products.*, COALESCE(dealer_product.dealer_price,products.mrp) as dealer_price, product_categories.name AS category_name, dealers.firm_name');
        $this->db->FROM('products');
        // $this->db->JOIN('dealer_product','products.id = dealer_product.product_id AND dealer_product.dealer_id = '.$dealer_id,'LEFT');
        // $this->db->JOIN('dealer_product','products.id = dealer_product.product_id AND dealer_product.dealer_id ='.$dealer_id,'LEFT');
        $this->db->JOIN('dealer_product','products.id = dealer_product.product_id AND dealer_product.dealer_id ='.$dealer_id,'LEFT');
        $this->db->JOIN('product_categories','product_categories.id = products.category_id','LEFT');
        $this->db->JOIN('dealers','dealers.id = '.$dealer_id,'LEFT');

        if(!empty($dealer_id)){
            // $this->db->where('dealer_product.dealer_id', $dealer_id);
        }else{
            // $this->db->group_by('dealer_product.dealer_id');  
        }
        // $this->db->order_by('dealer_product.id', 'desc');
        
        $query = $this->db->get();
        // print_r($this->db->last_query());exit;
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
  
  function getPartiesNew($empId = NULL){
      $this->db->SELECT('*');
      $this->db->FROM('dealers');
      if($empId != NULL){ $this->db->WHERE('employee_id', $empId); }
      $query = $this->db->get();
      if($query->num_rows() > 0){
          $query = $query->result();
            $response = [];
            foreach ($query as $party){
                $data['id'] = $party->id;
                $data['employee_id'] = $party->employee_id;
                $data['firm_name'] = $party->firm_name;
                $data['address'] = $party->address;
                $data['city_or_town'] = $party->city_or_town;
                $data['gst_number'] = $party->gst_number;
                $data['contact_person_aadhar'] = $party->dealer_aadhar;
                $data['dealer_category'] = $party->dealer_category;
                // $data['dealer_type'] = $party->dealer_type;
                $data['contact_details'] = array();
                if($party->dealer_name == null){
                    
                    $this->db->select('dealer_owners.owner_name, dealer_owners.phone_no, dealer_owners.dob, dealer_owners.doa');
                    $this->db->from('dealer_owners');
                    $this->db->where('dealer_owners.dealer_id', $party->id);
                    $model = $this->db->get();
                    if($model->num_rows() > 0){
                        $owner= $model->result();
                            // $data['dealer_name'][] = $owner;
                        foreach ($owner as $value){
                            $data['contact_details'][] = array(
                                'name' => $value->owner_name,
                                'number' => $value->phone_no,
                                'dob' => $value->dob,
                                'doa' => $value->doa,
                                );
                            // $data['dealer_name'][] = ;
                            // $data['dealer_phone'][] = ;
                            // $data['dealer_dob'][] = ;
                            // $data['dealer_doa'][] = ;
                        }
                        // return $data;
                    }
                } else{
                    $data['contact_details'][] = array(
                                'name' => $party->dealer_name,
                                'number' => $party->dealer_phone,
                                'dob' => '',
                                'doa' => '',
                                );
                    // $data['contact_details']['name'] = $party->dealer_name;
                    // $data['contact_details']['number'] = $party->dealer_phone;
                    // $data['contact_details']['dob'] = '';
                    // $data['contact_details']['doa'] = '';
                    
                }
                // return $data;
            $response[] = $data;
            // $data = [];
            }
            return  $response; 
          
      }else{
          return false;  
          
      }
  }

  function addParty($params = NULL , $data){
      
     $new = array_combine($data['owner_name'],$data['phone_no']);
      
    //   echo "<pre/>";print_r($data);print_r($params);exit;
      
    if($params != NULL){
      $query = $this->db->insert('dealers', $params);
      if($id = $this->db->insert_id()){
          
          foreach($new as $key => $value){
           $dealer_owners = [
             'dealer_id' => $id,
             'owner_name' => $key,
             'phone_no' => $value,
            ];
            
          $query2 = $this->db->insert('dealer_owners',$dealer_owners);
          
          }
        
          return true;
        }else{
          return false;
        }
    }else{
        return false;
    }
  }
  
  function updateDealerOwner($params = NULL , $data){
      
    //  $new = array_combine($data['owner_name'],$data['phone_no']);
      
    // echo "<pre/>";print_r($data);print_r($params);exit;
      
    if($params != NULL){
      $query = $this->db->update('dealers', $params, ['id' => $params['id']]);
    //   if($id = $this->db->insert_id()){
          
        foreach($data['id'] as $key => $value){
            if($data['is_deleted'][$key]){
                $query2 = $this->db->delete('dealer_owners', ['id' => $value]);
            }else{
                $dealer_owners = [
                    'owner_name' => $data['owner_name'][$key],
                    'phone_no' => $data['phone_no'][$key],
                    // 'is_deleted' => $data['is_deleted'][$key],
                ];
                
                $query2 = $this->db->update('dealer_owners',$dealer_owners, ['id' => $value]);
            }
          
        }
        
        return true;
        // }else{
        //   return false;
        // }
    }else{
        return false;
    }
  }

    function like($str, $searchTerm) {
        $searchTerm = strtolower($searchTerm);
        $str = strtolower($str);
        $pos = strpos($str, $searchTerm);
        if ($pos === false)
            return false;
        else
            return true;
    }

  function addParty_New($params = NULL){
    if($params != NULL){
        $query = $this->db->insert('dealers', $params);
        if($this->db->insert_id()){
          return $this->db->insert_id();
        }else{
          return false;
        }
    }else{
        return false;
    }
  }
  
  function addPartyOwner($params = NULL){
    if($params != NULL){
        $query = $this->db->insert('dealer_owners', $params);
        if($this->db->insert_id()){
          return $this->db->insert_id();
        }else{
          return false;
        }
    }else{
        return false;
    }
  }

  function adddealer_branches($params = NULL){
    if($params != NULL){
        $query = $this->db->insert('dealer_branches', $params);
        if($this->db->insert_id()){
          return $this->db->insert_id();
        }else{
          return false;
        }
    }else{
        return false;
    }
  }

  function adddealer_branches_product($params = NULL){
    if($params != NULL){
        $query = $this->db->insert('dealer_branch_products', $params);
        if($this->db->insert_id()){
          return $this->db->insert_id();
        }else{
          return false;
        }
    }else{
        return false;
    }
  }


  
  function updateParty($data = NULL, $where = NULL){
    //   print_r($where);exit;
    if($data != NULL && $where != NULL){
        $this->db->where($where);
        $query = $this->db->update('dealers', $data);        
    // pr($query);
        if($query != 0){ 
            return true; 
        }else{ 
            return false; 
        }
    }else{
      return false;
    }
  }
  
  function deletePartyOwners($id){
      $this->db->where('dealer_id', $id);
        $this->db->delete('dealer_owners');
    
  }

 
  function deleteDealer($id)
  {
    if(!empty($id))
    {
        $this->db->where('id', $id);
        $this->db->delete('dealers'); 
        return true;
    }else{
        return false;
    }   
  }

  function getExpenseList($id = NULL, $empId = NULL, $catId = NULL, $status = NULL, $startDate = NULL, $endDate){
    $expImage = EXPENSES_IMAGE;
     //Employee name, Employee phone, category name, Requested Amount, Approved Amount, Place (Grade), status, created
     $this->db->SELECT('EP.id, EP.employee_id as empId, CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone,EGD.amount as allowAmount, EG.grade as empGrade, CAT.name as catName, CAT.type as categoryType, CAT.id as catId, EP.requested_amount as reqAmount,EP.approved_amount as approveAmount, CT.city_name as place, CT.grade as ctGrade, EP.status,EP.created_at, DATE_FORMAT(EP.created_at,"%d/%m/%Y %H:%i:%s %p") as reqDate, CONCAT("'.$expImage.'large/",EP.expense_photo) as expLargeImg, CONCAT("'.$expImage.'thumb/",EP.expense_photo) as expThumbImg, EP.expense_details, EP.currency_symbol, EP.currency_name', false);
     $this->db->FROM('expenses EP');
     $this->db->JOIN('users USR','EP.employee_id = USR.id','LEFT');
     $this->db->JOIN('categories CAT','EP.category_id = CAT.id','LEFT');
     $this->db->JOIN('city_grades CT','EP.city_id = CT.id','LEFT');
     $this->db->JOIN('emp_grade EG','USR.grade_id = EG.id','LEFT');
     $this->db->JOIN('employee_grade_details EGD','USR.grade_id = EGD.grade_id && CT.grade = EGD.city_grade && CAT.id = EGD.category_id','LEFT', false);
     //employee
     if($empId != NULL){
       $this->db->where('EP.employee_id', $empId);
     }

     if($id != NULL){
       $this->db->where('EP.id', $id);
     }

     //category
     if($catId != NULL){
       $this->db->where('EP.category_id', $catId);
     }

     //status
     if($status != NULL){
       $this->db->where('EP.status', $status);
     }

     if($startDate != NULL){
       $this->db->where('EP.created_at >=', date('Y-m-d H:i:s',strtotime($startDate)));
     }

     if($endDate != NULL){
       $this->db->where('EP.created_at <', date('Y-m-d H:i:s', (strtotime($endDate) + 86340)));
     }
     
     $query = $this->db->get();
     
     if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

  function getParentList($id = NULL){
    global $parentIds;
    if(!is_null($id)){
      $this->db->SELECT('*');
      $this->db->FROM('users');
      $this->db->WHERE('id', $id);
      $query = $this->db->get();
      
      if($query->num_rows() > 0){
        $res = $query->result(); 
        $parent_id = $res[0]->parent_id;
        if($parent_id > 0){
          $parentIds[] = $parent_id;
        }
        return $this->getParentList($parent_id); 
      }else{ 
        return $parentIds;  
      }
    }else{
      return $parentIds;
    }
  }
  
  function getList($cityId = NULL){
    // $this->db->SELECT('*');
    // $this->db->FROM('city_grades');
    $this->db->SELECT('CIT.*, COU.country_name, STA.state_name, ZO.zone_name');
    $this->db->FROM('city_grades as CIT');
    $this->db->JOIN('countries COU','COU.country_id = CIT.country_id','LEFT');
    $this->db->JOIN('zones ZO','ZO.zone_id = CIT.zone_id','LEFT');
    $this->db->JOIN(' states STA','STA.state_id = CIT.state_id','LEFT');
    $this->db->order_by('city_name');
    // $this->db->WHERE('is_deleted', 0);
    $query = $this->db->get();
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }
  
  function getVisits($empId = NULL, $startDate = NULL, $endDate = NULL, $id = NULL){
    $this->db->SELECT('EV.*, DLR.dealer_name as contact_person, DLR.dealer_name as name, DLR.dealer_phone as contact_person_phone, DLR.firm_name as contact_firm, DLR.city_or_town as area_or_town, USR.first_name as first_name, USR.last_name as last_name');
    $this->db->FROM('employee_visits EV');
    $this->db->JOIN('dealers DLR','EV.party_id = DLR.id','LEFT');
    $this->db->JOIN('users USR','EV.employee_id = USR.id','LEFT');
    
    if($empId != NULL){
      $this->db->WHERE('EV.employee_id', $empId);
    }
    
    if($id != NULL){
      $this->db->where('EV.id', $id);
    }

    if($startDate != NULL){
      $this->db->where('EV.visited_at >=', date('Y-m-d H:i:s',strtotime($startDate)));
    }

    if($endDate != NULL){
      $this->db->where('EV.visited_at <', date('Y-m-d H:i:s', (strtotime($endDate) + 86340)));
    }

    $this->db->order_by('EV.created_at', 'desc');

    $query = $this->db->get();
    if($query->num_rows() > 0){ 
      $visitsRes = $query->result();
      $query->free_result();
      foreach ($visitsRes as $key => $value) {
          $this->db->select('dealers.*, GROUP_CONCAT((dealer_owners.owner_name) SEPARATOR "</br>") as owner_detail, GROUP_CONCAT(CONCAT((dealer_owners.owner_name), ("-"), (dealer_owners.phone_no)) SEPARATOR "</br>") as contact_person_details');
          $this->db->FROM('dealers');
          $this->db->join('dealer_owners', 'dealer_owners.dealer_id = dealers.id', 'left');
          $this->db->WHERE('dealers.id', $value->party_id);
          $queryRes = $this->db->get();
        //   echo"<pre>";
        //   print_r($queryRes);
        //   exit;
        //   $visitsRes['rowsssss'] = $queryRes->num_rows();
          if($queryRes->num_rows() > 0){
              $dealerData = $queryRes->result();
              $res = $dealerData[0];
            //   $res->name = $dealerData['first_name'].' '.$dealerData['last_name'];
              $res->contact_person_name = $res->dealer_name;
              $res->dealer_name = ($res->dealer_name) ? (array)$res->dealer_name : [];
              $res->contact_number = $res->dealer_phone;
              $res->dealer_phone = ($res->dealer_phone) ? (array)$res->dealer_phone : [];
              $res->contact_person_aadhar = $res->dealer_aadhar;
              $res->owner_detail = $res->owner_detail;
            //   unset($res->dealer_phone);
            //   unset($res->dealer_name);
            //   unset($res->dealer_aadhar);
              $visitsRes[$key]->party = $dealerData[0];
          }else{
              $visitsRes[$key]->party = array();
          }
          $queryRes->free_result();
      }
      return $visitsRes;
    }else{ 
      return false;  
    }
  }
  
  function addVisit($params = NULL){
    if($params != NULL){
        $query = $this->db->insert('employee_visits', $params);
        if($this->db->insert_id()){
          return true;
        }else{
          return false;
        }
    }else{
        return false;
    }
  }
  
  function getDealers($typeId = NULL, $catId = NULL, $empId = NULL, $startDate = NULL, $endDate = NULL, $id = NULL){
    
      $this->db->select('
                        dealers.*,
                        dealer_categories.name as category,
                        dealer_types.name as type,
                        GROUP_CONCAT(CONCAT((dealer_owners.owner_name), (" - "), (dealer_owners.phone_no)) SEPARATOR "</br>") as owner_detail,
                        GROUP_CONCAT(dealer_owners.id SEPARATOR "|") as owner_ids,
                        GROUP_CONCAT(dealer_owners.owner_name SEPARATOR "|") as owner_names,
                        GROUP_CONCAT(dealer_owners.phone_no SEPARATOR "|") as owner_phones
                    ');
      $this->db->FROM('dealers')
            ->join('dealer_owners', 'dealer_owners.dealer_id = dealers.id', 'left')
            ->join('dealer_categories', 'dealer_categories.id = dealers.dealer_category', 'left')
            ->join('dealer_types', 'dealer_types.id = dealers.dealer_type', 'left')
            ->group_by('dealers.id');
      
      if($catId != NULL){
        $this->db->WHERE('dealer_category', $catId);
      }
      
      if($typeId != NULL){
        $this->db->WHERE('dealer_category', $typeId);
      }
      
      if($empId != NULL){
        $this->db->WHERE('employee_id', $empId);
      }
      
      if($id != NULL){
        //   echo $id;exit();
        $this->db->where('dealers.id', $id);
      }
  
      if($startDate != NULL){
        $this->db->where('dealers.created_at >=', date('Y-m-d H:i:s',strtotime($startDate)));
      }
  
      if($endDate != NULL){
        $this->db->where('dealers.created_at <', date('Y-m-d H:i:s', (strtotime($endDate) + 86340)));
      }
  
      $this->db->order_by('dealers.created_at', 'desc');
      $query = $this->db->get();
                //   print_r($this->db->last_query());exit;

      if($query->num_rows() > 0){ 
        return $query->result();
      }else{
        return false;
      }
  }

  function getUserById($id = NULL){
    if($id){
      $this->db->SELECT('*');
      $this->db->FROM('users');
      $this->db->WHERE('id', $id);
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else{
      return false;
    }
  }

  function getUserByEmpId($id = NULL){
    if($id){
      $this->db->SELECT('*');
      $this->db->FROM('users');
      $this->db->WHERE('emp_id', $id);
      $query = $this->db->get();
      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }else{
      return false;
    }
  }
  
  function getAttendance($param = null){    
    $this->db->SELECT('EPD.id, EPD.user_id as employee_id, EPD.vehicle_type, EPD.partner_name, EPD.tour_details, EPD.punch_in, EPD.punch_out, "A" as attendanceStatus, EPD.created_at');
    $this->db->FROM('employee_punch_details EPD');
    $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
    if(isset($_GET['date'])){ 
      $this->db->where('EPD.punch_in_date', date('Y-m-d', strtotime($_GET['date'])));
    }
    /*$this->db->where('EPD.punch_out_date', NULL);
     */
    if($param != null && isset($param['user_id'])){
      $this->db->where('EPD.user_id',$param['user_id']);
    }
    $this->db->order_by('EPD.created_at', "DESC");

    $query = $this->db->get();
    
    if($query->num_rows() > 0){
      $response = $query->result();
      $attendaceRsponse = array();
      foreach ($response as $key => $value) {
        /**InDate */
        $InVal = $value->punch_in;
        $inDate = json_decode($InVal);
        $inDate->time = date('H:i', strtotime($inDate->date));
        $inDate->meter_reading_photo = PUNCH_IMAGE.'thumb/'.$inDate->meter_reading_photo;
        $value->punch_in = $inDate;

        /**OutDate */
        $OutVal = $value->punch_out;
        if(!is_null($OutVal) && $OutVal != ''){
          $outDate = (array)json_decode($OutVal);
          $outDate['time'] = date('H:i', strtotime($outDate['date']));
          $outDate['meter_reading_photo'] = PUNCH_IMAGE.'thumb/'.$outDate['meter_reading_photo'];
          $value->punch_out = $outDate;

          $dateDiff = intval((strtotime($outDate['date'])-strtotime($inDate->date))/60);
          $hours = str_pad(intval($dateDiff/60), 2, '0', STR_PAD_LEFT);
          $minutes = str_pad(($dateDiff%60), 2, '0', STR_PAD_LEFT);
          
          $valueIn = (float)$inDate->meter_reading_in_km; 
          $valueOut = (float)$outDate["meter_reading_in_km"]; 
          
          $distanceTravel = $valueOut - $valueIn;

          $value->totalLoggedTime = $hours.':'.$minutes;
          $value->totalTravelledDistance = $distanceTravel;
        }else{
          $value->punch_out = (object)array();
          $value->totalLoggedTime = '00:00';
        }
        
        $attendaceRsponse[] = $value;
      } 
      return $attendaceRsponse; 
    }else{ 
      return array();  
    }
  }

  // function getAttendanceReport($empId = NULL, $startDate = NULL, $endDate = NULL){    
  //   $this->db->SELECT('EPD.id, CONCAT(USR.first_name," ",USR.last_name) as empName, USR.phone, punch_in, punch_out, traveled_km as totalReading');
  //   $this->db->FROM('employee_punch_details EPD');
  //   $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
  //   //$this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
  
  //   if($empId != NULL){
  //     $this->db->WHERE('EPD.user_id', $empId);
  //   }

  //   if($startDate != NULL){
  //     $this->db->where('EPD.created_at >=', date('Y-m-d H:i:s',strtotime($startDate)));
  //   }

  //   if($endDate != NULL){
  //     $this->db->where('EPD.created_at <', date('Y-m-d H:i:s', (strtotime($endDate) + 86340)));
  //   }
    
  //   $this->db->order_by('EPD.created_at', 'desc');

  //   $query = $this->db->get();
    
  //   if($query->num_rows() > 0){
  //     $response = $query->result();
  //     $attendaceRsponse = array();
  //     foreach ($response as $key => $value) {
  //       /**InDate */
  //       $InVal = $value->punch_in;
  //       $inDate = json_decode($InVal);
  //       $value->punchInLocation = $inDate->place;
  //       $value->inPhoto = $inDate->meter_reading_photo;
  //       $value->inReading = $inDate->meter_reading_in_km;
        
  //       $inDate->time = date('H:i', strtotime($inDate->date));
  //       $value->punch_in = json_encode($inDate);
  //       $value->punchInDate = date(DEFAULT_DATETIME_FORMAT, strtotime($inDate->date));

  //       /**OutDate */
  //       if(isset($value->punch_out)){
  //         $OutVal = $value->punch_out;
  //         $outDate = json_decode($OutVal);
  //         $value->outReading = $outDate->meter_reading_in_km;
  //         $value->outPhoto = $outDate->meter_reading_photo;
  //         $value->punchOutLocation = $outDate->place;
  //         $outDate->time = date('H:i', strtotime($outDate->date));
  //         $value->punch_out = json_encode($outDate);
  //         $value->punchOutDate =  date(DEFAULT_DATETIME_FORMAT, strtotime($outDate->date));
  
  //         $dateDiff = intval((strtotime($outDate->date)-strtotime($inDate->date))/60);
  //         $hours = str_pad(intval($dateDiff/60), 2, '0', STR_PAD_LEFT);
  //         $minutes = str_pad(($dateDiff%60), 2, '0', STR_PAD_LEFT);
          
  //         $value->totalLoggedTime = $hours.':'.$minutes;
  //       }else{
  //         $value->outReading = '-';
  //         $value->outPhoto = '';
  //         $value->punchOutLocation = '-';
  //         $value->punchOutDate =  '-';
  //         $value->totalLoggedTime = "00:00";
  //       }
  //       unset($value->punch_out);
  //       unset($value->punch_in);
  //       $value->punchinSort = date('Y-m-d',strtotime(str_replace('/', '-', $value->punchInDate)));
        

  //       //$value->punchoutSort = strtotime($value->punchOutDate);
  
  //       $attendaceRsponse[$key] = $value;
  //     } 


  //     // uasort($attendaceRsponse, function ($item, $compare) {
  //     //     return $item->punchinSort >= $compare->punchinSort; 
  //     // });

  //     // //var_dump($attendaceRsponse);
  //     // pr($attendaceRsponse);
  //     return $attendaceRsponse; 
  //   }else{ 
  //     return array();  
  //   }
  // }

  function getAttendanceReport($empId = NULL, $startDate = NULL, $endDate = NULL, $sub_user_ids = NULL){  
      
    $sql = '';
    
    if(!empty($sub_user_ids) && $sub_user_ids != null){
      $sql = " USR.id IN (".$sub_user_ids.")";
    }
     
    // $this->db->SELECT('EPD.id, CONCAT(USR.first_name," ",USR.last_name) as empName, USR.phone, punch_in, punch_out, traveled_km as totalReading, EPD.da, EPD.ta, tour_details, partner_name');
    $this->db->SELECT('EPD.id, EPD.total_distance as totalDistance, CONCAT(USR.first_name," ",USR.last_name) as empName, USR.phone, punch_in, punch_out, traveled_km as totalReading');
    $this->db->FROM('employee_punch_details EPD');
    $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
  
    if($empId != NULL){
      $this->db->WHERE('EPD.user_id', $empId);
    }

    if($startDate != ''){
        $this->db->where('EPD.created_at >=', date('Y-m-d H:i:s',strtotime($startDate)));
    }

    if($endDate != NULL){
      $this->db->where('EPD.created_at <', date('Y-m-d H:i:s', (strtotime($endDate) + 86340)));
    }
    if($sql){
      $this->db->where($sql);
    }
    $this->db->order_by('EPD.created_at', 'DESC');

    $query = $this->db->get();
    
    if($query->num_rows() > 0){
      $response = $query->result();
      $attendaceRsponse = array();
      foreach ($response as $key => $value) {
        /**InDate */
        $InVal = $value->punch_in;
        $inDate = json_decode($InVal);
        $value->punchInLocation = $inDate->place;
        $value->inPhoto = $inDate->meter_reading_photo;
        $value->inReading = $inDate->meter_reading_in_km;
        
        $inDate->time = date('H:i', strtotime($inDate->date));
        $value->punch_in = json_encode($inDate);
        $value->punchInDate = date(DEFAULT_DATETIME_FORMAT, strtotime($inDate->date));

        /**OutDate */
        if(isset($value->punch_out)){
          $OutVal = $value->punch_out;
          $outDate = json_decode($OutVal);
          $value->outReading = (isset($outDate->meter_reading_in_km)) ? $outDate->meter_reading_in_km : 0;
          $value->outPhoto = (isset($outDate->meter_reading_photo)) ? $outDate->meter_reading_photo : ' - ';
          $value->punchOutLocation = (isset($outDate->place)) ? $outDate->place : ' - ';
          if(!empty(isset($outDate->date))){
            $outDate->time = date('H:i', strtotime($outDate->date));
          }
          $value->punch_out = json_encode($outDate);
          $value->punchOutDate =  (isset($outDate->date)) ? date(DEFAULT_DATETIME_FORMAT, strtotime($outDate->date)) : ' - ';
  
          $dateDiff = (isset($outDate->date)) ? intval((strtotime($outDate->date)-strtotime($inDate->date))/60) : ' ';
          $hours = str_pad(intval((float)$dateDiff/60), 2, '0', STR_PAD_LEFT);
          $minutes = str_pad(((float)$dateDiff%60), 2, '0', STR_PAD_LEFT);
          
          $value->totalLoggedTime = $hours.':'.$minutes;
        }else{
          $value->outReading = '-';
          $value->outPhoto = '';
          $value->punchOutLocation = '-';
          $value->punchOutDate =  '-';
          $value->totalLoggedTime = "00:00";
        }
        unset($value->punch_out);
        unset($value->punch_in);
        $attendaceRsponse[] = $value;
      } 
      return $attendaceRsponse; 
    }else{ 
      return array();  
    }
  }

  function getAbsentReport($empId = NULL, $startDate = NULL, $endDate = NULL){    
    $this->db->SELECT('EPD.id, CONCAT(USR.first_name," ",USR.last_name) as empName, USR.phone, punch_in, punch_out, traveled_km as totalReading');
    $this->db->FROM('employee_punch_details EPD');
    $this->db->JOIN('users USR', 'EPD.user_id != USR.id', 'LEFT');
    //$this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
  
    // if($empId != NULL){
    //   $this->db->WHERE('EPD.user_id', $empId);
    // }

    if($startDate != NULL){
      $this->db->where('EPD.created_at >=', date('Y-m-d H:i:s',strtotime($startDate)));
    }

    if($endDate != NULL){
      $this->db->where('EPD.created_at <', date('Y-m-d H:i:s', (strtotime($endDate) + 86340)));
    }
    
    $this->db->order_by('EPD.created_at', 'desc');

    $query = $this->db->get();
    
    if($query->num_rows() > 0){
      $response = $query->result();
      $attendaceRsponse = array();
      foreach ($response as $key => $value) {
        /**InDate */
        $InVal = $value->punch_in;
        $inDate = json_decode($InVal);
        $value->punchInLocation = $inDate->place;
        $value->inPhoto = $inDate->meter_reading_photo;
        $value->inReading = $inDate->meter_reading_in_km;
        
        $inDate->time = date('H:i', strtotime($inDate->date));
        $value->punch_in = json_encode($inDate);
        $value->punchInDate = date(DEFAULT_DATETIME_FORMAT, strtotime($inDate->date));

        /**OutDate */
        if(isset($value->punch_out)){
          $OutVal = $value->punch_out;
          $outDate = json_decode($OutVal);
          $value->outReading = $outDate->meter_reading_in_km;
          $value->outPhoto = $outDate->meter_reading_photo;
          $value->punchOutLocation = $outDate->place;
          $outDate->time = date('H:i', strtotime($outDate->date));
          $value->punch_out = json_encode($outDate);
          $value->punchOutDate =  date(DEFAULT_DATETIME_FORMAT, strtotime($outDate->date));
  
          $dateDiff = intval((strtotime($outDate->date)-strtotime($inDate->date))/60);
          $hours = str_pad(intval($dateDiff/60), 2, '0', STR_PAD_LEFT);
          $minutes = str_pad(($dateDiff%60), 2, '0', STR_PAD_LEFT);
          
          $value->totalLoggedTime = $hours.':'.$minutes;
        }else{
          $value->outReading = '-';
          $value->outPhoto = '';
          $value->punchOutLocation = '-';
          $value->punchOutDate =  '-';
          $value->totalLoggedTime = "00:00";
        }
        unset($value->punch_out);
        unset($value->punch_in);
        $value->punchinSort = date('Y-m-d',strtotime(str_replace('/', '-', $value->punchInDate)));
        

        //$value->punchoutSort = strtotime($value->punchOutDate);
  
        $attendaceRsponse[$key] = $value;
      } 


      // uasort($attendaceRsponse, function ($item, $compare) {
      //     return $item->punchinSort >= $compare->punchinSort; 
      // });

      // //var_dump($attendaceRsponse);
      // pr($attendaceRsponse);
      return $attendaceRsponse; 
    }else{ 
      return array();  
    }
  }


  function getlocationReport($empId = NULL, $date = NULL){    
    $this->db->SELECT('EPD.id, CONCAT(USR.first_name," ",USR.last_name) as empName, USR.phone, punch_in, punch_out, traveled_km as totalReading');
    $this->db->FROM('employee_punch_details EPD');
    $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
  
    if($empId != NULL){
      $this->db->WHERE('EPD.user_id', $empId);
    }

    if($date != NULL){
      
      $startDate = date('Y-m-d H:i:s',strtotime($date));      
      $endDate = date('Y-m-d H:i:s',strtotime($date.' 23:59:59'));
      // echo  $startDate."</br>";
      // echo $endDate."</br>";
      // echo strtotime($date);
      // exit;
      $this->db->where('EPD.created_at >=', $startDate);
      $this->db->where('EPD.created_at <=', $endDate);
    }/*else{
      $this->db->where('EPD.created_at >=', '2020-06-20 00:00:00');//date('Y-m-d H:i:s'));
    }*/

    
    
    $this->db->order_by('EPD.created_at', 'desc');

    $query = $this->db->get();
    
    if($query->num_rows() > 0){
      $response = $query->result();
      $attendaceRsponse = array();
      foreach ($response as $key => $value) {
        /**InDate */
        $InVal = $value->punch_in;
        $inDate = json_decode($InVal);
        $value->punchInLocation = $inDate->place;
        $value->inPhoto = $inDate->meter_reading_photo;
        $value->inReading = $inDate->meter_reading_in_km;
        
        $inDate->time = date('H:i', strtotime($inDate->date));
        $value->punch_in = json_encode($inDate);
        $value->punchInDate = date(DEFAULT_DATETIME_FORMAT, strtotime($inDate->date));

        /**OutDate */
        if(isset($value->punch_out)){
          $OutVal = $value->punch_out;
          $outDate = json_decode($OutVal);
          $value->outReading = $outDate->meter_reading_in_km;
          $value->outPhoto = $outDate->meter_reading_photo;
          $value->punchOutLocation = $outDate->place;
          $outDate->time = date('H:i', strtotime($outDate->date));
          $value->punch_out = json_encode($outDate);
          $value->punchOutDate =  date(DEFAULT_DATETIME_FORMAT, strtotime($outDate->date));
  
          $dateDiff = intval((strtotime($outDate->date)-strtotime($inDate->date))/60);
          $hours = str_pad(intval($dateDiff/60), 2, '0', STR_PAD_LEFT);
          $minutes = str_pad(($dateDiff%60), 2, '0', STR_PAD_LEFT);
          
          $value->totalLoggedTime = $hours.':'.$minutes;
        }else{
          $value->outReading = '-';
          $value->outPhoto = '';
          $value->punchOutLocation = '-';
          $value->punchOutDate =  '-';
          $value->totalLoggedTime = "00:00";
        }
        unset($value->punch_out);
        unset($value->punch_in);
        $attendaceRsponse[] = $value;
      } 
      return $attendaceRsponse; 
    }else{ 
      return array();  
    }
  }



  function getCurrency($id = NULL){
    $this->db->SELECT('*');
    $this->db->FROM('currencies');
    if($id != NULL){
      $this->db->WHERE('id', $id);
    }
    $query = $this->db->get();
    
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return array();
    }
  }
  
  function insertCurrency(){
    $data = $this->input->post();
    $query = $this->db->insert('currencies',$data);

    if($this->db->insert_id()){
      return true;
    }else{
      return false;
    }
  }

  function updateCurrency(){
    $data = $this->input->post();
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('currencies', $data);
    if($query != 0){ 
      return true; 
    }else{ 
      return false; 
    }
  } 

  function updateDealer(){
    $data = $this->input->post();
    $this->db->where('id', $this->input->post('id'));
    $query = $this->db->update('dealers', $data);
    if($query != 0){ return true; }else{ return false; }
  }
  
  function getLocalDateTime($date){
    $dt = new DateTime($date);
    $tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after
    $dt->setTimezone($tz);
    return $dt->format('d/m/Y h:i:s A');
  }

  function getAllChield( $userId = NULL ) 
  {
      if($userId != NULL){
          //get parent category value.
          $this->db->select('parent_id,id');
          $this->db->from('users');
          $this->db->where('user_type !=',4);
          $this->db->where_in('parent_id',$userId);
          $query = $this->db->get();
          $parent = $query->result(); 
          
          if($query->num_rows() > 0)
          {
            $ids = array();

            foreach ($parent as $key => $value) {
              array_push($ids, $value->id);
              array_push($this->child_ids, $value->id);
            }
            return $this->getAllChield($ids);
          }else{
            return $this->child_ids;
          }
      }
  }

  function getSubUser($user_id){
    $data = $this->db->query("CALL get_all_subadmin($user_id)");
    $result = $data->row_array();
    $this->db->close();
    if($result['all_subadmin'] != NULL){
      return $result['all_subadmin'];
    }else{
      return null;
    }
  }
  function getuserslist_new($userid = NULL, $sud_admin = NULL, $cur_userid = NULL, $cur_user_type = NULL){
    $user_id = $cur_userid;//$this->session->userdata['logged_in']['userid'];
    $uType = $cur_user_type;//$this->session->userdata['logged_in']['usertype'];
    if($cur_userid == null){
      $user_id = $this->session->userdata['logged_in']['userid'];
    }

    if($cur_user_type == null){
      $uType = $this->session->userdata['logged_in']['usertype'];
    }

    $ids = ($uType == 'Super Admin')? array($user_id,1) : array($user_id,1,2);
    // $this->db->SELECT('USR.*, UT.type as userType, CONCAT(PRNT.first_name," ",PRNT.last_name) as parent_name');
    // $this->db->FROM('users USR');
    // $this->db->JOIN('users PRNT', 'USR.parent_id = PRNT.id', 'LEFT');
    // $this->db->JOIN('user_types UT', 'USR.user_type = UT.id', 'LEFT');
    // $this->db->where_not_in('USR.id', $ids);
    $sql = "SELECT USR.*, `UT`.`type` as userType, CONCAT(`PRNT`.`first_name`,' ', `PRNT`.`last_name`) as parent_name FROM users USR LEFT JOIN `users` `PRNT` ON `USR`.`parent_id` = `PRNT`.`id` LEFT JOIN `user_types` `UT` ON `USR`.`user_type` = `UT`.`id` WHERE `USR`.`id` NOT IN(".implode(",", $ids).") ";

    if($userid != NULL){
      //$this->db->where('USR.id', base64_decode($userid));
      $sql .= " AND USR.id = ".base64_decode($userid);
    }


    if($sud_admin != NULL && $uType !== 'Super Admin'){
      $sql .= " AND USR.id IN (".$sud_admin.")";
      //$this->db->where("USR.id IN (".implode(",", $sud_admin).")");
    }elseif($uType !== 'Super Admin' ) {
      $sql .= " AND USR.parent_id IN (".$user_id.")";  
    }else{
      if($_POST && $sud_admin != NULL){ //&& $_POST['parent_id']
        $sql .= " AND USR.id IN (".$sud_admin.")";
      }
    }
    
    
    /*if(!empty($_POST) && $_POST['parent_id']){
        $res = $this->users_model->getSubUser($_POST['parent_id']);
      }else{
        $res = $this->users_model->getSubUser($userId);
      }*/
    if(!empty($_POST) && isset($_POST['user_type']) && $_POST['user_type'] > 0 ){
      $sql .= " AND USR.user_type =". $_POST['user_type'];  
    }
    $sql .= " AND USR.is_deleted = 0";
    $sql .= " GROUP BY `USR`.`id` ORDER BY `USR`.`first_name`";
    // $this->db->group_by('USR.id');
    // $this->db->order_by('USR.first_name');
    // $query = $this->db->get();
    $query = $this->db->query($sql);
    //$result = $data->row_array();
    // echo $this->db->last_query();
    // exit;
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }
  

  function getarchiveuserlist(){
    $user_id =$this->session->userdata['logged_in']['userid'];
    $uType = $this->session->userdata['logged_in']['usertype'];
    

    $ids = ($uType == 'Super Admin')? array($user_id,1) : array($user_id,1,2);
    $sql = "SELECT USR.*, `UT`.`type` as userType, CONCAT(`PRNT`.`first_name`,' ', `PRNT`.`last_name`) as parent_name FROM users USR LEFT JOIN `users` `PRNT` ON `USR`.`parent_id` = `PRNT`.`id` LEFT JOIN `user_types` `UT` ON `USR`.`user_type` = `UT`.`id` WHERE `USR`.`id` NOT IN(".implode(",", $ids).") ";
    $sql .= " AND ( USR.user_type = 3 OR USR.user_type =4)";  
    $sql .= " AND USR.is_deleted = 1";
    $sql .= " GROUP BY `USR`.`id` ORDER BY `USR`.`first_name`";
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
  }

  function getSiteSetting($siteSettingName = null){
    if($siteSettingName != NULL){
          //get parent category value.
          $this->db->select('setting_value');
          $this->db->from('site_setting');
          $this->db->where('setting_name', $siteSettingName);
          $query = $this->db->get();
          $parent = $query->result(); 
          if($query->num_rows() > 0){
            return $parent[0]->setting_value;
          }else{
            return false;
          }
      }
  }
  function gettotaluser($type_of_user = null){
    $this->db->select('count(*) as number_of_user');
    $this->db->from('users');
    $this->db->where('user_type', $type_of_user);
    $query = $this->db->get();
    $result = $query->result(); 
    return $result[0]->number_of_user;    
  }
  
  function getLiveUser()
  {
    //$this->getParentIds();return;
    $userId = $this->session->userdata['logged_in']['userid'];
    $uType = $this->session->userdata['logged_in']['usertype'];
    $sud_admin = $this->getSubUser($userId);
    $sql = '';
    if ($uType !== 'Super Admin' && !empty($sud_admin)) {
      $sql = " USR.id IN (" . $sud_admin . ")";
      $sql2 = " AND users.id IN (" . $sud_admin . ")";
    }
    $this->db->initialize();
    //$sql = "USR.id IN ('38','113','156','158','157')";

    
      $currentUser = $this->session->userdata['logged_in']['userid'];
      $this->db->SELECT('EPD.*,CONCAT(USR.first_name," ",USR.last_name) as empName,USR.phone');
      $this->db->FROM('employee_punch_details EPD');
      $this->db->JOIN('users USR', 'EPD.user_id = USR.id', 'LEFT');
      $this->db->where('EPD.punch_out_date', NULL);
      $this->db->where('EPD.punch_in_date', date('Y-m-d'));
      if ($sql) {
        $this->db->where($sql);
      }
      $this->db->order_by('EPD.created_at', 'desc');

      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return false;
      }
    
  }

  function getAbsentUser()
  {
    //$this->getParentIds();return;
    $userId = $this->session->userdata['logged_in']['userid'];
    $uType = $this->session->userdata['logged_in']['usertype'];
    $sud_admin = $this->getSubUser($userId);
    $sql2 = '';
    if ($uType !== 'Super Admin' && $sud_admin) {
      $sql = " USR.id IN (" . $sud_admin . ")";
      $sql2 = " AND users.id IN (" . $sud_admin . ")";
    }
    $this->db->initialize();
    //$sql = "USR.id IN ('38','113','156','158','157')";

      $query =  $this->db->query("SELECT CONCAT(`users`.`first_name`,' ',`users`.`last_name`) as empName,`users`.`phone` FROM `users` WHERE user_type = 4 AND is_deleted = 0 AND `users`.id NOT IN (SELECT `employee_punch_details`.`user_id` FROM employee_punch_details WHERE `employee_punch_details`.`punch_in_date` = CURRENT_DATE() ORDER BY `employee_punch_details`.`created_at` DESC ) " . $sql2);
      // print_r($query->result());
      // exit;
      //$query = $this->db->get();
      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return false;
      }
    
  }

}
?>