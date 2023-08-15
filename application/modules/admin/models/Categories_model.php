<?php

Class Categories_model extends MY_Model
{

    function __construct(){
        parent::__construct();
    }

    function insert(){
        $data = $this->input->post();
        $query = $this->db->insert('product_categories', $data);
        if($query != 0){ return true; }else{ return false; }
    }
    function update(){
        $data = $this->input->post();
        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->update('product_categories', $data);
        if($query != 0){ return true; }else{ return false; }
    }

    function delete($id)
    {
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $this->db->delete('product_categories');
            return true;
        }else{
            return false;
        }
    }

    function checkDuplicateproductcategories($val, $field){
        $this->db->select('count(*) as duplicate');
        $this->db->from('product_categories');
        $this->db->where_not_in('id', $this->input->post('id'));
        if($field == 'name'){
            $this->db->where('name', $this->input->post('name'));
        }
        $query = $this->db->get();
        $queryRes = $query->result();
        if($queryRes[0]->duplicate > 0){ return 'FALSE'; }else{ return 'TRUE'; }
    }

    function getProductCategoriesDetails($id){
        $this->db->SELECT('*');
        $this->db->FROM('product_categories');
        $this->db->WHERE('id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    function getProductCategoriesList(){
        $this->db->SELECT('*');
        $this->db->FROM('product_categories');
        $this->db->WHERE('status', 1);
        $this->db->order_by('created_at', 'desc');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }

    function getPoList(){
        $this->db->SELECT('*');
        $this->db->FROM('products_order');
        $this->db->join('users','users.id = products_order.employee_id','left');
        $this->db->join('dealers','dealers.id = products_order.dealer_id','left');

        $this->db->order_by('products_order.created_at', 'desc');
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return false;
        }
    }



}