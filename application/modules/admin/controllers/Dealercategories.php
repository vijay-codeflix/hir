<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Dealercategories extends MY_Controller {

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
			$this->categories();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function categories(){
    	if($this->checkSess()){
            $data['dealer_categoriesview'] = $this->dealers_model->getDealerCategoriesList();
            $this->viewAdmin('admin/dealers_category/dealers_category_list', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	public function category_add(){
		if($this->checkSess()){	
			$this->viewAdmin('admin/dealers_category/dealers_category_add');
		}else{
			$this->index();
		}
	}

	public function category_edit($id){
		if($id){
			if($this->checkSess()){	
                $data['dealer_category_details'] = $this->dealers_model->getDealerCategoriesDetails(base64_decode($id));
                $this->viewAdmin('admin/dealers_category/dealers_category_edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'dealercategories/');
		}
	}
	
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->dealers_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'Not Deleted');
		}
		redirect(CURRENT_MODULE.'dealercategories');
	}
		
	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('name', 'Dealer Categories name', 'trim|required|xss_clean|is_unique[ dealer_categories.name]');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->category_add();
			}
			else
			{				
				$result = $this->dealers_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/dealercategories');

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
				$this->category_edit($id);
			}
			else
			{				
				$result = $this->dealers_model->update();
				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/dealercategories');

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
    	$res = $this->dealers_model->checkDuplicatedealercategories($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }
}