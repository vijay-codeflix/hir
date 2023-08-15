<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dealersproduct extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dealersproduct_model');
        $this->load->model('dealers_model');
        $this->load->model('expenses_model');
        $this->load->model('products_model');
        $this->user_id = $this->session->userdata['logged_in']['userid'];
        $this->type = $this->session->userdata['logged_in']['usertype'];
        //$this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        if($this->checkSess()){
            $this->dealerProduct();
        }else{
            redirect(CURRENT_MODULE.'login');
        }
    }

    public function dealerProduct(){
        if($this->checkSess()){
            $data['dealer_productsview'] = $this->Dealersproduct_model->getDealerProductsList(null);
            $this->viewAdmin('admin/dealersproduct/list', $data);
        }else{
            redirect(CURRENT_MODULE.'login');
        }
    }
    
    
    public function dealerLists(){
        if($this->checkSess()){
           $empId = (isset($_POST['select_employee']))? $_POST['select_employee'] : NULL;
            $catId = (isset($_POST['select_category']))? $_POST['select_category'] : NULL;
            $typeId = (isset($_POST['select_type']))? $_POST['select_type'] : NULL;
            $startDate = (isset($_POST['start_date']))? str_replace("/","-", $_POST['start_date']) : "";
			$endDate = (isset($_POST['end_date']))? str_replace("/","-", $_POST['end_date']) : "";
          
            $data['empId'] = $empId;
            $data['catId'] = $catId;
            $data['typeId'] = $typeId;
			$data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['getCategories'] = $this->dealers_model->getDealerCategoriesList();
            $data['getTypes'] = $this->dealers_model->getDealerTypeList();
            $data['getEmployees'] = $this->expenses_model->getUserByType(4);
            $data['dealers'] = $this->expenses_model->getDealers($typeId, $catId, $empId, $startDate, $endDate, NULL);
            
            $this->viewAdmin('admin/dealersproduct/dealer_lists', $data);
        }else{
            redirect(CURRENT_MODULE.'login');
        }
    }
    
    public function product_list($dealer_id){
        if($this->checkSess()){
            $data['dealer_id'] = base64_decode($dealer_id);
            $data['dealer_details'] = base64_decode($dealer_id);
            $data['dealer_productsview'] = $this->Dealersproduct_model->getDealerProductsList(base64_decode($dealer_id));
            // pr($data, 1);exit();
            $this->viewAdmin('admin/dealersproduct/product_list', $data);
        }else{
            redirect(CURRENT_MODULE.'login');
        }
    }

    public function dealer_product_add(){
        if($this->checkSess()){
            $data['getDealers'] = $this->Dealersproduct_model->getPartiesList();
            $data['getProducts'] = $this->Dealersproduct_model->getProductsList();
            $this->viewAdmin('admin/dealersproduct/add', $data);
        }else{
            $this->index();
        }
    }

    public function dealer_product_edit($id){
        // print_r($id);exit;
        if($id){
            if($this->checkSess()){
                $data['getDealers'] = $this->Dealersproduct_model->getPartiesList();
                $data['getProducts'] = $this->Dealersproduct_model->getProductsList();
                $data['dealer_product_details'] = $this->Dealersproduct_model->getDealerProductsDetails(base64_decode($id));
                $data['dealer_product_details'][0]->product_id = explode(',', $data['dealer_product_details'][0]->product_id);
                // pr($data['dealer_product_details'], 1);exit();
                $this->viewAdmin('admin/dealersproduct/edit', $data);
            }else{
                $this->index();
            }
        }else{
            redirect(CURRENT_MODULE . 'dealersproducts/');
        }
    }
    
    public function product_edit($product_id, $dealer_id){
        $product_id = base64_decode($product_id);
        $dealer_id = base64_decode($dealer_id);
        if($product_id && $dealer_id){
            if($this->checkSess()){
                $data['dealer'] = $this->expenses_model->getDealers(NULL, NULL, NULL, NULL, NULL, $dealer_id);
                $data['product'] = $this->products_model->getproducts('',$product_id);
                $data['amount'] = $this->Dealersproduct_model->getDealerProductsByDealerAndProduct($dealer_id, $product_id);
                // pr($data, 1);
                $this->viewAdmin('admin/dealersproduct/edit', $data);
            }else{
                $this->index();
            }
        }else{
            redirect(CURRENT_MODULE . 'dealersproducts/');
        }
    }


    public function delete($dealer_id = NULL)
    {
        // $dealer_id = base64_decode($dealer_id);
        
        // echo $dealer_id;exit;
        if($this->Dealersproduct_model->delete($dealer_id) == TRUE)
        {
            $this->session->set_flashdata('flash_message', 'Deleted');
        }
        else
        {
            $this->session->set_flashdata('flash_message', 'not_deleted');
        }
        redirect(CURRENT_MODULE.'dealersproduct');
    }

    public function insert(){
        if($this->checkSess()){
            // print_r($_POST);exit;
            //Set Rule for Validation
            $this->form_validation->set_rules('dealer_id', 'Dealer Name', 'trim|required|xss_clean');
            // $this->form_validation->set_rules('product_id[]', 'Product Name', 'trim|required|multiple_select|xss_clean');
            // $this->form_validation->set_rules('dealer_price', 'Dealer Price', 'trim|required|xss_clean');

            if ($this->form_validation->run() === FALSE)
            {
                $this->dealer_product_add();
            }
            else
            {
                // print_r($_POST);exit;
                $result = $this->Dealersproduct_model->insert();

                if($result){
                    $this->session->set_flashdata('flash_message', 'inserted');
                    redirect(BASE_URL.'admin/dealersproduct');

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
            //Set Rule for Validation
            $this->form_validation->set_rules('dealer_id', 'Dealer Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('product_id', 'Product Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('dealer_price', 'Dealer Price', 'trim|required|xss_clean');

            if ($this->form_validation->run() === FALSE)
            {
                $this->product_edit($id);
            }
            else
            {
                $request = $this->input->post();
                // pr($request ,1);
                $result = $this->Dealersproduct_model->update($request['dealer_id'], $request['product_id'], $request['dealer_price']);
                if($result){
                    $this->session->set_flashdata('flash_message', 'updated');
                    redirect(BASE_URL.'admin/dealersproduct/dealerLists');

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
    
}