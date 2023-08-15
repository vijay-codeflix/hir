<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Expenses extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('expenses_model');
	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->categories();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function categories(){
    	if($this->checkSess()){
            $data['catview'] = $this->expenses_model->getCatList();
            $this->viewAdmin('admin/expenses/catview', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	function categoryadd(){
		if($this->checkSess()){	
			$this->viewAdmin('admin/expenses/add');
		}else{
			$this->index();
		}
	}
	function categoryedit($id){
		if($id){
			if($this->checkSess()){	
                $data['catDetails'] = $this->expenses_model->getCatList(base64_decode($id));
                $this->viewAdmin('admin/expenses/edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'expenses/categories');
		}
	}
	
	public function getCatCount($id = NULL){
		if(is_null($id)){
			echo json_encode(array());
		}else{
			$id = base64_decode($id);
			$data = array();
			$expCat = $this->expenses_model->getExpenseItemByCategory($id);
			$data['expCat'] = $expCat;
			$grade = $this->expenses_model->getEmpGradeByCat($id);
			$data['grade'] = $grade;
			if($grade == false && $expCat == false){
				$data['count'] = 0;
			}else{
				$data['count'] = 1;
			}
			echo json_encode($data);
		}
		
	}
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->expenses_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'expenses/categories');
	}	
	
	public function viewApprovalStatus($id = NULL){
		if($this->checkSess() && !is_null($id)){
			$getExpDetails = $this->expenses_model->getExpenseList($id,NULL,NULL,NULL,NULL,NULL);
			if($getExpDetails != false){
				$empId = $getExpDetails[0]->empId;
				$getParentList = $this->expenses_model->getParentList($empId);
				if(is_null($getParentList) || count($getParentList) == 0){
					echo json_encode(array('error' => true, 'message' => "Employee not exist."));
				}else{
					$res = $this->expenses_model->getSubUser($this->user_id);
					$users = $this->expenses_model->getuserslist_new(null, $res, $this->user_id, $this->type);
					if($users != false){
						$userIds = array();
						$userDetailValue = array();
						foreach ($users as $key => $value) {
							if(in_array($value->id, $getParentList)){
								$userIds[] = $value->id;
								$userDetailValue[$value->id] = $value->first_name." ".$value->last_name;
							}
						}
						$approvedList = $this->expenses_model->getExpensesByUserIds($userIds, $id);
						$appList = array();
						$sendDataArray = array();
						$sendDataapproved = array();
						if($approvedList != false){
							foreach ($approvedList as $key1 => $value1) {
								$appList[] = $value1->uid;
								$sendDataapproved[$value1->uid] = array('name' => $userDetailValue[$value1->uid], "status" => $value1->status, "amount"=>$value1->approved_amount, "reason"=> $value1->reason );
							}
						}
						sort($sendDataapproved);
						foreach ($userIds as $value2) {
							if(!in_array($value2, $appList)){
								$sendDataArray[] = array('name' => $userDetailValue[$value2], "status" => "pending");
							}
						}
						$sendDataArray = array_merge($sendDataArray, $sendDataapproved);				
						echo json_encode($sendDataArray);
					}else{
						echo json_encode(array('error' => true, "message" => "No child users found to show for this expense.")); //maybe we need to change condition here in - future
					}
				}
			}else{
				echo json_encode(array('error' => true,'message' => "Expense details not found, please contact to administrator."));
			}
		}else{
			echo json_encode(array('error' => true, "message" => "Getting error to fetch expense details." ));
		}
	}

	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('type', 'Category type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('name', 'Category name', 'trim|required|xss_clean|is_unique[categories.name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->categoryadd();
			}
			else
			{				
				$result = $this->expenses_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/expenses');

				}else{
					$this->session->set_flashdata('flash_message', 'Data not inserted');
					$this->index();
				}
			}
		}
		else
		{
			$this->index();	
		}
	}
	public function update(){
		if($this->checkSess()){
			$id = str_replace('/', '_',rtrim(base64_encode($this->input->post('id')), '=')); 
			//Set Rule for Validation
			$this->form_validation->set_rules('type', 'Category type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('name', 'Category name', 'trim|required|xss_clean|callback_checkDuplicate[Category name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->categoryedit($id);
			}
			else
			{				
				$result = $this->expenses_model->update();

				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/expenses');

				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
					$this->index();
				}
			}
		}
		else
		{
			$this->index();	
		}
	}

    public function checkDuplicate($para1, $para2){
    	$res = $this->expenses_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }

	/**Expense List */
	public function list(){
		if($this->checkSess()){
			$this->form_validation->set_rules('select_employee', 'select_employee', 'trim|xss_clean');	

			//pr($_POST);
			$empId = (isset($_POST['select_employee']))? $this->input->post('select_employee') : NULL;
			//echo $empId."__".gettype($empId);exit;
			$catId = (isset($_POST['select_category']))? $_POST['select_category'] : NULL;
			$statusVal = (isset($_POST['select_status']))? $_POST['select_status'] : NULL;
			$startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
			
			$data['selectStatus'] = $statusVal;
			$data['empId'] = $empId;
			$data['catId'] = $catId;
			$data['startDate'] = $startDate;
			$data['endDate'] = $endDate;

			$data['getCategories'] = $this->expenses_model->getCatList();
			/*Get login sub admin employee and sub to sub admin employee*/

			$userId = $this->user_id;
			$type = $this->session->userdata['logged_in']['usertype'];
			
			$getUserDetails = $this->expenses_model->getUserById($userId);

			$res = $this->expenses_model->getSubUser($userId);
			$userDetails = $this->expenses_model->getuserslist_new(null, $res, $userId, $type);
			$get_all_employee = [];
			if($userDetails != false){
				foreach ($userDetails as $key => $value) {
					if($value->user_type == 4){
						$get_all_employee[] = $value;
					}
				}
			}
			$get_all_sub_admin = $this->expenses_model->getSubadmin($userId);
			
			//$get_all_employee = $this->expenses_model->getEmployee($get_all_sub_admin);
			

			/*End*/
			$data['getEmployees'] = $get_all_employee; //$this->expenses_model->getUserByType(4);
			$data['getStatus'] = array('approved','pending','rejected','partial-approved');
			//$data['expenses'] = $this->expenses_model->getExpenseList(NULL, $empId, $catId, $statusVal, $startDate, $endDate);
			$data['expenses'] = $this->expenses_model->getExpenseList_subadmin(NULL, $empId, $catId, $statusVal, $startDate, $endDate, $get_all_sub_admin);
			//pr($data['expenses'],1);
            $this->viewAdmin('admin/expenses/expenseView', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function getExpenseDetails(){
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$result  = $this->expenses_model->getExpenseList($id,NULL,NULL,NULL,NULL,NULL);
			echo json_encode($result);
		}
	}

	public function expenseAction($id = NULL, $status = NULL){
		$reason = '';
		$amount = '';
		$expenseDetails = $this->expenses_model->getExpenseItem($id);
		if($status == 'partial-approved'){
			$amount = $_POST['amount'];
			$reason = $_POST['reason'];
			if($amount == '' || $reason == ''){
				$this->session->set_flashdata('validation', 'Amount and reason are required fields');
				redirect(BASE_URL.'admin/expenses/list');
			}

			if(!is_numeric($amount)){
				$this->session->set_flashdata('validation', 'Amount should be number');
				redirect(BASE_URL.'admin/expenses/list');
			}
		}else if($status == 'rejected'){
			$reason = $_POST['reason'];
			$amount = 0;//$expenseDetails[0]->requested_amount;
			if($reason == ''){
				$this->session->set_flashdata('validation', 'Reason is required field');
				redirect(BASE_URL.'admin/expenses/list');
			}
			
			if(!is_numeric($amount)){
				$this->session->set_flashdata('validation', 'Amount should be number');
				redirect(BASE_URL.'admin/expenses/list');
			}
		}else if($status == 'partial-approved_by_super_admin'){
            $status = 'partial-approved';
            $reason = $_POST['reason'];
            $amount = $_POST['amount'];
            if($reason == ''){
                $this->session->set_flashdata('validation', 'Reason is required field');
                redirect(BASE_URL.'admin/expenses/list');
            }

            if(!is_numeric($amount)){
                $this->session->set_flashdata('validation', 'Amount should be number');
                redirect(BASE_URL.'admin/expenses/list');
            }
        }else if($status == 'partial-approved_by_admin'){
            $status = 'partial-approved';
            $reason = $_POST['reason'];
            $amount = $_POST['amount'];//$expenseDetails[0]->requested_amount;
            if($reason == ''){
                $this->session->set_flashdata('validation', 'Reason is required field');
                redirect(BASE_URL.'admin/expenses/list');
            }

            if(!is_numeric($amount)){
                $this->session->set_flashdata('validation', 'Amount should be number');
                redirect(BASE_URL.'admin/expenses/list');
            }
        }else if($status == 'approved_by_super_admin'){
            $status = 'approved';
            $amount = $expenseDetails[0]->requested_amount;

        }else if($status == 'approved_by_admin'){
            $status = 'approved';
            $amount = $expenseDetails[0]->requested_amount;

        }else{
			$amount = $expenseDetails[0]->requested_amount;
		}

		if(($this->session->userdata['logged_in']['usertype'] == 2 || $this->session->userdata['logged_in']['usertype'] == "Admin")){
			$para = array('status' => $status, 'approved_amount' => $amount, 'reason' => $reason);	
			$result = $this->expenses_model->updateExpenseAction($para, $id);
		}else if(($this->session->userdata['logged_in']['usertype'] == 1 || $this->session->userdata['logged_in']['usertype'] == "Super Admin")){
			$para = array('status' => $status, 'approved_amount' => $amount, 'reason' => $reason);
			$result = $this->expenses_model->updateExpenseAction($para, $id);
		}else if($status == 'rejected'){
			$para = array('status' => $status, 'approved_amount' => $amount, 'reason' => $reason);	
			$result = $this->expenses_model->updateExpenseAction($para, $id);
		}
		

		$parent_id = $this->expenses_model->get_user_parent_id($this->session->userdata['logged_in']['userid']);
		if($parent_id == NULL){
			$parent_id = 0;
		}

		$approved_para = array('status' => $status, 'approved_date' => date("Y-m-d H:i:s"), 'expenses_id' => $id,'employee_id' => $expenseDetails[0]->employee_id,'approved_user_id' => $this->session->userdata['logged_in']['userid'],'approved_user_parent_id' => $parent_id, 'approved_amount' => $amount, 'reason' => $reason);

		$approved_result = $this->expenses_model->expenses_approved_process($approved_para);
		$this->session->set_flashdata('flash_message', 'updated');


		redirect(BASE_URL.'admin/expenses/list');
	}
	
	public function expenseExportCSV(){
		if($this->checkSess()){
			$empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
			$catId = (isset($_POST['select_category']))? $_POST['select_category'] : NULL;
			$statusVal = (isset($_POST['select_status']))? $_POST['select_status'] : NULL;
            $startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
          
			$reports = $this->expenses_model->getExpenseList_subadmin(NULL, $empId, $catId, $statusVal, $startDate, $endDate);
	       // pr($reports);
			$filename = 'Expense_'.strtotime('now').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");
			// file creation 
			$file = fopen('php://output', 'w');
			
			$header = array("EMPLOYEE NAME","EMPLOYEE GRADE","EMPLOYEE PHONE","CATEGORY","REQUESTED AMOUNT","APPROVED AMOUNT","ALLOWED AMOUNT","PLACE", "PLACE GRADE","STATUS","DATE"); 
			fputcsv($file, $header);

// 			print_r($reports);exit;
			if($reports){
				foreach ($reports as $key=>$line){ 
					$newData = array('name' => ucfirst($line->empName), 'empGrade' => $line->empGrade, 'phone' => $line->phone,'catName' => ucfirst($line->catName),'reqAmount' => $line->reqAmount,'approveAmount' => $line->approveAmount,'allowAmount' => $line->allowAmount,'place' => $line->place, 'ctGrade' => $line->ctGrade,'status' => ucwords($line->status), 'created_at' => date(DEFAULT_DATE_FORMAT,strtotime($line->created_at)));
					fputcsv($file, $newData); 
				} 
			}
			fclose($file); 
			exit;
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}
}