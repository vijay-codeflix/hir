<?php

class Products_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function getajaxproduct($id)
    {

        $this->db->SELECT('*');
        $this->db->from('products');
        $this->db->where('id', $id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
    function getproducts($cat_id = null, $id = null)
    {
        $this->db->SELECT('*');
        $this->db->from('products');
        if ($cat_id != NULL) {
            $this->db->where('category_id', $cat_id);
        } else if ($id != null) {
            $this->db->where('id', $id);
        }
        // $this->db->order_by('created_at', 'desc');
        $this->db->order_by("sort_order", "asc");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function deleteProduct($id)
    {
        echo $id;
        $this->db->where('id', $id);

        if ($this->db->delete('products')) {
            return 1;
        } else {
            return  0;
        }
    }
    function addProduct($data)
    {

        if ($this->db->insert('products', $data)) {
            return 1;
        } else {
            return 0;
        }
    }
    function getlastid()
    {
        $query = $this->db->select('id')->FROM('products')->LIMIT(1)->ORDER_BY('id', 'DESC')->get()->result();
        return $query;
        //            return $this->db->insert_id();
    }

    function getPoList($id)
    {
        $this->db->SELECT('products_order.*,CONCAT(users.first_name," ", users.last_name) as employee_name, dealers.dealer_name, dealers.address, dealers.gst_number, dealers.dealer_phone');
        $this->db->FROM('products_order');
        $this->db->join('dealers', 'dealers.id = products_order.dealer_id', 'left');
        $this->db->join('users', 'users.id = products_order.employee_id', 'left');
        $this->db->where('products_order.employee_id', $id);

        $result = $this->db->get();
        if (!empty($result)) {
            $query =  $result->result();
            $response = [];
            foreach ($query as $po) {
                $data['id'] = $po->id;
                $data['po_number'] = $po->po_number;
                $data['dispatch_date'] = $po->dispatch_date;
                $data['employee_name'] = $po->employee_name;
                $data['dealer_name'] = $po->dealer_name;
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

    function update()
    {
        $data = $this->input->post();
        $this->db->where('id', $this->input->post('id'));
        $query = $this->db->update('products', $data);
        if ($query != 0) {
            return true;
        } else {
            return false;
        }
    }
}
