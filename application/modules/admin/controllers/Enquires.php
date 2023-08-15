<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Enquires extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('enquires_model');
        $this->load->model('Api');
    }


    public function index()
    {
        if ($this->checkSess()) {
            $this->enquiry();
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }

    public function enquiry()
    {
        if ($this->checkSess()) {
            $data['enquiryview'] = $this->enquires_model->getEnquiryList();
            //            echo "<pre/>";print_r($data);exit;
            $this->viewAdmin('admin/enquires/list', $data);
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }


    public function getCurrentEnquiry()
    {
        if (isset($_POST['id'])) {
            $id = base64_decode($_POST['id']);
            $result  = $this->enquires_model->getEnquiryById($id);
            echo json_encode($result);
        }
    }

    public function add()
    {
        //check user already logged in OR not
        if ($this->checkSess()) {
            // 			$data['countrylist'] = $this->countries_model->getCountryList();
            $data['enquiry_no'] = $this->Api->getNextInquiryNumber();
            // $data['emp_id'] = $this->enquires_model->getUserByType(4);
            $data['user_id'] = $this->session->userdata['logged_in']['userid'];
            $data['party_category'] = $this->enquires_model->getPartyCategories();
            $data['product_category'] = $this->enquires_model->getProductCategories();
            // pr($data,1);exit;
            $this->viewAdmin('admin/enquires/add', $data);
        } else {
            $this->index();
        }
    }

    public function insert()
    {
        $data = $this->input->post();

        if ($this->checkSess()) {
            //Set Rule for Validation
            $this->form_validation->set_rules('enquiry_no', 'Inquiry Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('party_category_id', 'Part category', 'trim|required|xss_clean');
            // $this->form_validation->set_rules('party_id', 'Party name', 'trim|required|xss_clean');
            // $this->form_validation->set_rules('product_category_id', 'Product category', 'trim|required|xss_clean');
            // $this->form_validation->set_rules('product_id', 'Product name', 'trim|required|xss_clean');
            // $this->form_validation->set_rules('product', 'Product', 'trim|required|xss_clean');
            // print_r(form_error('party_id'));
            // exit();
            if ($this->form_validation->run() === FALSE) {
                $this->add();
            } else {
                $data = $this->input->post();
                $data['date'] = date('Y-m-d H:i:s');
                // pr($data,1);exit;
                $reqPara = [];
                $reqPara['party_category_id'] = $data['party_category_id'];
                $reqPara['party_id'] = isset($data['party_id']) ? $data['party_id'] : NULL;
                $reqPara['party_name'] = isset($data['party_name']) ? $data['party_name'] : NULL;
                $reqPara['user_id'] = $this->session->userdata['logged_in']['userid'];
                $reqPara['enquiry_no'] = $data['enquiry_no'];
                $reqPara['date'] = $data['date'];

                $enquiry = $this->db->insert('enquiries', $reqPara);
                $result = '';
                // if ($enquiry) {
                //     $enquiry_id = $this->db->insert_id();
                //     $element = [];
                //     $element['enquiry_id'] = $enquiry_id;
                //     $element['product_id'] = $data['product_id'];
                //     $element['category_id'] = $data['product_category_id'];
                //     $result = $this->db->insert('enquiry_products', $element);
                // }
                if ($enquiry) {
                    $po_id = $this->db->insert_id();
                    foreach ($data['product'] as $product_order) {
                        $element = [];
                        $element['enquiry_id'] = $po_id;
                        $element['product_id'] = $product_order['product_id'];
                        $element['category_id'] = $product_order['product_category_id'];
                        $this->db->insert('enquiry_products', $element);
                    }
                }
                if ($enquiry) {
                    $this->session->set_flashdata('flash_message', 'inserted');
                    // 	redirect(BASE_URL.'admin/enquires/view');
                } else {
                    $this->session->set_flashdata('flash_message', 'Data not inserted');
                    // 	redirect(BASE_URL.'admin/enquires/view');
                }
                redirect(CURRENT_MODULE . 'enquires');
            }
        } else {
            $this->index();
        }
    }

    //     function edit($id = NULL)
    // 	{
    // 		if($id){
    // 			if($this->checkSess()){	
    //                 $data['cities'] = $this->city_model->getCityById(base64_decode($id));
    //                 $data['countrylist'] = $this->countries_model->getCountryList();
    // 				$data['zonelist'] =$this->states_model->getZonesListByCountry($data['cities'][0]->country_id);
    // 				$data['stateslist'] =$this->states_model->getStatesListByZone($data['cities'][0]->zone_id);
    //                 $this->viewAdmin('admin/cities/edit', $data);
    // 			}else{
    // 				$this->index();
    // 			}
    // 		}else{
    // 			redirect(CURRENT_MODULE . 'cities/view');
    // 		}
    // 	}

    // 	public function update(){
    // 		if($this->checkSess()){
    // 			$id = str_replace('/', '_',rtrim(base64_encode($this->input->post('id')), '=')); 
    // 			//Set Rule for Validation
    // 			$this->form_validation->set_rules('grade', 'City Grade', 'trim|required|xss_clean');
    // 			$this->form_validation->set_rules('zone_id', 'Zone name', 'trim|required|xss_clean');
    // 			$this->form_validation->set_rules('country_id', 'Country name', 'trim|required|xss_clean');
    // 			$this->form_validation->set_rules('state_id', 'State name', 'trim|required|xss_clean');
    // 			$this->form_validation->set_rules('city_name', 'City name', 'trim|required|xss_clean|callback_checkDuplicate[City name]');

    // 			if ($this->form_validation->run() === FALSE)
    // 			{			
    // 				$this->edit($id);
    // 			}
    // 			else
    // 			{				
    // 				$result = $this->city_model->update();

    // 				if($result){
    // 					$this->session->set_flashdata('flash_message', 'updated');
    // 					redirect(BASE_URL.'admin/cities/view');

    // 				}else{
    // 					$this->session->set_flashdata('flash_message', 'not_updated');
    // 					$this->index();
    // 				}
    // 			}
    // 		}
    // 		else
    // 		{
    // 			$this->index();	
    // 		}
    // 	}

    public function delete($id = NULL)
    {
        $id = base64_decode($id);
        if ($this->enquires_model->delete($id) == TRUE) {
            $this->session->set_flashdata('flash_message', 'Deleted');
        } else {
            $this->session->set_flashdata('flash_message', 'not_deleted');
        }
        redirect(CURRENT_MODULE . 'enquires');
    }

    public function getPartyByCategory($id = NULL)
    {
        $data = $this->enquires_model->getPartyByCategory($id);
        echo json_encode($data);
    }

    public function getProductByCategory($id = NULL)
    {
        $data = $this->enquires_model->getProductByCategory($id);
        echo json_encode($data);
    }
}
