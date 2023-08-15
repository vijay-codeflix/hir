<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Dealertypes extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('dealers_model');
	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->types();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function types(){
    	if($this->checkSess()){
            $data['dealer_typesview'] = $this->dealers_model->getDealerTypeList();
            $this->viewAdmin('admin/dealers_type/dealers_type_list', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	public function type_add(){
		if($this->checkSess()){	
			$this->viewAdmin('admin/dealers_type/dealers_type_add');
		}else{
			$this->index();
		}
	}

	public function type_edit($id){
		if($id){
			if($this->checkSess()){	
                $data['dealer_type_details'] = $this->dealers_model->getDealerTypeDetails(base64_decode($id));
                $this->viewAdmin('admin/dealers_type/dealers_type_edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'dealertypes/');
		}
	}
	
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->dealers_model->DealerTypedelete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'dealertypes');
	}	
		
	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('name', 'Dealer Type name', 'trim|required|xss_clean|is_unique[ dealer_types.name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->dealer_typesadd();
			}
			else
			{				
				$result = $this->dealers_model->DealerTypeinsert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/dealertypes');

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
			$this->form_validation->set_rules('name', 'name', 'trim|required|xss_clean|callback_checkDuplicate[name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->type_edit($id);
			}
			else
			{				
				$result = $this->dealers_model->DealerTypeupdate();
				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/dealertypes');

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
    	$res = $this->dealers_model->checkDuplicatedealertypes($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }
}