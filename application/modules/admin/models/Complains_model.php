<?php

class Complains_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function all()
    {
        $table_data = array();
        $this->db->select('complains.id,dealers.dealer_name,users.first_name , users.last_name,complain_type.name,complains.complain_no,complains.status_id, statuses.name as status_name,complains.closed_by,complains.Date,complains.remark, complains.admin_remark');
        $this->db->from('complains');
        $this->db->join('users', 'users.id = complains.user_id');
        $this->db->join('statuses', 'statuses.id = complains.status_id');
        $this->db->join('complain_type', 'complain_type.id = complains.complain_type_id');
        $this->db->join('dealers', 'dealers.id = complains.party_id', 'left');
        $this->db->order_by("complains.id", "desc");
        $table_data['data'] = $this->db->get()->result_array();
        //            echo "<pre>";
        //            print_r($table_data);
        //            exit();
        return $table_data;
    }
    function getComplainType()
    {
        $this->db->SELECT('*');
        $this->db->FROM('complain_type');
        return $this->db->get()->result_array();
    }
    function getNextComplainNumber()
    {
        $query = $this->db->select('id')->FROM('complains')->LIMIT(1)->ORDER_BY('id', 'DESC')->get()->result();
        //        return  $query;
        if (!empty($query)) {

            return (string)date('Ym') . (string)$query[0]->id;
        } else {
            return (string)date('Ym') . '0';
        }
    }

    function insert($reqPara)
    {
        if ($this->db->insert('complains', $reqPara)) {
            return 1;
        } else {
            return 0;
        }
    }

    function update($reqPara)
    {
        $this->db->where('id', $reqPara['id']);
        $query = $this->db->update('complains', $reqPara);
        if ($query != 0) {
            return true;
        } else {
            return false;
        }
    }

    function getComplaintById($id)
    {
        $this->db->SELECT('*');
        $this->db->FROM('complains')->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}
