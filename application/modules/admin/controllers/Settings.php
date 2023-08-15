<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Settings extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('settings_model');
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->currency();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

    public function currency(){
        if($this->checkSess()){
            $data['currency'] = $this->settings_model->getCurrency();
            //pr($data,1);
            $this->viewAdmin('admin/settings/currency', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}
    }

    public function addcurrency(){
        if($this->checkSess()){
            $this->viewAdmin('admin/settings/addcurrency');
		}else{
			redirect(CURRENT_MODULE.'login');
		}
    } 
    
    public function insertCurrency(){
        if($this->checkSess()){
            $this->form_validation->set_rules('symbol', 'Currency symbol', 'trim|required|xss_clean|is_unique[currencies.symbol]');
			$this->form_validation->set_rules('name', 'Currency name', 'trim|required|xss_clean|is_unique[currencies.name]');
			
			if ($this->form_validation->run() === FALSE)
			{
				$this->addcurrency();
			}
			else
			{
                $result = $this->settings_model->insertCurrency();
				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/settings/currency');
				}else{
					$this->session->set_flashdata('flash_message', 'Data not inserted');
					redirect(BASE_URL.'admin/settings/addcurrency');
				}
            }
        }else{

        }
    }

    public function editCurrency($id = NULL){
        if($id != NULL){
			if($this->checkSess()){	
				$data['currency'] = $this->settings_model->getCurrency(base64_decode($id));
				$this->viewAdmin('admin/settings/editcurrency', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE.'settings/currency');
		}
    }

    function updateCurrency(){
        if($this->checkSess()){
            $id = str_replace('/', '_',rtrim(base64_encode($this->input->post('id')), '=')); 
            
            $this->form_validation->set_rules('name', 'Currency name', 'trim|required|xss_clean|callback_checkDuplicate[Currency name]');
            $this->form_validation->set_rules('symbol', 'Currency symbol', 'trim|required|xss_clean|callback_checkDuplicate[Currency symbol]');

            if ($this->form_validation->run() === FALSE)
			{			
				$this->editCurrency($id);
			}
			else
			{				
				$result = $this->settings_model->updateCurrency();

				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/settings/currency');
				}else{
					$this->session->set_flashdata('flash_message', 'not_updated');
					$this->index();
				}
            }
        }else{

        }

    }

    function deleteCurrency($id){
        $id = base64_decode($id);
		if($this->settings_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'settings/currency');
    }

    public function checkDuplicate($para1, $para2){
    	$res = $this->settings_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
	}

	public function site_settings(){
		if(isset($_POST) && !empty($_POST)){
			$update_key = array_keys($_POST);
		    foreach ($update_key as $key => $value) {
		    	//str_replace(' ', '-', $string);
		    	$this->form_validation->set_rules($value, ucwords(str_replace('_', ' ', $value)), 'trim|required|xss_clean');
		    }			
			if ($this->form_validation->run() === FALSE)
			{			
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$this->viewAdmin('admin/settings/sitesetting', $data);
			}
			else
			{				
				$this->settings_model->UpdateSitesetting($_POST);
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$this->viewAdmin('admin/settings/sitesetting', $data);
			}
		}else{
			if($this->checkSess()){	
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$data['app_version_setting'] = $this->settings_model->getAppVersionSetting();
				
				$this->viewAdmin('admin/settings/sitesetting', $data);
			}else{
				$this->index();
			}	
		}
			
	}
	
	public function add_settings(){
		if(isset($_POST) && !empty($_POST)){
			$update_key = array_keys($_POST);
		    foreach ($update_key as $key => $value) {
		    	//str_replace(' ', '-', $string);
		    	$this->form_validation->set_rules($value, ucwords(str_replace('_', ' ', $value)), 'trim|required|xss_clean');
		    }			
			if ($this->form_validation->run() === FALSE)
			{			
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$this->viewAdmin('admin/settings/addsetting', $data);
			}
			else
			{				
				$this->settings_model->UpdateSitesetting($_POST);
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$this->viewAdmin('admin/settings/addsetting', $data);
			}
		}else{
			if($this->checkSess()){	
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$data['app_version_setting'] = $this->settings_model->getAppVersionSetting();
				
				$this->viewAdmin('admin/settings/addsetting', $data);
			}else{
				$this->index();
			}	
		}
			
	}
	
	public function app_update_settings(){
	    if(isset($_POST) && !empty($_POST)){
			//$this->form_validation->set_rules('update_required', 'Update Required', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('latest_version', 'Latest Version', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('latest_version_code', 'Latest Version Code', 'trim|required|xss_clean');
			print_r($this->form_validation->run());
			if ($this->form_validation->run() === FALSE)
			{			
			    $updateData = array('update_required'=> $this->input->post('update_required'),'latest_version'=> $this->input->post('latest_version'),'latest_version_code'=> $this->input->post('latest_version_code'));
				$this->settings_model->updateApp_setting($updateData);
				redirect(BASE_URL.'admin/settings/site_settings');
			}
			else
			{				
				$this->settings_model->UpdateSitesetting($_POST);
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$data['app_version_setting'] = $this->settings_model->getAppVersionSetting();
				$this->viewAdmin('admin/settings/sitesetting', $data);
			}
		}
	
	}
	public function app_add_settings(){
	    if(isset($_POST) && !empty($_POST)){
			//$this->form_validation->set_rules('update_required', 'Update Required', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('latest_version', 'Latest Version', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('latest_version_code', 'Latest Version Code', 'trim|required|xss_clean');
			print_r($this->form_validation->run());
			if ($this->form_validation->run() === FALSE)
			{			
			    $updateData = array('update_required'=> $this->input->post('update_required'),'latest_version'=> $this->input->post('latest_version'),'latest_version_code'=> $this->input->post('latest_version_code'));
				$this->settings_model->updateApp_setting($updateData);
				redirect(BASE_URL.'admin/settings/add_settings');
			}
			else
			{				
				$this->settings_model->UpdateSitesetting($_POST);
				$data['setting'] = $this->settings_model->getSitesetting_list();
				$data['app_version_setting'] = $this->settings_model->getAppVersionSetting();
				$this->viewAdmin('admin/settings/addsetting', $data);
			}
		}
	
	}
}