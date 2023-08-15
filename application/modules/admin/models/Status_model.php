<?php

class Status_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getStatusList()
    {
        $this->db->SELECT('*');
        $this->db->FROM('statuses');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
}
