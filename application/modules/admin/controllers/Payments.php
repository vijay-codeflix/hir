<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Payments extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('payments_model');
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->pending();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function pending(){
    	if($this->checkSess()){
			$empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
			$startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
			
			$data['startDate'] = $startDate;
			$data['endDate'] = $endDate;
			$data['empId'] = $empId;
			$data['getEmployees'] = $this->payments_model->getUserByType(4);

			$response = $this->payments_model->paymentList(NULL, $empId, $startDate, $endDate,'pending');
			$total = 0;
			$data['response'] = $response;
			
			if($response){
				foreach ($response as $key => $value) {
					$total += (float)$value->amount;
				}
			}
			$data['typeOfPayment'] = 'pending';
			$data['total'] = $total;
            $this->viewAdmin('admin/payments/view', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
    
    public function approved(){
        if($this->checkSess()){
			$empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
			$startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
			
			$data['startDate'] = $startDate;
			$data['endDate'] = $endDate;
			$data['empId'] = $empId;
			$data['getEmployees'] = $this->payments_model->getUserByType(4);
			$data['typeOfPayment'] = 'approved';
			$response = $this->payments_model->paymentList(NULL, $empId, $startDate, $endDate,'approved');
			$total = 0;
			$data['response'] = $response;
			
			if($response){
				foreach ($response as $key => $value) {
					$total += $value->amount;
				}
			}
			$data['total'] = $total;
            $this->viewAdmin('admin/payments/view', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	public function paymentExportCSV(){
		if($this->checkSess()){
            $empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            $startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
			$type = $_POST['type'];
          
			$reports = $this->payments_model->paymentList(NULL, $empId, $startDate, $endDate,$type);
			
			$header = array("DEALER NAME","EMPLOYEE NAME","AMOUNT","TYPE","CHEQUE DETAIL","PAYMENT DETAIL","DATE");
			$filename = 'Payment_'.strtotime('now').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");
			// file creation 
			$file = fopen('php://output', 'w');
			
			fputcsv($file, $header);
			if($reports){
				foreach ($reports as $key=>$line){ 
					$newData = array('dealer_name' => ucfirst($line->dealer_name), 'empName' => ucfirst($line->empName), 'amount' => $line->amount, 'payment_method' => ucfirst($line->payment_method),"cheque_detail" => $line->cheque_detail, "payment_details" => $line->payment_details, "reqDate" => $line->reqDate);
					fputcsv($file, $newData); 
				} 
			}
			
			fclose($file); 
			exit;
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}
	public function getAction($id = NULL, $status)
	{
		$id = base64_decode($id);
		$para = array('status' => $status, 'action_date' => date('Y-m-d H:i:s'), 'action_by' => $this->session->userdata['logged_in']['userid']);
		
		$result = $this->payments_model->updatePaymentAction($para, $id);
		$this->session->set_flashdata('flash_message', 'updated');
		redirect(BASE_URL.'admin/payments/pending');
	}

	public function getPaymentDetails(){
		if(isset($_POST['id'])){
			$id = base64_decode($_POST['id']);
			$response = $this->payments_model->paymentList($id, NULL, '', '', NULL);
			echo json_encode($response);
		}
	}
}