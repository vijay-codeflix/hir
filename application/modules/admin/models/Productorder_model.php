<?php

class Productorder_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getPoById($id = NULL)
    {
        if ($id) {
            $this->db->SELECT(
                'products_order_product.*, 
                products.name as product_name, 
                products.unit as product_unit, 
                products.ah as product_ah, 
                products.mrp as product_mrp, 
                products.item_code as product_item_code, 
                products.category_id, 
                product_categories.name as product_catgeory_name, 
                dealer_product.dealer_price'
            );
            $this->db->FROM('products');
            $this->db->join('products_order_product', 'products.id = products_order_product.product_id', 'left');
            $this->db->join('products_order', 'products_order.id = products_order_product.product_order_id', 'left');
            $this->db->join('product_categories', 'product_categories.id = products.category_id', 'left');
            $this->db->join('dealer_product', 'dealer_product.product_id = products_order_product.product_id AND dealer_product.dealer_id = products_order.dealer_id', 'left');
            $this->db->WHERE('product_order_id', $id);
            $this->db->group_by('product_id');
            $query = $this->db->get();
            // print_r($this->db->last_query());exit;
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getProductOrderDetails($product_order_id)
    {
        if ($product_order_id) {
            $this->db->SELECT('products_order.*,CONCAT(users.first_name," ", users.last_name) as employee_name, dealers.dealer_name, dealers.firm_name, dealers.address, dealers.gst_number, dealers.dealer_phone');
            $this->db->WHERE('products_order.id', $product_order_id);
            $this->db->FROM('products_order');
            $this->db->join('dealers', 'dealers.id = products_order.dealer_id', 'left');
            $this->db->join('users', 'users.id = products_order.employee_id', 'left');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getPoList()
    {
        $this->db->SELECT('products_order.*,CONCAT(users.first_name," ", users.last_name) as employee_name, dealers.dealer_name, dealers.firm_name, dealers.address, dealers.gst_number, dealers.dealer_phone');
        $this->db->FROM('products_order');
        $this->db->join('dealers', 'dealers.id = products_order.dealer_id', 'left');
        $this->db->join('users', 'users.id = products_order.employee_id', 'left');
        $this->db->order_by("id", "desc");

        $result = $this->db->get();
        if (!empty($result)) {
            $query =  $result->result();
            $response = [];
            foreach ($query as $po) {
                $data['id'] = $po->id;
                $data['po_number'] = $po->po_number;
                $data['dispatch_date'] = $po->dispatch_date;
                $data['admin_dispatch_date'] = $po->admin_dispatch_date;
                $data['employee_name'] = $po->employee_name;
                $data['dealer_name'] = $po->dealer_name;
                $data['company_name'] = $po->firm_name;
                $data['address'] = $po->address;
                $data['gst_number'] = $po->gst_number;
                $data['dealer_phone'] = $po->dealer_phone;
                $this->db->select('*');
                $this->db->from('products_order_product');
                $this->db->join('products', 'products.id = products_order_product.product_id', 'left');
                $this->db->where('product_order_id', $po->id);
                $model = $this->db->get();
                if (!empty($model)) {
                    $data['products'] = $model->result();
                }
                $response[] = $data;
            }
            return  $response;
        } else {
            return false;
        }
    }
    public function updateProductOrderDispatch($data, $params = NULL)
    {
        if ($params != NULL) {
            $query = $this->db->update('products_order', $data, ['id' => $params]);
            if ($query) {
                return true;
            }
        }
        return false;
    }
}
