<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Zones extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('zones_model');
	   $this->load->model('countries_model');
	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->zones();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function zones(){
    	if($this->checkSess()){
            $data['zoneview'] = $this->zones_model->getZonesList();
            $this->viewAdmin('admin/zones/zone_list', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	public function zoneadd(){
		if($this->checkSess()){	
			$data['countrylist'] = $this->countries_model->getCountryList();
			$this->viewAdmin('admin/zones/zone_add', $data);
		}else{
			$this->index();
		}
	}

	public function zoneedit($id){
		if($id){
			if($this->checkSess()){	
				$data['countrylist'] = $this->countries_model->getCountryList();
				$data['zone_Details'] = $this->zones_model->getZonesDetails(base64_decode($id));
                $this->viewAdmin('admin/zones/zone_edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'zones/zones');
		}
	}
	
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->zones_model->delete_zone($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'zones/zones');
	}	
		
	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('country_id', 'Country name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('zone_name', 'Zone name', 'trim|required|xss_clean|is_unique[zones.zone_name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->zoneadd();
			}
			else
			{				
				$result = $this->zones_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/zones');

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
			$id = str_replace('/', '_',rtrim(base64_encode($this->input->post('zone_id')), '=')); 
			//Set Rule for Validation
			$this->form_validation->set_rules('zone_id', 'Zone name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('zone_name', 'Country name', 'trim|required|xss_clean|callback_checkDuplicate[Country name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->zoneedit($id);
			}
			else
			{				
				$result = $this->zones_model->update();
				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/zones');

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
    	$res = $this->zones_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }
}