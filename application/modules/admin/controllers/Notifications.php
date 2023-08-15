<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Notifications extends MY_Controller {

	public function __construct()
	{
	   parent::__construct();
	   $this->load->model('notifications_model');
	   $this->load->model('users_model');

	   $this->user_id = $this->session->userdata['logged_in']['userid'];
	   $this->type = $this->session->userdata['logged_in']['usertype'];
	   //$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		if($this->checkSess()){
			$this->notifications();
		}else{
			redirect(CURRENT_MODULE.'login');
		}
	}

	public function notifications(){
    	if($this->checkSess()){
            $data['notifications'] = $this->notifications_model->getNotificationsList();
            $this->viewAdmin('admin/notifications/view', $data);
		}else{
			redirect(CURRENT_MODULE.'login');
		}	
	}
	
	function send(){
		if($this->checkSess()){	
			$userId = $this->user_id;
			
			$res = $this->users_model->getSubUser($userId);
			$usertype = $this->session->userdata['logged_in']['usertype'];
			$data['users'] = $this->users_model->getuserslist_new(null, $res, $userId, $usertype);
			$this->viewAdmin('admin/notifications/send',$data);
		}else{
			$this->index();
		}
	}	

	function add(){
		if($this->checkSess()){	
			if(isset($_POST)){
				$thumb_imageURL = '';
				$file_name = '';
			    if (isset($_FILES['notification_file']['name'])) {
			        $file_name = $_FILES['notification_file']['name'];
		            $files = $_FILES;
		            $product_image = array();
		            $imageName = strtotime(date('Y-m-d H:i:s'));
		            $dir = NOTIFICATION_IMAGE_DIR;
		            $key = 'notification_file';
		            $config['upload_path'] = $dir . 'large';
            		$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|txt|xls';
            		//$config['max_size']	= '204800';		
            		$config['file_ext_tolower']	= TRUE;		
            		$config['remove_spaces']	= TRUE;		
            	    $config['file_name'] = $imageName;	
            		
            		$this->load->library('upload', $config);

		            if($this->upload->do_upload($key)){
		                $resultData = $this->upload->data();
		                //$thumb_image = $this->setImageConfigParameter($resultData, PUNCH_THUMB_IMAGE_WIDTH, PUNCH_THUMB_IMAGE_HEIGHT, $imageName, $dir.'thumb');
		                $thumb_imageURL = NOTIFICATION_IMAGE.'large/'.$resultData['file_name'];
		            }
		        }
		        if($_POST['user'][0] == 'all'){
					$userId = $this->user_id;
					$res = $this->users_model->getSubUser($userId);
					$usertype = $this->session->userdata['logged_in']['usertype'];
					$users = $this->users_model->getuserslist_new(null, $res, $userId, $usertype);
					foreach ($users as $value) {
					    $data = array(
							'user_id' => $value->id,
							'title' => $this->input->post('title'),
							'message' => $this->input->post('message'),
							'type' => 1,
							'file_link'=> $thumb_imageURL,
							'file_name' => $file_name,
							
						);
						
					    $this->db->insert('notifications',$data);
						$send_obj = array(
						    "to" => $value->fcm_token,
						  //  "notification" =>  array(
						  //      "body" => $this->input->post('message'),
						  //      "title" => $this->input->post('title'),	
						  //      'file'=> $thumb_imageURL,
						  //      'file_name' => $file_name,
						  //  ),
						    "data" => array(
						        "body" => $this->input->post('message'),
						        "title" => $this->input->post('title'),	
						        'file'=> $thumb_imageURL,
						        'file_name' => $file_name,
						        'user_id' => $value->id,
						        'type' =>1,
						        'id' => $this->db->insert_id(),
						    )
						);
						$this->send_notication($send_obj);
					}
					redirect(CURRENT_MODULE.'notifications');
				}else{
					foreach ($_POST['user'] as $user_id) {
					    $data = array(
							'user_id' => $user_id,
							'title' => $this->input->post('title'),
							'message' => $this->input->post('message'),
							'type' => 1,
							'file_link'=> $thumb_imageURL,
							'file_name' => $file_name,
						);
						$this->db->insert('notifications',$data);
						
						$users = $this->users_model->getUserById($user_id);
						
						$users = $users[0];
						$send_obj = array(
						    "to" => $users->fcm_token,
						  //  "notification" =>  array(
						  //      "body" => $this->input->post('message'),
						  //      "title" => $this->input->post('title'),
						  //      'file'=> $thumb_imageURL,
						  //      'file_name' => $file_name,
						  //  ),
						    "data" => array(
						        "body" => $this->input->post('message'),
						        "title" => $this->input->post('title'),
						        'file'=> $thumb_imageURL,
						        'file_name' => $file_name,
						        'user_id' => $user_id,
						        'type' =>1,
						        'id' => $this->db->insert_id(),
						    )
						);
						
						$this->send_notication($send_obj);
					}
					redirect(CURRENT_MODULE.'notifications');
				}
				
			}else{
				$this->send();	
			}
			
		}else{
			$this->index();
		}
	}

	public function setUploadConfig( $imageName, $path ){
    	$this->createDir($path . 'thumb'); //Create thumb dir if not exist
    	$this->createDir($path . 'large'); //Create large dir if not exist
		$config['upload_path'] = $path . 'large';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
		$config['max_size']	= '204800';		
		$config['file_ext_tolower']	= TRUE;		
		$config['remove_spaces']	= TRUE;		
	    $config['file_name'] = $imageName;
	   	$config['width'] = '1242';
		$config['height'] = '717';	
		
		$this->load->library('upload', $config);									
    }

    public function setImageConfigParameter($uploadData, $width, $height, $imageName, $path = NULL)
    {
        //$this->setUploadConfig( $imageName );
        $newFileName = $imageName . $uploadData['file_ext'];
        $config_var["image_library"] = "gd2";
        $config_var["source_image"] = $uploadData["full_path"];
        $config_var['create_thumb'] = FALSE;
        $config_var['maintain_ratio'] = FALSE;
        $config_var['quality'] = "100%";					
        $config_var['width'] = $width;
        $config_var['height'] = $height;
        $config_var['remove_spaces'] = TRUE;
        $config_var['new_image'] = $path;

        $this->load->library('image_lib', $config_var);
        $this->image_lib->initialize($config_var);
            
        if(!$this->image_lib->resize())
        { 
            return array('status' => 0, 'message' => $this->image_lib->display_errors()); 
        }else{
            $this->image_lib->clear();
            return array('status' => 1, 'message' => $newFileName); 
        }
    }

	function send_notication($json_data){
	    $data = json_encode($json_data);
		$url = 'https://fcm.googleapis.com/fcm/send';
		$server_key = 'AAAARIjtvSI:APA91bH46zJpNTBmFfr8uIr12VBeR0XOfk2NrQHVGQ8w3dvZPkPClAL0j6LranQ1lKyNDi0Qlt4uI1E-lGzjD1-7gQneS9ChSAtzI0FCOcx2leufTR_pBkIkPODHO69QftOlH14L4Z_K';
		$headers = array(
		    'Content-Type:application/json',
		    'Authorization:key='.$server_key
		);
		//CURL request to route notification to FCM connection server (provided by Google)
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		if ($result === FALSE) {
		    //die('Oops! FCM Send Error: ' . curl_error($ch));
		    echo "error";
		}
		curl_close($ch);
		return;
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
				$result = $this->notifications_model->insert();

				if($result){
					$this->session->set_flashdata('flash_message', 'inserted');
					redirect(BASE_URL.'admin/notifications');

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
				$this->notifications();
			}
			else
			{				
				$result = $this->notifications_model->update();

				if($result){
					$this->session->set_flashdata('flash_message', 'updated');
					redirect(BASE_URL.'admin/notifications');

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
    	$res = $this->notifications_model->checkDuplicate($para1, $para2);
    	
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
    	$data['notifications'] = $this->notifications_model->getNotificationsList(1);        
        $data['notifications'] = $data['notifications'][0];
        if(date("Y-m-d H:i", strtotime($data['notifications']->punch_in_date)) == date("Y-m-d H:i")){
        	$check_off_day = $this->notifications_model->checkDayOff();
        	if(!$check_off_day){
        		$this->load->model('users_model');
        		$user_data = $this->users_model->getAttendanceList('absent');
        		$notifications_number = '';
        		foreach ($user_data as $key => $value) {
        			// echo "data 1<pre>";
        			// print_r($value);
        			$notifications_number .= $value->phone.',';
        		}
        		$url = "http://smsbomb.online/sendsms.aspx?mobile=7573043495&pass=IJLNE&senderid=NUCERA&to=".$notifications_number."&msg=".$data['notifications']->punch_in_message;
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

        if(date("Y-m-d H:i", strtotime($data['notifications']->punch_out_date))== date("Y-m-d H:i")){
        	$check_off_day = $this->notifications_model->checkDayOff();
        	if(!$check_off_day){
        		$this->load->model('users_model');
        		$user_data = $this->users_model->getAttendanceList('punch-in');
        		$notifications_number = '';
        		foreach ($user_data as $key => $value) {
        			// echo "data 1<pre>";
        			// print_r($value);
        			$notifications_number .= $value->phone.',';
        		}
        		$url = "http://smsbomb.online/sendsms.aspx?mobile=7573043495&pass=IJLNE&senderid=NUCERA&to=".$notifications_number."&msg=".$data['notifications']->punch_in_message;
        		//$url = str_replace(' ', '+', $url);
				// $ch = curl_init();
				// curl_setopt($ch, CURLOPT_URL, $url);
				// curl_setopt($ch, CURLOPT_TIMEOUT, 100);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// $data = curl_exec($ch);
				// curl_close($ch);
        	}  
        }	
        // $data['notifications']->punch_in_date = date("m/d/Y g:i A", strtotime($data['notifications']->punch_in_date));
        // $data['notifications']->punch_out_date = date("m/d/Y g:i A", strtotime($data['notifications']->punch_out_date));
    }

    
}