<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dealers extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('expenses_model');
		$this->load->model('dealers_model');
		//$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if ($this->checkSess()) {
			$this->view();
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function view()
	{
		if ($this->checkSess()) {
			$empId = (isset($_POST['select_employee'])) ? $_POST['select_employee'] : NULL;
			$catId = (isset($_POST['select_category'])) ? $_POST['select_category'] : NULL;
			$typeId = (isset($_POST['select_type'])) ? $_POST['select_type'] : NULL;
			$startDate = (isset($_POST['start_date'])) ? str_replace("/", "-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date'])) ? str_replace("/", "-", $_POST['end_date']) : "";

			$data['empId'] = $empId;
			$data['catId'] = $catId;
			$data['typeId'] = $typeId;
			$data['startDate'] = $startDate;
			$data['endDate'] = $endDate;
			$data['getCategories'] = $this->dealers_model->getDealerCategoriesList();
			$data['getTypes'] = $this->dealers_model->getDealerTypeList();
			$data['getEmployees'] = $this->expenses_model->getUserByType(4);
			$data['dealers'] = $this->expenses_model->getDealers($typeId, $catId, $empId, $startDate, $endDate, NULL);
			// echo '<pre/>';print_r($data);exit;
			$this->viewAdmin('admin/dealers/view', $data);
		} else {
			redirect(CURRENT_MODULE . 'login');
		}
	}

	public function getListView()
	{
		$id = $_POST['id'];
		$id = base64_decode($id);
		$visits = $this->expenses_model->getDealers(NULL, NULL, NULL, NULL, NULL, $id);
		// print_r($visits);exit;
		echo json_encode($visits);
	}

	public function add()
	{
		// check user already logged in OR not 
		if ($this->checkSess()) {
			$data['getEmployees'] = $this->expenses_model->getUserByType(4);
			$data['getDealerCategories'] = $this->expenses_model->getDealerCategories();
			$data['getDealerTypes'] = $this->expenses_model->getDealerTypes();
			$this->viewAdmin('admin/dealers/add', $data);
		} else {
			$this->index();
		}
	}

	public function insert()
	{
		if ($this->checkSess()) {

			//Set Rule for Validation
			$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('dealer_category', 'Party Category', 'trim|required|strip_tags|xss_clean');
			// 			$this->form_validation->set_rules('dealer_type', 'Party Type', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('firm_name', 'Firm Name', 'trim|required|strip_tags|xss_clean');
			// 			$this->form_validation->set_rules('dealer_name', 'Dealer Name', 'trim|required|strip_tags|xss_clean'); 
			// 			$this->form_validation->set_rules('dealer_phone', 'Dealer Number', 'trim|required|strip_tags|xss_clean'); 
			$this->form_validation->set_rules('address', 'Address', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('city_or_town', 'City/Town', 'trim|required|strip_tags|xss_clean');
			// 			$this->form_validation->set_rules('gst_number', 'GST Number', 'trim|required|strip_tags|xss_clean'); 

			if ($this->form_validation->run() === FALSE) {
				$this->add();
			} else {
				$params = $this->input->post();
				$dealer_name = $params['dealer_name'];
				$dealer_phone = $params['dealer_phone'];
				$data = [

					'owner_name' => $dealer_name,
					'phone_no' => $dealer_phone,

				];

				unset($params['dealer_name']);
				unset($params['dealer_phone']);
				$result = $this->expenses_model->addParty($params, $data);

				if ($result) {
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL . 'admin/dealers/view');
				} else {
					$this->session->set_flashdata('flash_message', 'Data not inserted');
					redirect(BASE_URL . 'admin/dealers/add');
				}
			}
		} else {
			$this->index();
		}
	}


	public function edit($id = NULL)
	{
		if ($this->checkSess()) {
			$id = base64_decode($id);
			$dealer = $this->expenses_model->getDealers(NULL, NULL, NULL, NULL, NULL, $id);
			if (!$dealer) {
				$this->index();
			} else {
				$data['dealer'] = $dealer[0];
				$data['owner_ids'] = explode("|", $dealer[0]->owner_ids);
				$data['owner_names'] = explode("|", $dealer[0]->owner_names);
				$data['owner_phones'] = explode("|", $dealer[0]->owner_phones);
				$data['getEmployees'] = $this->expenses_model->getUserByType(4);
				$data['getDealerCategories'] = $this->expenses_model->getDealerCategories();
				$data['getDealerTypes'] = $this->expenses_model->getDealerTypes();
				//pr($data,1);
				// echo "<pre/>";print_r($data);exit;

				$this->viewAdmin('admin/dealers/edit', $data);
			}
		} else {
			$this->index();
		}
	}

	public function update()
	{
		if ($this->checkSess()) {
			$id = str_replace('/', '_', rtrim(base64_encode($this->input->post('id')), '='));
			//Set Rule for Validation
			$this->form_validation->set_rules('dealer_category', 'Party Category', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('dealer_type', 'Party Type', 'trim|required|strip_tags|xss_clean');
			// 			$this->form_validation->set_rules('dealer_name', 'Dealer name', 'trim|xss_clean');
			// 			$this->form_validation->set_rules('dealer_phone', 'Dealer phone', 'trim|xss_clean');
			$this->form_validation->set_rules('firm_name', 'Firm name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
			$this->form_validation->set_rules('city_or_town', 'City/Town', 'trim|required|xss_clean');
			// 			$this->form_validation->set_rules('gst_number', 'GST number', 'trim|required|xss_clean');
			// 			$this->form_validation->set_rules('dealer_aadhar', 'Dealer Aadhar', 'trim|required|xss_clean');

			if ($this->form_validation->run() === FALSE) {
				$this->edit($id);
			} else {
				$params = $this->input->post();
				//Check if dealer name is in dealer table or delaer_owner
				if (!isset($params['owner_id'])) {
					//   echo"hcbkd";
					$result = $this->expenses_model->updateDealer();
				} else {

					$owner_id = $params['owner_id'];
					$dealer_name = $params['dealer_name'];
					$dealer_phone = $params['dealer_phone'];
					$is_deleted = $params['is_deleted'];
					$data = [
						'id' => $owner_id,
						'owner_name' => $dealer_name,
						'phone_no' => $dealer_phone,
						'is_deleted' => $is_deleted,
					];
					// echo "<pre/>";print_r($data);exit;

					unset($params['owner_id']);
					unset($params['dealer_name']);
					unset($params['dealer_phone']);
					unset($params['is_deleted']);
					$result = $this->expenses_model->updateDealerOwner($params, $data);
				}


				if ($result) {
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL . 'admin/dealers/view');
				} else {
					$this->session->set_flashdata('flash_message', 'not_updated');
					$this->index();
				}
			}
		} else {
			$this->index();
		}
	}

	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		if ($this->expenses_model->deleteDealer($id) == TRUE) {
			$this->session->set_flashdata('flash_message', 'Deleted');
		} else {
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE . 'dealers/view');
	}

	public function importDealers()
	{
		$fileName = $_FILES['csv']['tmp_name'];
		$handle = fopen($fileName, "r");
		$c = 0; //
		while (($filesop = fgetcsv($handle)) !== false) {
			if ($c <> 0) {					//SKIP THE FIRST ROW
				if (isset($filesop[0]) && $filesop[0] != "") {
					$emp_id = $filesop[0];
					$employeeData = $this->expenses_model->getUserByEmpId($emp_id);

					if ($employeeData != false) {
						$isInvalid = false;
						$insertData = array();

						$insertData['employee_id'] = $employeeData[0]->id;

						if (isset($filesop[1]) && $filesop[1] != "") {
							$insertData['dealer_name'] = $filesop[1];
						} else {
							$isInvalid = true;
						}

						if (isset($filesop[2]) && $filesop[2] != "") {
							$insertData['dealer_phone'] = $filesop[2];
						} else {
							$isInvalid = true;
						}

						if (isset($filesop[3]) && $filesop[3] != "") {
							$insertData['firm_name'] = $filesop[3];
						} else {
							$isInvalid = true;
						}

						if (isset($filesop[4]) && $filesop[4] != "") {
							$insertData['address'] = $filesop[4];
						} else {
							$isInvalid = true;
						}

						if (isset($filesop[5]) && $filesop[5] != "") {
							$insertData['city_or_town'] = $filesop[5];
						} else {
							$isInvalid = true;
						}

						if (isset($filesop[6]) && $filesop[6] != "") {
							$insertData['gst_number'] = $filesop[6];
						} else {
							$isInvalid = true;
						}

						//aadhar option optional
						if (isset($filesop[7]) && $filesop[7] != "") {
							$insertData['dealer_aadhar'] = $filesop[7];
						}

						if (!$isInvalid) {
							$this->expenses_model->addParty($insertData);
						}
					}
				}
			}
			$c = $c + 1;
		}
		$this->session->set_flashdata('flash_message', 'imported');
		redirect(CURRENT_MODULE . 'dealers/view');
	}


	public function getDealersByEmp()
	{
		if ($emp_id = $_GET['emp_id'] ?? 0) {
			$data = $this->expenses_model->getDealers(NULL, NULL, $emp_id);
			// $data = json_encode($data);
			echo json_encode($data);
			// print_r(array_column($data, 'id'));
		}
	}
}
