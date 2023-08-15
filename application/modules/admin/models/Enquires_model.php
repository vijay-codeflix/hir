<?php

    class Enquires_model extends MY_Model {

        function __construct(){
            parent::__construct();
        }

        function getEnquiryById($id = NULL){
            if($id){
                $this->db->SELECT('enquiry_products.*, products.name as product_name, enquiry_products.category_id as category_name');
                $this->db->FROM('enquiry_products');
                $this->db->WHERE('enquiry_id', $id);
                $this->db->join('products','products.id = enquiry_products.product_id','left');
                $this->db->join('product_categories','product_categories.id = enquiry_products.category_id','left');
                $query = $this->db->get();
                if($query->num_rows() > 0){ return $query->result(); }else{ return false;  }
            }else{
                return false;
            }
        }

        function getEnquiryList(){
            $this->db->SELECT('enquiries.*,
            CONCAT(users.first_name," ", users.last_name) as employee_name,
            COALESCE(
                (SELECT firm_name FROM dealers WHERE id = enquiries.party_id),
                party_name
            ) AS firm_name, 
             dealer_categories.name as dealer_category_name');
            $this->db->FROM('enquiries');
            $this->db->join('dealers','dealers.id = enquiries.party_id','left');
            $this->db->join('users','users.id = enquiries.user_id','left');
            $this->db->join('dealer_categories','dealer_categories.id = enquiries.party_category_id','left');

            $result = $this->db->get();
            if(!empty($result)){
                $query =  $result->result();
                $response = [];
                foreach ($query as $po){
                    $data['id'] = $po->id;
                    $data['enquiry_no'] = $po->enquiry_no;
                    $data['enquiry_date'] = $po->date;
                    $data['employee_name'] = $po->employee_name;
                    $data['firm_name'] = $po->firm_name;
                    $data['dealer_category_name'] = $po->dealer_category_name;
                    $data['date'] = $po->date;

                    $response[] = $data;
                }
                return  $response;
            }else {
                return false;
            }
        }
        
        function delete($id)
        {
          if(!empty($id))
          {
              $this->db->where('id', $id);
              $this->db->delete('enquiries');
              
              $this->db->where('enquiry_id', $id)->delete('enquiry_products');
              return true;
          }else{
              return false;
          }   
        }
        
        function getProductCategories()
        {
            $this->db->SELECT('*');
            $this->db->FROM('product_categories');
            // $this->db->WHERE('id', $id);
            $query = $this->db->get();
            if($query->num_rows() > 0){ 
              return $query->result(); 
            }else{ 
              return false;  
            }
        }

    }
