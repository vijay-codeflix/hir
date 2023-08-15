<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Cities extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('city_model');
	   $this->load->model('states_model');
	   $this->load->model('countries_model');
	   $this->load->model('zones_model');
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
            $data['cities'] = $this->city_model->getList();
            $this->viewAdmin('admin/cities/view', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	public function add(){
		//check user already logged in OR not
		if($this->checkSess()){
			$data['countrylist'] = $this->countries_model->getCountryList();
            $this->viewAdmin('admin/cities/add', $data);
		}else{
			$this->index();
		}
	}

	public function insert(){
		//$this->methodCalled = 'insert';
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('zone_id', 'Zone name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('country_id', 'Country name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('state_id', 'State name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('grade', 'Grade', 'trim|required|xss_clean');
			$this->form_validation->set_rules('city_name', 'City Name', 'trim|required|xss_clean|is_unique[city_grades.city_name]');
			if ($this->form_validation->run() === FALSE)
			{
				$this->add();
            }
            else
            {	
				$result = $this->city_model->insert();
				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/cities/view');
				}else{
					$this->session->set_flashdata('flash_message', 'Data not inserted');
					redirect(BASE_URL.'admin/cities/add');
				}
			}
		} else {
			$this->index();
		}
	}

	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		if($this->city_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'cities/view');
	}	

	function edit($id = NULL)
	{
		if($id){
			if($this->checkSess()){	
                $data['cities'] = $this->city_model->getCityById(base64_decode($id));
                $data['countrylist'] = $this->countries_model->getCountryList();
				$data['zonelist'] =$this->states_model->getZonesListByCountry($data['cities'][0]->country_id);
				$data['stateslist'] =$this->states_model->getStatesListByZone($data['cities'][0]->zone_id);
                $this->viewAdmin('admin/cities/edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'cities/view');
		}
	}

	public function update(){
		if($this->checkSess()){
			$id = str_replace('/', '_',rtrim(base64_encode($this->input->post('id')), '=')); 
			//Set Rule for Validation
			$this->form_validation->set_rules('grade', 'City Grade', 'trim|required|xss_clean');
			$this->form_validation->set_rules('zone_id', 'Zone name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('country_id', 'Country name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('state_id', 'State name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('city_name', 'City name', 'trim|required|xss_clean|callback_checkDuplicate[City name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->edit($id);
			}
			else
			{				
				$result = $this->city_model->update();

				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/cities/view');

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
    	$res = $this->city_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }

	public function validFunction(){

		if($this->checkPermission($_POST['type']))
		{
			echo 'true';
		}else{
			echo 'false';
		}
    }
    
	public function getCurrentCity(){
		if(isset($_POST['id'])){
			$id = base64_decode($_POST['id']);
			$result  = $this->city_model->getCityById($id);
			echo json_encode($result);
		}
	}
	
}