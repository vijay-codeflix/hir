<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Visits extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('expenses_model');
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->view();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

    public function view(){
        if($this->checkSess()){
            $empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            $startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
          
            $data['empId'] = $empId;
			$data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['getEmployees'] = $this->expenses_model->getUserByType(4);
            $data['visits'] = $this->expenses_model->getVisits($empId, $startDate, $endDate, NULL);
            // pr($data['visits'],1);
            $this->viewAdmin('admin/visits/view', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}
    }

    public function getListView(){
		$id = $_POST['id'];
		$id = base64_decode($id);
        $visits = $this->expenses_model->getVisits(NULL, NULL, NULL, $id);
        // echo"<pre>";
        //     print_r($visits);
        //     exit;
        echo json_encode($visits);
    }
	
	public function visitExportCSV(){
		if($this->checkSess()){
            $empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            $startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
          
			$reports = $this->expenses_model->getVisits($empId, $startDate, $endDate);
// 			echo"<pre>";
//             print_r($reports);
//             exit;
			$filename = 'Visit_'.strtotime('now').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");
			// file creation 
			$file = fopen('php://output', 'w');
			
			$header = array("ID","EMPLOYEE NAME","CONTACT PERSON DETAILS","FIRM","AREA","VISITED DATE","DISCUSS DURATION","DISCUSS POINT","REMARK");
			fputcsv($file, $header);

			if($reports){
				foreach ($reports as $key=>$line){
				    $name = (empty($line->contact_person)) ? str_replace("</br>",", ",$line->party->contact_person_details) : $line->contact_person;
					$newData = [
					    'id' => $line->id,
					    'employee_name' => ucfirst($line->first_name.' '.$line->last_name),
					    'name' => ucfirst($name),
					    'contact_firm' => $line->contact_firm,
					    'area_or_town' => $line->area_or_town,
					    'created_at' => date(DEFAULT_DATE_FORMAT,strtotime($line->created_at)),
					    'discuss_duration' => $line->discuss_duration,
					    'discuss_point' => $line->discuss_point,
					    'remark' => $line->remark,
					];
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
