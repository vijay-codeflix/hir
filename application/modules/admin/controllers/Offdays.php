<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Offdays extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('offdays_model');
	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->offdays();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function offdays(){
    	if($this->checkSess()){
            $data['offdays'] = $this->offdays_model->getOffdaysList();
            $this->viewAdmin('admin/offdays/offdaysview', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	function offdayadd(){
		if($this->checkSess()){	
			$this->viewAdmin('admin/offdays/add');
		}else{
			$this->index();
		}
	}

	function offdayedit($id){
		if($id){
			if($this->checkSess()){	
                $data['catDetails'] = $this->offdays_model->getOffdaysList(base64_decode($id));
                $this->viewAdmin('admin/offdays/edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'offdays');
		}
	}	
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->offdays_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'offdays');
	}	

	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('type', 'Offday type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('title', 'Offday title', 'trim|required|xss_clean');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->offdayadd();
			}
			else
			{				
				$result = $this->offdays_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/offdays');

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
			$this->form_validation->set_rules('type', 'Offday type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('name', 'Offday name', 'trim|required|xss_clean|callback_checkDuplicate[Offday name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->offdayedit($id);
			}
			else
			{				
				$result = $this->offdays_model->update();

				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/offdays');

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
    	$res = $this->offdays_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }

    
}