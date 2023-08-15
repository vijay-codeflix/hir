<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productorder extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('productorder_model');
        $this->user_id = $this->session->userdata['logged_in']['userid'];
        $this->type = $this->session->userdata['logged_in']['usertype'];
        //$this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        if ($this->checkSess()) {
            $this->po();
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }

    public function po()
    {
        if ($this->checkSess()) {
            $data['poview'] = $this->productorder_model->getPoList();
            // echo "<pre>";
            // print_r($data);
            // exit();
            $this->viewAdmin('admin/productorder/list', $data);
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }


    public function getCurrentPo($id)
    {
        if ($this->checkSess()) {
            $id = base64_decode($id);
            $data['product_details']  = $this->productorder_model->getPoById($id);
            $data['product_order_details']  = $this->productorder_model->getProductOrderDetails($id);
            // echo "<pre/>";
            // print_r($id);
            // exit;
            $this->viewAdmin('admin/productorder/view', $data);
            //            echo json_encode($result);
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }

    public function addDisptachDate()
    {

        if ($this->checkSess()) {
            $params = $this->input->post();

            $this->form_validation->set_rules('admin_dispatch_date', 'Complain Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('id', 'id', 'trim|required|xss_clean');
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('flash_message', 'Date is Required');
                redirect(BASE_URL . 'admin/productorder/getCurrentPo/' . $params['id']);

                // $this->getCurrentPo($params['id'] ?? 0);
            } else {
                $id = base64_decode($params['id']);
                $params['admin_dispatch_date']  = date("Y-m-d", strtotime($params['admin_dispatch_date']));
                unset($params['id']);
                if ($this->productorder_model->updateProductOrderDispatch($params, $id))
                    $this->session->set_flashdata('flash_message', 'inserted');
                else
                    $this->session->set_flashdata('flash_message', 'not inserted');
                redirect(BASE_URL . 'admin/productorder');
            }
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }
}
