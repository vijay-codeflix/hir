<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('categories_model');
        //$this->output->enable_profiler(TRUE);
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

            $category_id = (isset($_POST['category'])) ? $_POST['category'] : NULL;
            // if(isset($_POST['category'])){
            //     echo $_POST['category'];exit;
            // }
            $data['category_id'] = $category_id;


            $data['productstype'] = $this->categories_model->getProductCategoriesList();
            $data['products'] = $this->products_model->getproducts($category_id);


            $this->viewAdmin('admin/products/view', $data);
        } else {
            redirect(CURRENT_MODULE . 'login');
        }
    }

    public function getListView()
    {
        $id = $_POST['id'];

        $visits = $this->products_model->getajaxproduct($id);
        echo json_encode($visits);
    }

    public function add()
    {
        // check user already logged in OR not
        if ($this->checkSess()) {
            //            $data['getEmployees'] = $this->products_model->getUserByType(4);
            //            $data['getDealerCategories'] = $this->products_model->getDealerCategories();
            $data['category'] = $this->categories_model->getProductCategoriesList();
            $this->viewAdmin('admin/products/add', $data);
        } else {
            $this->index();
        }
    }

    public function insert()
    {
        if ($this->checkSess()) {
            //Set Rule for Validation

            $this->form_validation->set_rules('category_id', 'Category', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('name', 'Product', 'trim|required|strip_tags|xss_clean');
            $this->form_validation->set_rules('unit', 'Unit', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('ah', 'AH', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('mrp', 'MRP', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('default_dealer_price', 'Default Dealer Price', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('sort_order', 'Sort', 'trim|required|numeric|xss_clean');


            if ($this->form_validation->run() === FALSE) {
                $this->add();
            } else {
                $params = $this->input->post();
                $params['item_code'] = $this->products_model->getlastid();
                $params['item_code'] = $params['item_code'][0]->id;
                $params['item_code'] = date('dm') . $params['item_code'];
                $result = $this->products_model->addProduct($params);
                if ($result) {
                    $this->session->set_flashdata('flash_message', 'inserted');
                    redirect(BASE_URL . 'admin/products/view');
                } else {
                    $this->session->set_flashdata('flash_message', 'Data not inserted');
                    redirect(BASE_URL . 'admin/products/add');
                }
            }
        } else {
            $this->index();
        }
    }


    public function edit($id = NULL)
    {
        if ($this->checkSess()) {


            $product = $this->products_model->getproducts('', $id);

            if (!$product) {
                $this->index();
            } else {
                $data['category'] = $this->categories_model->getProductCategoriesList();
                $data['products'] = $product;
                //                echo "<pre>";
                //                exit();

                $this->viewAdmin('admin/products/edit', $data);
            }
        } else {
            $this->index();
        }
    }

    public function update()
    {
        if ($this->checkSess()) {
            $id = str_replace('/', '_', rtrim(base64_encode($this->input->post('id')), '='));
            //Set Rule for Validation
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('name', 'Product', 'trim|required|strip_tags|xss_clean');
            $this->form_validation->set_rules('unit', 'Unit', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('ah', 'AH', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('mrp', 'MRP', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('default_dealer_price', 'Default Dealer Price', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('sort_order', 'Sort', 'trim|required|numeric|xss_clean');
            // echo "<pre>";
            // print_r($this->form_validation);
            // exit();
            if ($this->form_validation->run() === FALSE) {
                $this->edit($id);
            } else {
                 
                $result = $this->products_model->update();

                if ($result) {
                    $this->session->set_flashdata('flash_message', 'updated');
                    redirect(BASE_URL . 'admin/products/view');
                } else {
                    $this->session->set_flashdata('flash_message', 'not_updated');
                    $this->index();
                }
            }
        } else {
            $this->index();
        }
    }

    public function delete($id = NULL)
    {

        echo $id;

        if ($this->products_model->deleteProduct($id) == TRUE) {
            echo "1";
            $this->session->set_flashdata('flash_message', 'Deleted');
        } else {
            $this->session->set_flashdata('flash_message', 'not_deleted');
        }

        redirect(CURRENT_MODULE . 'products/view');
    }

    public function importDealers()
    {
        $fileName = $_FILES['csv']['tmp_name'];
        $handle = fopen($fileName, "r");
        $c = 0; //
        while (($filesop = fgetcsv($handle)) !== false) {
            if ($c <> 0) {                    //SKIP THE FIRST ROW
                if (isset($filesop[0]) && $filesop[0] != "") {
                    $emp_id = $filesop[0];
                    $employeeData = $this->expenses_model->getUserByEmpId($emp_id);

                    if ($employeeData != false) {
                        $isInvalid = false;
                        $insertData = array();

                        $insertData['category_id'] = $employeeData[0]->id;

                        if (isset($filesop[1]) && $filesop[1] != "") {
                            $insertData['dealer_name'] = $filesop[1];
                        } else {
                            $isInvalid = true;
                        }

                        if (isset($filesop[2]) && $filesop[2] != "") {
                            $insertData['dealer_phone'] = $filesop[2];
                        } else {
                            $isInvalid = true;
                        }

                        if (isset($filesop[3]) && $filesop[3] != "") {
                            $insertData['firm_name'] = $filesop[3];
                        } else {
                            $isInvalid = true;
                        }

                        if (isset($filesop[4]) && $filesop[4] != "") {
                            $insertData['address'] = $filesop[4];
                        } else {
                            $isInvalid = true;
                        }

                        if (isset($filesop[5]) && $filesop[5] != "") {
                            $insertData['city_or_town'] = $filesop[5];
                        } else {
                            $isInvalid = true;
                        }

                        if (isset($filesop[6]) && $filesop[6] != "") {
                            $insertData['gst_number'] = $filesop[6];
                        } else {
                            $isInvalid = true;
                        }

                        //aadhar option optional
                        if (isset($filesop[7]) && $filesop[7] != "") {
                            $insertData['dealer_aadhar'] = $filesop[7];
                        }

                        if (!$isInvalid) {
                            $this->expenses_model->addParty($insertData);
                        }
                    }
                }
            }
            $c = $c + 1;
        }
        $this->session->set_flashdata('flash_message', 'imported');
        redirect(CURRENT_MODULE . 'dealers/view');
    }
}
