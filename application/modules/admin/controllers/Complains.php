<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Complains extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Complains_model');
        $this->load->model('status_model');
    }

    public function index()
    {

        if ($this->checkSess()) {
            $this->view();
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }

    public function view()
    {
        if ($this->checkSess()) {
            $data = array();
            $data = $this->Complains_model->all();
            //            echo "<pre>";
            //            print_r($data);
            //            exit();

            $this->viewAdmin('admin/complains/view', $data);
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }
    function add()
    {
        if ($this->checkSess()) {
            $data['types'] = $this->Complains_model->getComplainType();
            $data['party'] = [];
            $data['users'] = $this->Complains_model->getUserByType(4);
            $this->viewAdmin('admin/complains/add', $data);
        } else        $this->index();
    }

    public function insert()
    {
        if ($this->checkSess()) {
            //Set Rule for Validation

            $this->form_validation->set_rules('user_id', 'Employee', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('Date', 'Submit Date', 'trim|required|xss_clean');
            // $this->form_validation->set_rules('party_id', 'Party Type', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('complain_type_id', 'Complain Type', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('remark', 'Remark', 'trim|required|xss_clean');


            if ($this->form_validation->run() === FALSE) {
                $this->add();
            } else {
                $params = $this->input->post();
                $params['complain_no'] = $this->Complains_model->getNextComplainNumber();
                // $params['Date']  = date("Y-m-d", strtotime($params['Date']));

                $inputDateTime = DateTime::createFromFormat('d/m/Y', $params['Date']);

                // Format the DateTime object to the desired output format
                $params['Date']  = $inputDateTime->format('Y-m-d');
                // print_r($params);
                // exit();
                $result = $this->Complains_model->insert($params);
                if ($result) {
                    $this->session->set_flashdata('flash_message', 'inserted');
                    redirect(BASE_URL . 'admin/complains');
                } else {
                    $this->session->set_flashdata('flash_message', 'Data not inserted');
                    redirect(BASE_URL . 'admin/complains');
                }
            }
        } else {
            $this->index();
        }
    }

    public function add_action($id = NULL)
    {
        if ($this->checkSess()) {
            $data['status_types'] = $this->status_model->getStatusList();
            $data['complaint_data'] = $this->Complains_model->getComplaintById($id)[0];
            // pr($data);
            $this->viewAdmin('admin/complains/action', $data);
        } else        
        $this->index();
    }

    public function insert_action($id = null)
    {
        // pr($id);
        if ($this->checkSess()) {
            //Set Rule for Validation

            $this->form_validation->set_rules('status_id', 'Status', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('admin_remark', 'Remark', 'trim|required|xss_clean');


            if ($this->form_validation->run() === FALSE) {
                // pr($this->form_validation->error_array());
                $this->add_action($id);
            } else {
                $params = $this->input->post();
                $params['id'] = $id;
                // pr($params);
                $result = $this->Complains_model->update($params);
                if ($result) {
                    $this->session->set_flashdata('flash_message', 'Updated successfully');
                    redirect(BASE_URL . 'admin/complains');
                } else {
                    $this->session->set_flashdata('flash_message', 'Data not inserted');
                    redirect(BASE_URL . 'admin/complains');
                }
            }
        } else {
            $this->index();
        }
    }
}
