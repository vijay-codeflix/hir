<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Followup extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Follow_up_model');
        $this->load->model('expenses_model');
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


            $data =  array();
            $type_id = (isset($_POST['type_follow'])) ? $_POST['type_follow'] : NULL;
            $data['type_id_id'] = $type_id;
            $data['datas'] = $this->Follow_up_model->get_follow_up($type_id);
            $data['types'] = $this->Follow_up_model->get_type_data();
            // echo "<pre>";
            // print_r($data);
            // exit();
            $this->viewAdmin('admin/follow_up/view', $data);
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }

    function getListView()
    {
        $id = $_POST['id'];

        $visits = $this->Follow_up_model->get_follow_up($id);
        echo json_encode($visits);
    }
    function add()
    {
        if ($this->checkSess()) {
            $data['types'] = $this->Follow_up_model->get_type_data();
            // $data['party'] = $this->expenses_model->getDealers();
            $data['party'] = [];
            $data['users'] = $this->expenses_model->getUserByType(4);
            // echo "<pre>";
            // print_r(date('d/m/Y'));
            // exit();
            $this->viewAdmin('admin/follow_up/add', $data);
        } else        $this->index();
    }

    public function insert()
    {
        if ($this->checkSess()) {
            //Set Rule for Validation

            $this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('submit_date', 'Submit Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('party_id', 'Party Type', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('follow_up_type_id', 'Follow Up Type', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('follow_up_date', 'Follow Up Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('remark', 'Remark', 'trim|required|xss_clean');


            if ($this->form_validation->run() === FALSE) {
                $this->add();
            } else {
                $params = $this->input->post();
                $params['number'] = $this->Follow_up_model->getNextFollowUpNumber();
                $params['submit_date']  = date("Y-m-d", strtotime($params['submit_date']));
                $params['follow_up_date']  = date("Y-m-d", strtotime($params['follow_up_date']));
                $result = $this->Follow_up_model->insert($params);
                if ($result) {
                    $this->session->set_flashdata('flash_message', 'inserted');
                    redirect(BASE_URL . 'admin/followup');
                } else {
                    $this->session->set_flashdata('flash_message', 'Data not inserted');
                    redirect(BASE_URL . 'admin/followup');
                }
            }
        } else {
            $this->index();
        }
    }
}
