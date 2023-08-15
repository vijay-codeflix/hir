<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class States extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('states_model');
	   $this->load->model('countries_model');
	   $this->load->model('zones_model');
	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->states();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function states(){
    	if($this->checkSess()){
            $data['statesview'] = $this->states_model->getStatesList();
            $this->viewAdmin('admin/states/state_list', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	public function stateadd(){
		if($this->checkSess()){	
			//$data['zonelist'] = $this->zones_model->getZoneList();
			$data['countrylist'] = $this->countries_model->getCountryList();
			$this->viewAdmin('admin/states/state_add', $data);
		}else{
			$this->index();
		}
	}
	public function getZones($id)
	{
		$data = $this->states_model->getZonesListByCountry(base64_decode($id));
		echo json_encode($data);
		return;
	}

	public function getStates($id)
	{
		$data = $this->states_model->getStatesListByZone($id);
		echo json_encode($data);
		return;
	}

	
	public function stateedit($id){
		if($id){
			if($this->checkSess()){	
				$data['countrylist'] = $this->countries_model->getCountryList();
				//$data['zonelist'] = $this->zones_model->getZoneList();
                $data['states_Details'] = $this->states_model->getStatesDetails(base64_decode($id));
                $data['zonelist'] =$this->states_model->getZonesListByCountry($data['states_Details'][0]->country_id);
                $this->viewAdmin('admin/states/state_edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'states/');
		}
	}
	
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->states_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'states/');
	}	
		
	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('zone_id', 'Zone name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('country_id', 'Country name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('state_name', 'State name', 'trim|required|xss_clean|is_unique[states.state_name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->stateadd();
			}
			else
			{				
				$result = $this->states_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/states');

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
			
			$id = str_replace('/', '_',rtrim(base64_encode($this->input->post('state_id')), '=')); 
			//Set Rule for Validation
			$this->form_validation->set_rules('zone_id', 'Zone name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('country_id', 'Country name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('state_name', 'State name', 'trim|required|xss_clean|callback_checkDuplicate[State name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->stateedit($id);
			}
			else
			{		
				$result = $this->states_model->update();
				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/states');

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
    	$res = $this->states_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please entery new name');	
    		return false;
    	}else{
    		return true;
    	}		
    }
}