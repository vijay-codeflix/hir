<?php

class Dealersproduct_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function insert()
    {
        $data = $this->input->post();
        unset($data['product_id'][0]);
        $data['insert'] = null;
        $i = 0;
        foreach($data['product_id'] as $key=>$val)
        {
            if($i == 0){
                $data['insert'] .= '('.$data['dealer_id'].', '.$val.', (SELECT mrp FROM products WHERE id='.$val.'))';
            }else{
                $data['insert'] .= ', ('.$data['dealer_id'].', '.$val.', (SELECT mrp FROM products WHERE id='.$val.'))';
            }
              $i++;
        }
        $sql = "INSERT INTO dealer_product (dealer_id, product_id, dealer_price) VALUES ".$data['insert'];
        // print_r($sql);exit;
        $query = $this->db->query($sql);
        if ($query != 0) {
            return true;
        } else {
            return false;
        }
    }

    function update($dealer_id, $product_id, $amount)
    {
        $this->db->where(['dealer_id' => $dealer_id, 'product_id' => $product_id ]);
        $this->db->delete('dealer_product');
        
        $sql = "INSERT INTO dealer_product (dealer_id, product_id, dealer_price) VALUES (".$dealer_id.", ".$product_id.", ".$amount.")";
        // print_r($sql);exit;
        $query = $this->db->query($sql);
        if ($query != 0) {
            return true;
        } else {
            return false;
        }
        
    }

    function delete($dealer_id)
    {
        // echo $dealer_id;exit;
        if (!empty($dealer_id)) {
            $this->db->where('dealer_id', $dealer_id);
            $this->db->delete('dealer_product');
            return true;
        } else {
            return false;
        }
    }

    function getDealerProductsDetails($id)
    {
        $this->db->SELECT('id, GROUP_CONCAT(product_id) as product_id, dealer_id');
        $this->db->FROM('dealer_product');
        $this->db->WHERE('dealer_id', '(select dealer_id from dealer_product where id='.$id.')', FALSE);
        $this->db->GROUP_BY('dealer_id');
        // echo $this->db->get_compiled_select(); exit;
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function getDealerProductsByDealerAndProduct($dealer_id, $product_id)
    {
        $this->db->SELECT('*');
        $this->db->FROM('dealer_product');
        $this->db->WHERE(['dealer_id' => $dealer_id, 'product_id' => $product_id]);
        // echo $this->db->get_compiled_select(); exit;
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getDealerProductsList($dealer_id)
    {
        // echo $dealer_id;exit;
        // $this->db->SELECT('dealer_product.*, dealers.firm_name, products.name, products.mrp, product_categories.name AS catgory_name');
        $this->db->SELECT('products.*, dealer_product.dealer_price, product_categories.name AS category_name, dealers.firm_name');
        $this->db->FROM('products');
        // $this->db->JOIN('dealer_product','products.id = dealer_product.product_id AND dealer_product.dealer_id = '.$dealer_id,'LEFT');
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
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getPartiesList()
    {
        $this->db->SELECT('*');
        $this->db->FROM('dealers');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getProductsList()
    {
        $this->db->SELECT('*');
        $this->db->FROM('products');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}