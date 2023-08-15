<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Countries extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('countries_model');
	   $this->load->model('Api');
	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
	   // $data = $this->Api->getPoDetails(88);
	   // print_r(base_url('assets/hir.png'));exit;
	   // echo '<img src="data:image/png;base64,'.base64_encode(base_url('assets/hir.png')).'" alt="Hir Industries" style="width: 100%; max-width: 300px" />';exit;
	   // $this->viewAdmin('admin/Invoice', ['data' =>$data]);
		if($this->checkSess()){
			$this->countries();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function countries(){
    	if($this->checkSess()){
            $data['countryview'] = $this->countries_model->getCountryList();
            $this->viewAdmin('admin/countries/country_list', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	public function countryadd(){
		if($this->checkSess()){	
			$this->viewAdmin('admin/countries/country_add');
		}else{
			$this->index();
		}
	}

	public function countryedit($id){
		if($id){
			if($this->checkSess()){	
                $data['country_Details'] = $this->countries_model->getCountryDetails(base64_decode($id));
                $this->viewAdmin('admin/countries/country_edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'countries/countries');
		}
	}
	
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->countries_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'countries/countries');
	}	
		
	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('country_name', 'Country name', 'trim|required|xss_clean|is_unique[countries.country_name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->countryadd();
			}
			else
			{				
				$result = $this->countries_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/countries');

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
			$id = str_replace('/', '_',rtrim(base64_encode($this->input->post('country_id')), '=')); 
			//Set Rule for Validation
			$this->form_validation->set_rules('country_name', 'Country name', 'trim|required|xss_clean|callback_checkDuplicate[Country name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->countryedit($id);
			}
			else
			{				
				$result = $this->countries_model->update();
				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/countries');

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
    	$res = $this->countries_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }
}