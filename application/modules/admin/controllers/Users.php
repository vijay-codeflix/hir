<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{

	public $user_id;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->user_id = $this->session->userdata['logged_in']['userid'];
		// $this->output->enable_profiler(TRUE);
	}

	public function index($userId = NULL)
	{
		if ($this->checkSess()) {
			if (is_null($userId)) {
				$userId = $this->user_id;
				$type = $this->session->userdata['logged_in']['usertype'];
			} else {
				$userId = base64_decode($userId);
				$type = "Sub Admin";
			}
			$getUserDetails = $this->users_model->getUserById($userId);
			$parent_id = NULL;
			if ($getUserDetails[0]->parent_id != 0) {
				$parent_id = '/index/' . base64_encode($getUserDetails[0]->parent_id);
			}
			$this->view($userId, $type, $parent_id);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function view($userId = null, $type = null, $parent_id = null)
	{
		if ($this->checkSess()) {
			//$res = $this->users_model->getAllChield(array($this->user_id));
			if (is_null($userId)) {
				$userId = $this->user_id;
			}

			if (!empty($_POST) && $_POST['parent_id']) {
				$res = $this->users_model->getSubUser($_POST['parent_id']);
			} else {
				$res = $this->users_model->getSubUser($userId);
			}
			// print_r($_POST);
			// exit();
			$data['parent'] = $parent_id;
			$data['getSubAdmins'] = $this->users_model->getSubAdmins_new(array($this->user_id), $res, 3);
			$data['users'] = $this->users_model->getuserslist_new(null, $res, $userId, $type);
			$this->viewAdmin('admin/users/view', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function archiveuser()
	{
		if ($this->checkSess()) {
			$type = $this->session->userdata['logged_in']['usertype'];
			if ($type == 'Super Admin' || $type == 'Admin') {
				$data['users'] = $this->users_model->getarchiveuserlist();
				$this->viewAdmin('admin/users/archiveuser', $data);
			} else {
				redirect(CURRENT_MODULE . 'users');
			}
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}



	/**Employee grade functions */
	public function employeeGrade()
	{
		if ($this->checkSess()) {
			$data['grades'] = $this->users_model->getEmpGradelist();
			$this->viewAdmin('admin/users/gradeview', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function addEmployeeGrade()
	{
		if ($this->checkSess()) {
			$data['categories'] = $this->users_model->getCatList();
			$data['cityGrades'] = array('A', 'B', 'C', 'D');
			$this->viewAdmin('admin/users/gradeAdd', $data);
		} else {
			$this->employeeGrade();
		}
	}

	public function insertEmployeeGrade()
	{
		if ($this->checkSess()) {
			//Set Rule for Validation
			$this->form_validation->set_rules('grade', 'Grade name', 'trim|required|xss_clean|is_unique[emp_grade.grade]');

			if ($this->form_validation->run() === FALSE) {
				$this->addEmployeeGrade();
			} else {
				$gradeId = $this->users_model->insertGrade();
				unset($_POST['grade']); //unset grade name from array

				$gradeDetails = $this->input->post();
				if ($gradeDetails) {
					$gradeInsertDetails = array();
					foreach ($gradeDetails as $key => $value) {
						$splitVal = explode('_99_', $key);
						$gradeInsertDetails[] = array('grade_id' => $gradeId, 'city_grade' => $splitVal[0], 'category_id' => $splitVal[1], 'amount' => $value);
					}
					$response = $this->users_model->insertGradeDetails($gradeInsertDetails);

					if ($response) {
						$this->session->set_flashdata('flash_message', 'inserted');
						redirect(BASE_URL . 'admin/users/employeeGrade');
					} else {
						$this->session->set_flashdata('flash_message', 'Data not inserted');
						redirect(BASE_URL . 'admin/users/gradeAdd');
					}
				}
			}
		} else {
			$this->index();
		}
	}

	public function editEmployeeGrade($id = NULL)
	{
		if ($this->checkSess()) {
			$data['categories'] = $this->users_model->getCatList();
			$data['cityGrades'] = array('A', 'B', 'C', 'D');
			$data['gradeView'] = $this->users_model->getGradeById($id);
			//pr($data,1);
			$data['type'] = 'edit';
			$this->viewAdmin('admin/users/gradeEdit', $data);
		} else {
			$this->index();
		}
	}

	public function viewEmployeeGrade($id = NULL)
	{
		if ($this->checkSess()) {
			$data['categories'] = $this->users_model->getCatList();
			$data['cityGrades'] = array('A', 'B', 'C', 'D');
			$data['type'] = 'view';
			$data['gradeView'] = $this->users_model->getGradeById($id);
			//pr($data,1);
			$this->viewAdmin('admin/users/gradeEdit', $data);
		} else {
			$this->index();
		}
	}

	public function updateEmployeeGrade()
	{
		if ($this->checkSess()) {
			$gid = $this->input->post('id');
			$id = str_replace('/', '_', rtrim(base64_encode($this->input->post('id')), '='));
			//Set Rule for Validation
			$this->form_validation->set_rules('grade', 'Grade Name', 'trim|required|xss_clean');
			if ($this->form_validation->run() === FALSE) {
				$this->editEmployeeGrade($id);
			} else {
				unset($_POST['grade']); //unset grade name from array
				unset($_POST['id']); //unset id name from array

				$gradeDetails = $this->input->post();
				if ($gradeDetails) {
					$gradeInsertDetails = array();
					$gradeUpdateDetails = array();
					foreach ($gradeDetails as $key => $value) {
						$splitVal = explode('_99_', $key);

						if (count($splitVal) > 2) { //update that record
							$gradeUpdateDetails[] = array('id' => $splitVal[2], 'grade_id' => $gid, 'city_grade' => $splitVal[0], 'category_id' => $splitVal[1], 'amount' => $value);
						} else { //insert new record
							$gradeInsertDetails[] = array('grade_id' => $gid, 'city_grade' => $splitVal[0], 'category_id' => $splitVal[1], 'amount' => $value);
						}
					}
					$result = false;

					if ($gradeInsertDetails) {
						$response = $this->users_model->insertGradeDetails($gradeInsertDetails);
						if ($response) {
							$result = true;
						}
					}

					if ($gradeUpdateDetails) {
						$response2 = $this->users_model->insertGradeDetails($gradeUpdateDetails, 'update');
						$result = true;
					}

					if ($result) {
						$this->session->set_flashdata('flash_message', 'updated');
						redirect(BASE_URL . 'admin/users/employeeGrade');
					} else {
						$this->session->set_flashdata('flash_message', 'Data not updated');
						redirect(BASE_URL . 'admin/users/editEmployeeGrade/' . $id);
					}
				}
			}
		} else {
			$this->index();
		}
	}

	public function deleteGrade($id)
	{
		$id = base64_decode($id);
		if ($this->users_model->deleteGrade($id) == TRUE) {
			$this->session->set_flashdata('flash_message', 'Deleted');
		} else {
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE . 'users/employeeGrade');
	}
	/**END- emp grade */

	public function attendanceList($method = NULL)
	{
		if ($this->checkSess()) {
			$data['method'] = ($method == NULL) ? 'punch-in' : $method;

			$data['users'] = $this->users_model->getAttendanceList($data['method']);
			$this->viewAdmin('admin/users/attendance', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function add()
	{
		// check user already logged in OR not
		if ($this->checkSess()) {
			// 			if($this->session->userdata['logged_in']['usertype'] == 'Sub Admin'){
			// 				$this->session->set_flashdata('flash_message', 'add_permission');
			// 				redirect(BASE_URL.'admin/users/');//view
			// 				return;
			// 			}
			$data['usertype'] = $this->users_model->getUserType();
			//$new_ids = $this->users_model->getSubadminIds($this->user_id);
			$new_ids = $this->users_model->getSubUser($this->user_id);
			//$data['getSubAdmins'] = $this->users_model->getSubAdmins(array($this->user_id));
			$data['getSubAdmins'] = $this->users_model->getSubAdmins_new(array($this->user_id), $new_ids, 3);
			// echo $this->db->last_query('data');
			// exit;
			$data['gradeList'] = $this->users_model->getGradeList();
			$this->viewAdmin('admin/users/add', $data);
		} else {
			$this->index();
		}
	}

	public function insert()
	{

		if ($this->checkSess()) {
			//Set Rule for Validation
			$this->form_validation->set_rules('user_type', 'User Type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');


			$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');
			if ($this->input->post('user_type') == 3) {
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|callback_checkIsDeletedDuplicate[Email]|is_unique[users.email]');
				$this->form_validation->set_message('checkIsDeletedDuplicate', "This %s is already in use!");
			} else {
				$this->form_validation->set_rules('grade_id', 'Grade', 'trim|required|xss_clean');
				if ($this->input->post('user_type') == 4) {
					$this->form_validation->set_rules('parent_id', 'Parent ID', 'trim|required|xss_clean');
				}
				$this->form_validation->set_rules('emp_id', 'Employee ID', 'trim|required|xss_clean|is_unique[users.emp_id]');
				$this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean|callback_checkIsDeletedDuplicate[Phone]|is_unique[users.phone]');
				$this->form_validation->set_message('checkIsDeletedDuplicate', "This %s is already in use!");
			}

			if ($this->form_validation->run() === FALSE) {
				//echo validation_errors();		
				$this->add();
			} else {
				/* Add limit*/
				/* Get limit for employe add and subadmin add*/
				$data = $this->input->post();
				$limit = 0;
				$current_user_total = 0;
				// echo $this->session->userdata['logged_in']['usertype']; //Super Admin or admin
				// exit;
				if ($this->session->userdata['logged_in']['usertype'] == 'Sub Admin') {
					$this->session->set_flashdata('flash_message', 'add_permission');
					redirect(BASE_URL . 'admin/users/'); //view
					return;
				}


				if ($this->session->userdata['logged_in']['usertype'] !== 'Super Admin') {
					if ($data['user_type'] == 3) {
						$limit = $this->users_model->getSiteSetting('sub_admin_add_limit');
						$current_user_total = $this->users_model->gettotaluser($data['user_type']);
					} elseif ($data['user_type'] == 4) {
						$limit = $this->users_model->getSiteSetting('employee_add_limit');
						$current_user_total = $this->users_model->gettotaluser($data['user_type']);
					}
				} else {
					$limit = 10;
					$current_user_total = 1;
				}
				// print_r($limit);
				// echo $current_user_total."</br>";
				// exit;
				if ($current_user_total >=  $limit) {
					//echo "data"; exit;
					$this->session->set_flashdata('flash_message', 'add_limit');
					redirect(BASE_URL . 'admin/users/'); //view
					return;
				}



				$result = $this->users_model->insert();

				if ($result) {
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL . 'admin/users/'); //view

				} else {
					$this->session->set_flashdata('flash_message', 'Data not inserted');
					redirect(BASE_URL . 'admin/users/add');
				}
			}
		} else {
			$this->index();
		}
	}

	public function checkIsDeletedDuplicate($para1, $para2)
	{
		$res = $this->users_model->checkIsDeletedDuplicate($para1, $para2);

		if ($res == 'FALSE') {
			$this->form_validation->set_message('checkIsDeletedDuplicate', 'This ' . $para2 . ' is already used, Please choose different');
			return false;
		} else {
			return true;
		}
	}

	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		if ($this->session->userdata['logged_in']['usertype'] == 'Sub Admin') {
			$this->session->set_flashdata('flash_message', 'add_permission');
			redirect(BASE_URL . 'admin/users/'); //view
			return;
		}
		/*Number of child users*/
		if ($this->users_model->child_user_count($id) == TRUE) {

			if ($this->users_model->delete($id) == TRUE) {
				$this->session->set_flashdata('flash_message', 'Deleted');
			} else {
				$this->session->set_flashdata('flash_message', 'not_deleted');
			}
		} else {
			$this->session->set_flashdata('flash_message', 'not_deleted_child');
		}
		redirect(CURRENT_MODULE . 'users/view');
	}

	function edit($id = NULL)
	{
		if ($id) {
			if ($this->checkSess()) {
				if ($this->session->userdata['logged_in']['usertype'] == 'Sub Admin') {
					$this->session->set_flashdata('flash_message', 'add_permission');
					redirect(BASE_URL . 'admin/users/'); //view
					return;
				}
				$data['usertype'] = $this->users_model->getUserType();
				$data['getSubAdmins'] = $this->users_model->getSubAdmins(array($this->user_id, base64_decode($id)));
				$data['gradeList'] = $this->users_model->getGradeList();
				$data['users'] = $this->users_model->getUserById(base64_decode($id));

				// pr($data,1);

				$this->viewAdmin('admin/users/edit', $data);
			} else {
				$this->index();
			}
		} else {
			redirect(CURRENT_MODULE . 'users/');
		}
	}

	public function update()
	{
		if ($this->checkSess()) {
			if ($this->session->userdata['logged_in']['usertype'] == 'Sub Admin') {
				$this->session->set_flashdata('flash_message', 'add_permission');
				redirect(BASE_URL . 'admin/users/'); //view
				return;
			}
			// 			pr($this->input->post(), 1);
			$userId = str_replace('/', '_', rtrim(base64_encode($this->input->post('id')), '='));

			//Set Rule for Validation
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');

			$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');

			$this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean');

			if ($this->input->post('user_type') == 3) {
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			} else if ($this->input->post('user_type') == 4) {
				$this->form_validation->set_rules('grade_id', 'Grade', 'trim|required|xss_clean');
				$this->form_validation->set_rules('parent_id', 'Parent ID', 'trim|required|xss_clean');
				$this->form_validation->set_rules('emp_id', 'Employee ID', 'trim|required|xss_clean|callback_checkDuplicate[Employee ID]');
				$this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean|callback_checkIsDeletedDuplicate[Phone]|edit_unique[users.phone.' . $this->input->post('id') . ']');
				$this->form_validation->set_message('checkIsDeletedDuplicate', "This %s is already in use!");
			}
			if ($this->form_validation->run() === FALSE) {
				//echo validation_errors();	
				// pr($this->form_validation->error_array(),1);
				$this->edit($userId);
			} else {
				$result = $this->users_model->update();

				if ($result) {
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL . 'admin/users/');
				} else {
					$this->session->set_flashdata('flash_message', 'not_updated');
					$this->index();
				}
			}
		} else {
			$this->index();
		}
	}

	public function checkDuplicate($para1, $para2)
	{
		$res = $this->users_model->checkDuplicate($para1, $para2);

		if ($res == 'FALSE') {
			$this->form_validation->set_message('checkDuplicate', 'This ' . $para2 . ' is already used, Please choose diffrent');
			return false;
		} else {
			return true;
		}
	}

	public function checkDuplicateGrade($para1, $para2)
	{
		$res = $this->users_model->checkDuplicateGrade($para1, $para2);

		if ($res == 'FALSE') {
			$this->form_validation->set_message('checkDuplicateGrade', 'This ' . $para2 . ' is already used, Please choose diffrent');
			return false;
		} else {
			return true;
		}
	}

	public function validFunction()
	{

		if ($this->checkPermission($_POST['type'])) {
			echo 'true';
		} else {
			echo 'false';
		}
	}

	public function getCurrentUser()
	{
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			$result  = $this->users_model->getuserslist($id);
			echo json_encode($result);
		}
	}

	public function attendanceReport()
	{
		if ($this->checkSess()) {
			$empId = (isset($_POST['select_employee'])) ? $_POST['select_employee'] : NULL;
			$startDate = (isset($_POST['start_date'])) ? str_replace("/", "-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date'])) ? str_replace("/", "-", $_POST['end_date']) : "";

			$data['empId'] = $empId;
			$data['startDate'] = $startDate;
			$data['endDate'] = $endDate;
			$userId = $this->session->userdata['logged_in']['userid'];
			$sub_user_ids = $this->users_model->getSubUser($userId);
			$data['getEmployees'] = $this->users_model->getUserByType(4, null, $sub_user_ids);
			$data['reports'] = $this->users_model->getAttendanceReport($empId, $startDate, $endDate, $sub_user_ids);
			//pr( $data['reports'],1),;
			$this->viewAdmin('admin/users/report', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}


	public function absentReport()
	{
		if ($this->checkSess()) {
			$first_day_this_month = date('01-m-Y'); // hard-coded '01' for first day
			$last_day_this_month  = date('t-m-Y');
			$empId = (isset($_POST['select_employee'])) ? $_POST['select_employee'] : NULL;
			$startDate = (isset($_POST['start_date'])) ? str_replace("/", "-", $_POST['start_date']) : $first_day_this_month;
			$endDate = (isset($_POST['end_date'])) ? str_replace("/", "-", $_POST['end_date']) : $last_day_this_month;

			$data['empId'] = $empId;
			$data['startDate'] = $startDate;
			$data['endDate'] = $endDate;
			$data['getEmployees'] = $this->users_model->getUserByType(4);
			$data['reports'] = $this->users_model->getAbsentReport($empId, $startDate, $endDate);
			//pr( $data['reports'],1);
			$this->viewAdmin('admin/users/absentreport', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}


	public function locationReport()
	{
		/*if($this->checkSess()){
            $empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            $date = (isset($_POST['date']))? str_replace("/","-", $_POST['date']) : "";
			//$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
          
            $data['empId'] = $empId;
			$data['date'] = $date;
			$data['getEmployees'] = $this->users_model->getUserByType(4);
			$data['reports'] = $this->users_model->getlocationReport($empId, $date);
			//pr( $data,1);
            $this->viewAdmin('admin/users/locationreport', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}*/

		if ($this->checkSess()) {
			$userId = $this->user_id;
			$type = $this->session->userdata['logged_in']['usertype'];
			//$res = $this->users_model->getAllChield(array($this->user_id));
			$parent_id = NULL;
			if (!empty($_POST) && isset($_POST['parent_id']) && !empty($_POST['parent_id'])) {
				$res = $this->users_model->getSubUser($_POST['parent_id']);
			} else {
				$res = $this->users_model->getSubUser($userId);
			}

			$data['parent'] = $parent_id;
			$data['getSubAdmins'] = $this->users_model->getSubAdmins_new(array($this->user_id), $res, 3);
			$data['users'] = $this->users_model->getuserslist_new(null, $res, $userId, $type);
			$this->viewAdmin('admin/users/locationreport', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	function locationsEmployeeDetails($id)
	{
		if ($this->checkSess()) {
			$data['user_details']  = $this->users_model->employeedetails(base64_decode($id));
			$data['user_attendance']  = $this->users_model->employeeattendance(base64_decode($id));
			$this->viewAdmin('admin/users/locationemployeeDetails', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}
	public function locationDetails($id)
	{
		if ($this->checkSess()) {
			$employee_punch_Id = base64_decode($id);
			//employeedetails
			$data['report_details'] = $this->users_model->getlocationReportDetails($employee_punch_Id);
			if ($data['report_details']) {
				$data['employeedetails'] = $this->users_model->employeedetails($data['report_details'][0]->user_id);
				//$total_distance = $this->users_model->gettotal_distance($data['report_details'][0]->user_id, $employee_punch_Id);
				$data['total_distance'] = 0;

				foreach ($data['report_details'] as $key => $value) {
					if ($key == 0) {
						$data['date_of_list'] = date('d-m-Y', strtotime($value->HOUR));
					}

					$fullurl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $value->lat . "," . $value->lng . "&result_type=route&key=AIzaSyBuHFuTFRx10d302H6JF0SYfzBmFpuLclI";
					$backIndex = ($key == 0) ? 1 - 1 : $key - 1;
					$newdistance = $this->distance($data['report_details'][$backIndex]->lat, $data['report_details'][$backIndex]->lng, $value->lat, $value->lng, 'K');

					$data['total_distance'] = $data['total_distance'] + $newdistance;
					$string = file_get_contents($fullurl); // get json content
					$json_a = json_decode($string, true); //json decoder
					$data['report_details'][$key]->distance = round($newdistance, 2);
					if ($json_a['results']) {
						$data['report_details'][$key]->address = $json_a['results'][0]['formatted_address'];
					} else {
						$data['report_details'][$key]->address = 'Address Not Found';
					}
				}
				///exit;
				//https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=YOUR_API_KEY
			} else {
				$this->locationReport();
				return;
			}
			$this->viewAdmin('admin/users/locationreportDetails', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function attendanceReportExportCSV()
	{
		if ($this->checkSess()) {
			$empId = (isset($_POST['select_employee'])) ? $_POST['select_employee'] : NULL;
			$startDate = (isset($_POST['start_date'])) ? str_replace("/", "-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date'])) ? str_replace("/", "-", $_POST['end_date']) : "";

			$reports = $this->users_model->getAttendanceReport($empId, $startDate, $endDate);

			$filename = 'Attendance_' . date('Ymd') . '.csv';
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Type: application/csv; ");

			// get data 
			//$usersData = $this->Main_model->getUserDetails();

			// file creation 
			$file = fopen('php://output', 'w');

			$header = array("EMPLOYEE", "PHONE", "PUNCH IN", "PUNCH OUT", "PUNCH IN LOCATION", "PUNCH OUT LOCATION", "PUNCH IN METER READING", "PUNCH OUT METER READING", "TOTAL READING");
			fputcsv($file, $header);
			if ($reports) {
				foreach ($reports as $key => $line) {
					$line->totalLoggedTime = (isset($line->totalLoggedTime)) ? $line->totalLoggedTime : 0;
					$newData = array('empName' => $line->empName, 'phone' => $line->phone, 'punchInDate' => $line->punchInDate, 'punchOutDate' => $line->punchOutDate, 'punchInLocation' => $line->punchInLocation, 'punchOutLocation' => $line->punchOutLocation, 'inReading' => $line->inReading, 'outReading' => $line->outReading, 'totalLoggedTime' => $line->totalLoggedTime);

					fputcsv($file, $newData);
				}
			}
			fclose($file);
			exit;
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function attendanceListExport()
	{
		if ($this->checkSess()) {
			$method = $this->input->post('select_usertype');
			$method = ($method == NULL) ? 'punch-in' : $method;
			$users = $this->users_model->getAttendanceList($method);
			$filename = 'AttendanceList_' . strtotime("now") . '.csv';
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Type: application/csv; ");

			$file = fopen('php://output', 'w');

			if ($users) {
				if ($method == 'punch-in') {
					$header = array("EMPLOYEE", "PHONE", "PLACE", "PUNCH-IN READING");
					fputcsv($file, $header);
					//pr($reports,1);
					foreach ($users as $key => $line) {
						$punchObj = json_decode($line->punch_in);
						$newData = array('empName' => $line->empName, 'phone' => $line->phone, 'place' => $punchObj->place, 'reading' => $punchObj->meter_reading_in_km);
						fputcsv($file, $newData);
					}
				} elseif ($method == 'punch-out') {
					$header = array("EMPLOYEE", "PHONE", "PUNCH-IN PLACE", "PUNCH-IN READING", "PUNCH-OUT PLACE", "PUNCH-OUT READING", "TOTAL READING");
					fputcsv($file, $header);
					//pr($reports,1);
					foreach ($users as $key => $line) {
						$punchObj = json_decode($line->punch_in);
						$punchOutObj = json_decode($line->punch_out);
						$newData = array('empName' => $line->empName, 'phone' => $line->phone, 'place_in' => $punchObj->place, 'reading_in' => $punchObj->meter_reading_in_km, 'place_out' => $punchOutObj->place, 'reading_out' => $punchOutObj->meter_reading_in_km, 'traveled_km' => $line->traveled_km);
						fputcsv($file, $newData);
					}
				} elseif ($method == 'absent') {
					$header = array("EMPLOYEE", "PHONE");
					fputcsv($file, $header);
					//pr($reports,1);
					foreach ($users as $key => $line) {
						$newData = array('empName' => $line->empName, 'phone' => $line->phone);
						fputcsv($file, $newData);
					}
				}
			} else {
				$header = array("EMPLOYEE", "PHONE", "PLACE", "PUNCH-IN READING");
			}
			fclose($file);
			exit;
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function liveMap()
	{
		if ($this->checkSess()) {
			$this->viewAdmin('admin/users/map');
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function getLiveAttandance()
	{
		if ($this->checkSess()) {
			$locations = $this->users_model->getTodaysLiveLocations();
			echo  json_encode($locations);
		} else {
			echo json_encode(array());
		}
	}

	public function userRoute($id)
	{
		if ($this->checkSess()) {

			$data['emp_details']  = $this->users_model->employeeattendanceById(base64_decode($id));

			$data['next_date']  = $this->users_model->employeeattendanceNext_date($data['emp_details']->punch_in_date, $data['emp_details']->user_id);
			//$data['next_date'] =  end($data['next_date']);
			$data['previous_date']  = $this->users_model->employeeattendancePrevious_date($data['emp_details']->punch_in_date, $data['emp_details']->user_id);
			$this->viewAdmin('admin/users/livemap', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function getLiveUserRoute($id)
	{
		if ($this->checkSess()) {
			$locations = $this->users_model->getLiveUserRoute(base64_decode($id));
			$punchtimedata = $this->users_model->getLiveUserRoutePunchDetails(base64_decode($id));
			$locations = json_decode(json_encode($locations), true);
			if ($locations) {
				array_unshift($locations, $punchtimedata['start']);
				if ($punchtimedata['end']['lat']) {
					array_push($locations, $punchtimedata['end']);
				}
				// echo  json_encode((object) $locations);
				echo json_encode($locations);
			} else {
				$locations = array();
				array_push($locations, $punchtimedata['start']);
				if ($punchtimedata['end']['lat'] != null) {
					array_push($locations, $punchtimedata['end']);
				}
				echo json_encode($locations);
			}
		} else {
			echo json_encode(array());
		}
	}

	public function viewEmployeeDetails($id)
	{
		if ($this->checkSess()) {
			$data['user_details']  = $this->users_model->employeedetails(base64_decode($id));
			$apk_version = explode(' ', $data['user_details']->device_model);
			$data['user_details']->mobile_apk_version = end($apk_version);
			$data['user_attendance']  = $this->users_model->employeeattendance(base64_decode($id));
			$this->viewAdmin('admin/users/employeeDetails', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function resetDevice()
	{
		if ($this->checkSess()) {
			$id = str_replace('/', '_', rtrim(base64_decode($this->input->post('id')), '='));
			$result = $this->users_model->resetDevice($id);
			return $result;
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function profile()
	{
		if ($this->checkSess()) {
			$user_id = $this->session->userdata['logged_in']['userid'];
			$data['user_details'] = $this->users_model->getuserdetails($user_id);

			$this->viewAdmin('admin/users/profile', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function change_password()
	{
		if ($this->checkSess()) {
			$user_id = $this->session->userdata['logged_in']['userid'];
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|xss_clean');
			/*check old password*/
			$old_check = $this->users_model->check_old_password($user_id, $_POST['old_password']);
			/*End check old password*/
			if ($old_check) {
				if ($_POST['password'] == $_POST['password_confirm']) {
					$this->users_model->update_password($user_id, $_POST['password']);
					$this->session->set_flashdata('flash_message', 'updated');
				} else {
					$this->session->set_flashdata('flash_message', 'new_match');
				}
			} else {
				$this->session->set_flashdata('flash_message', 'not_match');
			}



			$data['user_details'] = $this->users_model->getuserdetails($user_id);

			$this->viewAdmin('admin/users/profile', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
	/*::                                                                         :*/
	/*::  This routine calculates the distance between two points (given the     :*/
	/*::  latitude/longitude of those points). It is being used to calculate     :*/
	/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
	/*::                                                                         :*/
	/*::  Definitions:                                                           :*/
	/*::    South latitudes are negative, east longitudes are positive           :*/
	/*::                                                                         :*/
	/*::  Passed to function:                                                    :*/
	/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
	/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
	/*::    unit = the unit you desire for results                               :*/
	/*::           where: 'M' is statute miles (default)                         :*/
	/*::                  'K' is kilometers                                      :*/
	/*::                  'N' is nautical miles                                  :*/
	/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
	function distance($lat1, $lon1, $lat2, $lon2, $unit)
	{
		if (($lat1 == $lat2) && ($lon1 == $lon2)) {
			return 0;
		} else {
			$theta = $lon1 - $lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K") {
				return ($miles * 1.609344);
			} else if ($unit == "N") {
				return ($miles * 0.8684);
			} else {
				return $miles;
			}
		}
	}
	/**
	 * Optimized algorithm for Get Distance between two lat long
	 *
	 * @param float $latitudeFrom
	 * @param float $longitudeFrom
	 * @param float $latitudeTo
	 * @param float $longitudeTo
	 *
	 * @return float [km]
	 */
	function GetDistanceOpt($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
	{
		$rad = M_PI / 180;
		//Calculate distance from latitude and longitude
		$theta = $longitudeFrom - $longitudeTo;
		$dist = sin($latitudeFrom * $rad)
			* sin($latitudeTo * $rad) +  cos($latitudeFrom * $rad)
			* cos($latitudeTo * $rad) * cos($theta * $rad);

		return acos($dist) / $rad * 60 *  1.853;
	}

	public function treeview()
	{
		if ($this->checkSess()) {
			$this->viewAdmin('admin/users/treeview');
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function getUsersTreeView()
	{
		if ($this->checkSess()) {
			$type = $this->session->userdata['logged_in']['usertype'];
			// 		if($type == 'Super Admin' || $type == 'Admin'){
			$data['users'] = $this->users_model->getUsersTreeView($this->session->userdata['logged_in']['userid']);
			// 			unset($data['users'][0]->pid);

			echo json_encode($data['users']);
			// 		}else{
			// 			redirect(CURRENT_MODULE.'users');
			// 		}	
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}
}
