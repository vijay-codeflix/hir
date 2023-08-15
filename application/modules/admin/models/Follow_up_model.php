<?php

class Follow_up_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_follow_up($id)
    {
        $table_data = array();
        $this->db->select('follow_up.id,follow_up.number,users.first_name , users.last_name, follow_up.submit_date,dealers.firm_name,follow_up_type.name,follow_up.remark,follow_up.follow_up_date');
        $this->db->from('follow_up');
        $this->db->join('users', 'users.id = follow_up.employee_id');
        $this->db->join('follow_up_type', 'follow_up_type.id = follow_up.follow_up_type_id');
        $this->db->join('dealers', 'dealers.id = follow_up.party_id');
        if ($id == null) {
            $table_data = $this->db->get()->result_array();
            return $table_data;
        } else {
            $this->db->where('follow_up.id', $id);
            $table_data = $this->db->get()->result_array();
            return $table_data;
        }
    }
    function get_type_data()
    {
        $table_data = array();
        $this->db->select('*');
        $this->db->from('follow_up_type');
        return $this->db->get()->result_array();
    }

    function insert($reqPara)
    {
        if ($this->db->insert('follow_up', $reqPara)) {
            return 1;
        } else {
            return 0;
        }
    }

    function getNextFollowUpNumber()
    {
        $query = $this->db->select('id')->FROM('follow_up')->LIMIT(1)->ORDER_BY('id', 'DESC')->get()->result();
        //        return  $query;
        if (!empty($query)) {

            return (string)date('Ym') . (string)$query[0]->id;
        } else {
            return (string)date('Ym') . '0';
        }
    }
}
