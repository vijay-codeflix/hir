<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {
	
	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('Login_model');	  
	   if(isset($this->session->userdata['logged_in'])){
		$this->user_id = $this->session->userdata['logged_in']['userid'];
	   }
	   $this->output->enable_profiler(FALSE);
	}
	public function index()
	{
		// check user already logged in OR not 
		if($this->checkSess())
		{
			redirect(CURRENT_MODULE.'dashboard');
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}
	public function dashboard(){
		// check user already logged in OR not 
		if($this->checkSess())
		{
			$utype = $this->session->userdata['logged_in']['usertype'];
			$userId = $this->session->userdata['logged_in']['userid'];
            $globalArr = $this->session->all_userdata();
			$invalidStatus = array('ver_pending', 'sec_unselect');
			$data = array();

			if($utype == 'Super Admin')
			{		
			    $getAdminList = $this->Login_model->getUserByType(2);
				$getSubAdminList = $this->Login_model->getUserByType(3);
				$getEmpList = $this->Login_model->getUserByType(4);
				$getLiveUser = $this->Login_model->getLiveUser();
				$getAbsentUser = $this->Login_model->getAbsentUser();
				$empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            	$startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
				$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
				$sub_user_ids = $this->Login_model->getSubUser($userId);
				
				$getAttendanceReport = $this->Login_model->getAttendanceReport($empId, $startDate, $endDate, $sub_user_ids);
          
				
				
				$data['empId'] = $empId;
				$data['startDate'] = $startDate;
				$data['endDate'] = $endDate;
				$data['getEmployees'] = $this->Login_model->getUserByType(4,null,$sub_user_ids);
				$data['adminCount'] = (!$getAdminList)? 0 : count($getAdminList); 
				$data['subAdminCount'] = (!$getSubAdminList)? 0 : count($getSubAdminList); 
				$data['empCount'] = (!$getEmpList)? 0 : count($getEmpList); 
				$data['liveUserCount'] = (!$getLiveUser)? 0 : count($getLiveUser); 
				$data['absentUserCount'] = (!$getAbsentUser)? 0 : count($getAbsentUser); 
				$data['attendanceReport'] = $getAttendanceReport;
				$data['dealers'] = $this->Login_model->getDealers($empId, null, null);
				$data['users'] = $this->Login_model->getuserslist_new(null, $sub_user_ids, $userId, null);


				$this->viewAdmin('dashboard/admin',$data); // if logged in then redirect directly dashboard
			}
			else if($utype == 'Admin')
			{
				$userList = $this->Login_model->getSubUser($this->user_id);
				$userDetails = $this->Login_model->getuserslist_new(null, $userList);
				$getLiveUser = $this->Login_model->getLiveUser();
				$startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
				$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
				$sub_user_ids = $this->Login_model->getSubUser($userId);
				$empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            	$getAbsentUser = $this->Login_model->getAbsentUser();
				$getAttendanceReport = $this->Login_model->getAttendanceReport($empId, date("Y-m-d"), $endDate, $sub_user_ids);
          
				
				$subAdminCount = 0;
				$empCount = 0;
				if($userDetails != false){
					foreach ($userDetails as $key => $value) {
						if($value->user_type == 3){
							$subAdminCount++;	
						}
						if($value->user_type == 4){
							$empCount++;	
						}
					}
				}
				$data['subAdminCount'] = $subAdminCount;
				$data['empCount'] = $empCount; 
				$data['liveUserCount'] = (!$getLiveUser)? 0 : count($getLiveUser); 
				$data['absentUserCount'] = (!$getAbsentUser)? 0 : count($getAbsentUser); 
				$data['attendanceReport'] = $getAttendanceReport;
				$data['dealers'] = $this->Login_model->getDealers($empId, null, null);
				$data['users'] = $this->Login_model->getuserslist_new(null, $sub_user_ids, $userId, null);

				
				$this->viewAdmin('dashboard/admin',$data);
			}
			else if($utype == 'Sub Admin')
			{
				$userList = $this->Login_model->getSubUser($this->user_id);
				$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
				$empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            	$userDetails = $this->Login_model->getuserslist_new(null, $userList);
				$getLiveUser = $this->Login_model->getLiveUser();
				$getAbsentUser = $this->Login_model->getAbsentUser();
				$sub_user_ids = $this->Login_model->getSubUser($userId);
				$getAttendanceReport = $this->Login_model->getAttendanceReport($empId, '','', $sub_user_ids);
          
				
				$subAdminCount = 0;
				$empCount = 0;
				if($userDetails != false){
					foreach ($userDetails as $key => $value) {
						if($value->user_type == 3){
							$subAdminCount++;	
						}
						if($value->user_type == 4){
							$empCount++;	
						}
					}
				}
				$data['subAdminCount'] = $subAdminCount;
				$data['empCount'] = $empCount; 
				$data['liveUserCount'] = (!$getLiveUser)? 0 : count($getLiveUser); 
				$data['absentUserCount'] = (!$getAbsentUser)? 0 : count($getAbsentUser);
			
				$data['attendanceReport'] = $getAttendanceReport;
				$data['dealers'] = $this->Login_model->getDealers($empId, null, null);
				$data['users'] = $this->Login_model->getuserslist_new(null, $sub_user_ids, $userId, null);

				
				$this->viewAdmin('dashboard/admin', $data);

			}else{
			 //   print_r('asdas');exit;
				redirect(CURRENT_MODULE.'login'); // If not logged in then goto login screen
			}

		}else{
			redirect(CURRENT_MODULE.'login'); // If not logged in then goto login screen
		}
	}
    
	public function login()
	{
		$this->form_validation->set_rules('login-username', 'Username', 'trim|required|strip_tags|xss_clean');
		//"check_database()":   calling here call back function to check username and password
	    $this->form_validation->set_rules('login-password', 'Password', 'trim|required|strip_tags|xss_clean|callback_check_database'); 
	 			
	    if($this->form_validation->run() == FALSE && !$this->checkSess())
	    {
	    	// If user name & password in incorrect AND user session not set then goto login screen
	    	$this->load->view('login');	// If not logged in then goto login screen
	    }
	    else
	    {
	    	// If user name and password correct then goto dashboard
	      	redirect(CURRENT_MODULE);
	    }
	}
	public function check_database($password)
	{
	   //Field validation succeeded.  Validate against database
	   $username = $this->input->post('login-username');
	 
	   //query the database
	   $result = $this->Login_model->login($username, $password);
	   if($result)
	   {
	   		if($result[0]->user_type == "Employee"){
	   			// If user status is Inactive then return message
			    $this->form_validation->set_message('check_database', 'Your not allowed to login.');
			    return false;

	   		}else{
	   			$sess_array = array();
			     foreach($result as $row)
			     {
			     	
			       $sess_array = array(
			         'userid' 		=>	 $row->id,
			         'usertype' 	=>	 $row->user_type,
			         'username' 	=>	 $row->email,
			         'password' 	=> 	 $row->password,
			         'user_fname' 	=>	 $row->first_name,
			         'user_lname' 	=>	 $row->last_name,
			       );
			       // Here set session values of logged in user's
			       $this->session->set_userdata('logged_in', $sess_array);
			     }
			     return TRUE;
	   		}	     
	   }else{
	   	 // If username & password not match then return error message
	     $this->form_validation->set_message('check_database', 'Invalid username or password.');
	     return false;
	   }

	}

	public function changepassword(){
		if($this->checkSess())
		{			
			$this->viewAdmin('changepassword');

		} else {
			// If user name and password correct then goto dashboard
	      	redirect(CURRENT_MODULE);
		}
	} 
	public function savepassword(){
		if($this->checkSess())
		{
			$utype = $this->session->userdata['logged_in']['usertype'];
			$this->form_validation->set_rules('password', 'Password', 'trim|required|strip_tags|xss_clean|max_length[16]|min_length[6]');
			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|strip_tags|xss_clean|max_length[16]|min_length[6]');

			if($this->form_validation->run() == FALSE){
				$this->viewAdmin('changepassword');
			} else {
				
				if($this->Login_model->changepassword($utype) == TRUE)
		    	 {	                    
			    	$message = str_replace('{name}', 'password', UPDATE_SUCCESS);
			        $this->session->set_flashdata('flash_message_success', $message);
			        redirect(CURRENT_MODULE.'dashboard');
	             }
	             else
	             {
	                $this->session->set_flashdata('flash_message', UNSUCCESS);
	                redirect(CURRENT_MODULE.'changepassword');
	             }
            	 								    	
			}

		} else {
			// If user name and password correct then goto dashboard
	      	redirect(CURRENT_MODULE);
		}
	} 
}