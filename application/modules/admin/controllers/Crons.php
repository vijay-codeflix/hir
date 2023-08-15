<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Crons extends CI_Controller {
    public function __construct()
	{
	   parent::__construct();
	}
	
	
	function autopuchout(){
	    
	    if (date('H') >= 23 && date('i') >= 54) {
	        $punchInVal = array("location" => array("type" => "Point", "coordinates" => array()), "meter_reading_photo" => 0,  "date" => date('Y-m-d H:i:s'), "place" => 'Auto Punch out', "meter_reading_in_km" => 0);
            $punchInVal = json_encode($punchInVal);
	        $this->db->set('punch_out_date', date('Y-m-d'));
    	    $this->db->set('punch_out', $punchInVal);
            $this->db->where('punch_out',NULL);
            $this->db->update('employee_punch_details');
	    }
	    return false;
	    
	}
}