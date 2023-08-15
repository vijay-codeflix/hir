<?php
Class Payments_model extends MY_Model
{
    function __construct(){
    parent::__construct();
    }

    function paymentList($id = NULL, $empId = NULL, $startDate = '', $endDate = '', $type = NULL){
      //Person Name	Amount	Type	Cheque Details	Payment Details	Date	More	Action Total Payment	
      $pmtImage = PAYMENTS_IMAGE;
      $this->db->select('DLR.dealer_name, CONCAT(EMP.first_name," ", EMP.last_name) as empName, PMT.amount, PMT.payment_method, PMT.cheque_detail, PMT.collection_of, PMT.extra, PMT.payment_details, DATE_FORMAT(PMT.created_at,"%d/%m/%Y %H:%i:%s %p") as reqDate, PMT.id, CONCAT("'.$pmtImage.'thumb/",PMT.photo) as pmtThumbImg, CONCAT("'.$pmtImage.'large/",PMT.photo) as pmtLargeImg');

      $this->db->FROM('payments PMT');
      $this->db->JOIN('dealers DLR','PMT.dealer_id = DLR.id','LEFT');
      $this->db->JOIN('users EMP','PMT.employee_id = EMP.id','LEFT');
      
      if($type != NULL){
        $this->db->WHERE('PMT.status', $type);
      }
    
      //employee
      if($empId != NULL){
        $this->db->where('PMT.employee_id', $empId);
      }

      if($id != NULL){
        $this->db->where('PMT.id', $id);
      }

      if($startDate != NULL){
        $this->db->where('PMT.created_at >=', date('Y-m-d H:i:s',strtotime($startDate)));
      }

      if($endDate != NULL){
        $this->db->where('PMT.created_at <', date('Y-m-d H:i:s', (strtotime($endDate) + 86340)));
      }
      

      $query = $this->db->get();

      if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
    }
    
    function updatePaymentAction($data, $id){
      $this->db->where('id', $id);
      $query = $this->db->update('payments', $data);
      if($query != 0){ return true; }else{ return false; }
    }
}