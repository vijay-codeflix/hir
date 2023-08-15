<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Messages extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('messages_model');

	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->messages();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function messages(){
    	if($this->checkSess()){
            $data['messages'] = $this->messages_model->getMessagesList(1);
            //echo "<pre>";

            $data['messages'] = $data['messages'][0];
            $data['messages']->punch_in_date = date("m/d/Y g:i A", strtotime($data['messages']->punch_in_date));
            $data['messages']->punch_out_date = date("m/d/Y g:i A", strtotime($data['messages']->punch_out_date));

            // print_r($data['messages']);
            // exit();
            $this->viewAdmin('admin/messages/messagesview', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	function offdayadd(){
		if($this->checkSess()){	
			$this->viewAdmin('admin/messages/add');
		}else{
			$this->index();
		}
	}

	function offdayedit($id){
		if($id){
			if($this->checkSess()){	
                $data['catDetails'] = $this->messages_model->getMessagesList(base64_decode($id));
                $this->viewAdmin('admin/messages/edit', $data);
			}else{
				$this->index();
			}
		}else{
			redirect(CURRENT_MODULE . 'messages');
		}
	}	
	
	public function delete($id = NULL)
	{
		$id = base64_decode($id);
		
		if($this->messages_model->delete($id) == TRUE)
		{
			$this->session->set_flashdata('flash_message', 'Deleted');
		}
		else
		{
			$this->session->set_flashdata('flash_message', 'not_deleted');
		}
		redirect(CURRENT_MODULE.'messages');
	}	

	public function insert(){
		if($this->checkSess()){
			//Set Rule for Validation
			$this->form_validation->set_rules('type', 'message type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('title', 'message title', 'trim|required|xss_clean');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->offdayadd();
			}
			else
			{				
				$result = $this->messages_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/messages');

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
			$id = 1;//str_replace('/', '_',rtrim(base64_encode($this->input->post('id')), '=')); 
			//Set Rule for Validation
			$this->form_validation->set_rules('punch_in_date', 'Punch In Date', 'trim|required|xss_clean');
			$this->form_validation->set_rules('punch_in_message', 'Punch In Message', 'trim|required|xss_clean');
			$this->form_validation->set_rules('punch_out_date', 'Punch Out Date', 'trim|required|xss_clean');
			$this->form_validation->set_rules('punch_out_message', 'Punch Out Message', 'trim|required|xss_clean');

			if ($this->form_validation->run() === FALSE)
			{			
				$this->messages();
			}
			else
			{				
				$result = $this->messages_model->update();

				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/messages');

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
    	$res = $this->messages_model->checkDuplicate($para1, $para2);
    	
    	if($res == 'FALSE'){
    		$this->form_validation->set_message('checkDuplicate', 'This '.$para2.' is already used, Please choose diffrent');	
    		return false;
    	}else{
    		return true;
    	}		
    }

    public function send_message()
    {
    	/*Get Message*/
    	$data['messages'] = $this->messages_model->getMessagesList(1);        
        $data['messages'] = $data['messages'][0];
        if(date("Y-m-d H:i", strtotime($data['messages']->punch_in_date)) == date("Y-m-d H:i")){
        	$check_off_day = $this->messages_model->checkDayOff();
        	if(!$check_off_day){
        		$this->load->model('users_model');
        		$user_data = $this->users_model->getAttendanceList('absent');
        		$messages_number = '';
        		foreach ($user_data as $key => $value) {
        			// echo "data 1<pre>";
        			// print_r($value);
        			$messages_number .= $value->phone.',';
        		}
        		$url = "http://smsbomb.online/sendsms.aspx?mobile=7573043495&pass=IJLNE&senderid=NUCERA&to=".$messages_number."&msg=".$data['messages']->punch_in_message;
    			//$url = str_replace(' ', '+', $url);
				// $ch = curl_init();
				// curl_setopt($ch, CURLOPT_URL, $url);
				// curl_setopt($ch, CURLOPT_TIMEOUT, 100);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// $data = curl_exec($ch);
				// curl_close($ch);
        		//http://smsbomb.online/sendsms.aspx?mobile=7573043495&pass=your_password&senderid=mysenderid&to=myrecipient1,myrecipient2,myrecipient3&msg=Your msg
        		//http://smsbomb.online/sendsms.aspx?mobile=7573043495&pass=IJLNE&senderid=NUCERA&to=9672752600,7041200926,&msg=Test Developer Message
        		//NUCERA
        		
        	}        	
        }

        if(date("Y-m-d H:i", strtotime($data['messages']->punch_out_date))== date("Y-m-d H:i")){
        	$check_off_day = $this->messages_model->checkDayOff();
        	if(!$check_off_day){
        		$this->load->model('users_model');
        		$user_data = $this->users_model->getAttendanceList('punch-in');
        		$messages_number = '';
        		foreach ($user_data as $key => $value) {
        			// echo "data 1<pre>";
        			// print_r($value);
        			$messages_number .= $value->phone.',';
        		}
        		$url = "http://smsbomb.online/sendsms.aspx?mobile=7573043495&pass=IJLNE&senderid=NUCERA&to=".$messages_number."&msg=".$data['messages']->punch_in_message;
        		//$url = str_replace(' ', '+', $url);
				// $ch = curl_init();
				// curl_setopt($ch, CURLOPT_URL, $url);
				// curl_setopt($ch, CURLOPT_TIMEOUT, 100);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// $data = curl_exec($ch);
				// curl_close($ch);
        	}  
        }	
        // $data['messages']->punch_in_date = date("m/d/Y g:i A", strtotime($data['messages']->punch_in_date));
        // $data['messages']->punch_out_date = date("m/d/Y g:i A", strtotime($data['messages']->punch_out_date));
    }

    
}