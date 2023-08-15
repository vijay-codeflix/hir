<?php

class Api extends MY_Model
{

    /** Defining parent constructor */
    function __consruct()
    {
        parent::__consruct();
    }

    function checkDataExist($fieldname, $fieldValue, $tbname, $chkfield = NULL, $select = "*")
    {
        $this->db->select($select);

        $this->db->from($tbname);

        $this->db->where($fieldValue);

        if ($chkfield != NULL) {
            $this->db->where_not_in($fieldname, $chkfield);
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function validate_user()
    {
        $data = $this->input->post();
        if ($data['phone'] == '9898121212') {
            $sql = "SELECT * FROM users WHERE phone = '" . $data['phone'] . "' AND is_deleted = 0 AND user_type = 4";
        } else {
            $sql = "SELECT * FROM users WHERE ( phone = '" . $data['phone'] . "' AND device_id = '" . $data['device_id'] . "' AND device_model = '" . $data['device_model'] . "' AND user_type = 4) OR (device_id IS NULL AND device_model IS NULL  AND phone = '" . $data['phone'] . "' AND user_type = 4)";
        }
        $query = $this->db->query($sql);
        $response = $query->result();
        $query->free_result();

        if ($response) {
            $this->db->where('id', $response[0]->id);
            $query = $this->db->update('users', $data);
            if ($query != 0) {
                return $response;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function cust_signin()
    {
        $custpass = md5($this->input->post('cust_password'));
        $cust_code   = $this->input->post('cust_code');
        $mobile      = $this->input->post('cust_mob_num');
        $device_id   = $this->input->post('device_id');
        $device_type = $this->input->post('device_type');

        // if same device_id exist in any user then update this device id.
        if ($device_id != '' || $device_id != NULL) {
            $this->db->set('device_id', NULL);
            $this->db->set('device_type', NULL);
            $this->db->where('device_id', $device_id);
            $this->db->update('customers');
        }

        $this->db->distinct();
        $this->db->select('cust.status as custStatus,city.status as cityStatus, whs.status as whsStatus,loc.status as locStatus');
        $this->db->select('cust.cust_id,cust.city_id,cust.cust_code,cust.first_name,cust.last_name,cust.credit_limit,cust.mobile,cust.pass_changed,whs.whs_id,cust.ver_code', false);
        $this->db->from('customers cust');
        $this->db->join('cities city', 'city.city_id = cust.city_id', 'left');
        $this->db->join('locations loc', 'loc.loc_id = cust.location_id', 'left');
        $this->db->join('warehouses whs', 'whs.whs_id = loc.whs_id', 'left');
        $this->db->where('password', $custpass);
        if ($this->input->post('cust_mob_num')) {
            $this->db->where('mobile', $mobile);
        } else {
            $this->db->where('cust_code', $this->input->post('cust_code'));
        }

        $query = $this->db->get();
        $result = $query->result();
        $resVal = array();

        if ($result) {
            if ($result[0]->custStatus == INACTIVE) {
                return array("response" => array("status" => 0, "message" => EXIT_STATUS_ERROR));
            } else {
                $this->db->set('device_type', $device_type);
                $this->db->set('device_id', $device_id);
                $this->db->where('cust_id', $result[0]->cust_id);
                $this->db->update('customers');

                // check customer's city, warehouse or location should not inactive
                if ($result[0]->locStatus == 0) {
                    return array("response" => array("status" => 0, "message" => LOC_INACTIVE_MSG));
                } else if ($result[0]->whsStatus == 0) {
                    return array("response" => array("status" => 0, "message" => LOC_INACTIVE_MSG));
                } else if ($result[0]->cityStatus == 0) {
                    return array("response" => array("status" => 0, "message" => CITY_INACTIVE_MSG));
                } else {


                    //$todaydate = date('Y-m-d'); //today date.
                    //$tomorrowdate = date('Y-m-d ', strtotime('+1 day', strtotime($todaydate))); //tomorrowdate.
                    //$cartinfo = $this->Api->getCartinfo($result[0]->cust_id,$tomorrowdate); //use for get tomorrowcart informaion.
                    $cartinfo = '';

                    $resVal[] = array(
                        "cust_id"           => $result[0]->cust_id,
                        "city_id"           => $result[0]->city_id,
                        "whs_id"            => $result[0]->whs_id,
                        "cust_code"         => $result[0]->cust_code,
                        "first_name"        => $result[0]->first_name,
                        "last_name"         => $result[0]->last_name,
                        "cust_deposited"    => $result[0]->credit_limit,
                        "cust_mob_num"      => $result[0]->mobile,
                        "pass_changed"      => $result[0]->pass_changed,
                        "cust_status"       => $result[0]->custStatus
                    );

                    if ($result[0]->custStatus == VER_PENDING) {
                        $resVal[0]['cust_ver_code'] = $result[0]->ver_code;
                    }

                    if ($cartinfo != '') {
                        foreach ($cartinfo as $prod) {
                            $cartArr[] = array(
                                'prod_id'            => $prod->prod_id,
                                "order_id"           => $prod->order_id,
                                "prod_name"          => $prod->prod_name,
                                "prod_price"         => $prod->price,
                                "prod_value"         => $prod->value,
                                "prod_unit"          => $prod->unit,
                                "prod_no_of_packing" => $prod->no_of_packing,
                                "prod_iconurl"       => $prod->image
                            );
                        }

                        $resVal[0]['tomorrow_cart_info'] = $cartArr;
                    }
                    $resVal[0]['billInfo'] = $this->Api->cust_bill_detail($result[0]->cust_id); //use for get bill detail.

                    $resVal[0]['contactInfo'] = $this->Api->getcontact_info(); // use for get contact information.             

                    return array("response" => array("status" => 1, "response" => $resVal));
                }
            }
        } else {
            $message = INVALID_DATA;
            return array("response" => array("status" => 0, "message" => $message));
        }
    }

    function logout()
    {
        $cust_id = $this->input->post('cust_id');

        $this->db->set('device_id', 'NULL');
        $this->db->where('cust_id', $cust_id);
        $this->db->update('customers');
        return array("response" => array("status" => 1, "response" => array()));
    }


    /**
     * Insert punch details
     */
    function insertPunch($params = NULL)
    {
        if ($params != NULL) {
            $query = $this->db->insert('employee_punch_details', $params);
            if ($this->db->insert_id()) {
                return $this->db->insert_id();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Update punch details
     */
    function updatePunch($params = NULL, $id = NULL)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('employee_punch_details', $params);

        if ($query != 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Payment module
     */
    function addPayment($params = NULL)
    {
        if ($params != NULL) {
            $query = $this->db->insert('payments', $params);
            if ($this->db->insert_id()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function paymentList($type = NULL)
    {
        $pmtImage = PAYMENTS_IMAGE;
        $this->db->select('PMT.*, CONCAT("' . $pmtImage . 'thumb/",PMT.photo) as pmtThumbImg, CONCAT("' . $pmtImage . 'large/",PMT.photo) as pmtLargeImg');
        $this->db->FROM('payments PMT');
        if ($type != NULL) {
            $this->db->WHERE($type);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $paymentRes = $query->result();
            $query->free_result();
            foreach ($paymentRes as $key => $value) {
                $this->db->select('*');
                $this->db->FROM('dealers');
                $this->db->WHERE('id', $value->dealer_id);
                $queryRes = $this->db->get();

                if ($queryRes->num_rows() > 0) {
                    $dealerData = $queryRes->result();
                    $res = $dealerData[0];
                    $res->contact_person_name = $res->dealer_name;
                    $res->contact_number = $res->dealer_phone;
                    $res->contact_person_aadhar = $res->dealer_aadhar;
                    unset($res->dealer_name);
                    unset($res->dealer_phone);
                    unset($res->dealer_aadhar);
                    $paymentRes[$key]->party = $dealerData[0];
                } else {
                    $paymentRes[$key]->party = (object)array();
                }
                $queryRes->free_result();
            }
            return $paymentRes;
        } else {
            //return (object)array();
            return array();
        }
    }

    function addExpense($params = NULL)
    {
        if ($params != NULL) {
            $query = $this->db->insert('expenses', $params);
            if ($this->db->insert_id()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function getcontact_info()
    {
        $contact = $this->Api->getDataval('value', 'key', 'contact_number', 'settings');
        $email = $this->Api->getDataval('value', 'key', 'contact_email', 'settings');

        $res['mobile_number'] = $contact;
        $res['email_id'] = $email;

        return $res;
    }

    function recursiveRemoval(&$array, $pid, $subscribe, $order, $code)
    {

        if (is_array($array)) {
            foreach ($array as $key => &$arrayElement) {
                if (is_array($arrayElement)) {
                    // recursive function call
                    $this->Api->recursiveRemoval($arrayElement, $pid, $subscribe, $order, $code);
                } else {
                    // check for parent_id,subscriber,order and code found in subcategory.
                    if ($key == $pid || $key == $subscribe || $key == $order || $key == $code) {
                        // unset value from subcategory.
                        unset($array[$key]);
                    }
                }
            }
        }
        return $array;
    }

    function get_root_parent_id($catid)
    {
        //get parent category value.
        $this->db->select('parent_id,cat_name,cat_id,color_code');
        $this->db->from('categories');
        $this->db->where('cat_id', $catid);
        $query = $this->db->get();
        $parent = $query->result();
        if ($query->num_rows() > 0) {
            if ($query->row()->parent_id == 0) {
                return $parent;  //return parent category data.
            } else {
                return $this->Api->get_root_parent_id($query->row()->parent_id); //if category is not parent then call fucntion again.
            }
        }
    }

    function changePassword()
    {
        $custid = $this->input->post('cust_id');
        $newpass = md5($this->input->post('new_password'));

        //update customer password.
        $this->db->set('pass_changed', 0);
        $this->db->set('password', $newpass);
        $this->db->where('cust_id', $custid);
        $this->db->update('customers');

        return array("response" => array("status" => 1, "response" => (object) null));
    }

    function getLastAttendance($id = NULL)
    {
        $this->db->SELECT('*');
        $this->db->FROM('employee_punch_details');
        $this->db->WHERE('user_id', $id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit('1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    function updateEmployee($reqData = NULL, $id = NULL)
    {
        $this->db->where('id', $id);
        $query = $this->db->update('users', $reqData);
        //echo $this->db->last_query();
        if ($query != 0) {
            return true;
        } else {
            return false;
        }
    }

    function getSiteSettingAPI($setting_name)
    {
        $this->db->SELECT('setting_value');
        $this->db->FROM('site_setting');
        $this->db->WHERE('setting_name', $setting_name);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return  $result[0]->setting_value;
        } else {
            return array();
        }
    }

    function getDealerCategories()
    {
        $this->db->FROM('dealer_categories');
        /// $this->db->WHERE('setting_name', $setting_name);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $result = $query->result();
        } else {
            return array();
        }
    }

    function getDealerType()
    {
        $this->db->FROM('dealer_types');
        /// $this->db->WHERE('setting_name', $setting_name);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $result = $query->result();
        } else {
            return array();
        }
    }

    function getBranchParty($dealer_id = NULL)
    {
        $this->db->SELECT('*');
        $this->db->FROM('dealer_branches');
        if ($dealer_id != NULL) {
            $this->db->WHERE('dealer_id', $dealer_id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getBranchProducts($dealer_id = NULL, $branch_id = NULL)
    {
        $this->db->SELECT('*');
        $this->db->FROM('dealer_branch_products');
        if ($dealer_id != NULL) {
            $this->db->WHERE('dealer_id', $dealer_id);
        }
        if ($branch_id != NULL) {
            $this->db->WHERE('branch_id', $branch_id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }



    function updatePartyBranch($data = NULL, $where = NULL)
    {
        if ($data != NULL && $where != NULL) {
            $this->db->where($where);
            $query = $this->db->update('dealer_branches', $data);
            if ($query != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updateBranchProduct($data = NULL, $where = NULL)
    {
        if ($data != NULL && $where != NULL) {
            $this->db->where($where);
            $query = $this->db->update('dealer_branch_products', $data);
            if ($query != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function getSubUser($user_id)
    {
        $data = $this->db->query("CALL get_all_subadmin($user_id)");
        $result = $data->row_array();
        $this->db->close();
        if ($result['all_subadmin'] != NULL) {
            return $result['all_subadmin'];
        } else {
            return null;
        }
    }

    function getTodaysLiveLocations($user_id, $uType)
    {
        $res = $this->getSubUser($user_id);
        $sql = "SELECT CONCAT(`USR`.`first_name`,' ', `USR`.`last_name`) as name, `ePunch`.`current`, `ePunch`.`user_id` as id FROM employee_punch_details as ePunch LEFT JOIN users USR ON  `ePunch`.`user_id` = `USR`.`id` WHERE `ePunch`.`punch_in_date` = '" . date('Y-m-d') . "' AND `ePunch`.`punch_out_date` IS NULL ";

        $ids = ($uType == 'Super Admin' || $user_id == 6) ? array($user_id, 1) : array($user_id, 1, 2);
        $uType = ($user_id == 6) ? "Super Admin" : "";
        if ($res != NULL && $uType !== 'Super Admin') {
            $sql .= "AND USR.id IN (" . $res . ")";
        } else {
            if ($uType !== 'Super Admin') {
                $sql .= "AND  USR.parent_id IN (" . $user_id . ")";
            }
        }
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $queryRes = $query->result();
            $newResponse = array();
            foreach ($queryRes as $key => $value) {
                $current = json_decode($value->current);
                $cord = $current->location->coordinates;

                $dateVal = $this->getLocalDateTime($current->date);
                //echo $dt->format('d/m/Y h:i:s A');
                $newResponse[] = array('name' => $value->name, 'id' =>  str_replace('/', '_', rtrim(base64_encode($value->id), '=')), 'dateTime' => $dateVal, 'lat' => $cord[0], 'long' => $cord[1]);
            }
            return $newResponse;
        } else {
            return [];
        }
    }


    function getLiveUserRoute($id, $date = null)
    {
        $this->db->SELECT('lat,lng,created_at as time');
        $this->db->FROM('employee_punch_logs');
        $this->db->WHERE('employee_punch_details_id', $id);
        $this->db->order_by('created_at', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return [];
        }
    }

    function getLiveUserRoutePunchDetails($id)
    {
        $this->db->SELECT('punch_in,punch_out');
        $this->db->FROM('employee_punch_details');
        $this->db->WHERE('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result();

            $punch_in = json_decode($data[0]->punch_in);
            if (!empty(($data[0]->punch_out))) {
                $punch_out = json_decode($data[0]->punch_out);
            } else {
                $punch_out = (object) array('location' => array('coordinates' => array(null, null)),  'time' => '');
            }
            $returnData = array(
                'start' => array(
                    'lat' => $punch_in->location->coordinates[0],
                    'lng' => $punch_in->location->coordinates[1],
                    'time' => $punch_in->date
                ),
                'end' => array(
                    'lat' => $punch_out->location->coordinates[0],
                    'lng' => $punch_out->location->coordinates[1],
                    'time' => $punch_out->date
                )
            );
            return $returnData;
        } else {
            return [];
        }
    }

    public function getLiveUserRoute_get($id)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser);
        }
        $locations = $this->Api->getLiveUserRoute(base64_decode($id));
        $punchtimedata = $this->Api->getLiveUserRoutePunchDetails(base64_decode($id));
        $locations = json_decode(json_encode($locations), true);
        array_unshift($locations, $punchtimedata['start']);
        if ($punchtimedata['end']['lat']) {
            array_push($locations, $punchtimedata['end']);
        }
        $this->response(array(
            "status" => 1,
            "results" => $locations,
        ));
    }

    function employeedetails($userid = NULL)
    {
        $this->db->SELECT('USR.*, UT.type as userType, CONCAT(PRNT.first_name," ",PRNT.last_name) as parent_name');
        $this->db->FROM('users USR');
        $this->db->JOIN('users PRNT', 'USR.parent_id = PRNT.id', 'LEFT');
        $this->db->JOIN('user_types UT', 'USR.user_type = UT.id', 'LEFT');
        //$this->db->where('USR.user_type', 4);
        $this->db->where('USR.id', $userid);
        $this->db->group_by('USR.id');
        $this->db->order_by('USR.first_name');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function employeeattendance($userid = NULL)
    {
        $this->db->select('*');
        $this->db->where('user_id', $userid);
        $this->db->order_by('punch_in_date', 'DESC');
        $query = $this->db->get('employee_punch_details');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getLiveUserRouteDetails($id, $date = null)
    {
        $this->db->SELECT('lat,lng,created_at as time');
        $this->db->FROM('employee_punch_logs');
        $this->db->WHERE('employee_punch_details_id', $id);
        $this->db->order_by('created_at', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getLiveUserRoutePunchDetails_New($id)
    {
        $this->db->SELECT('punch_in,punch_out');
        $this->db->FROM('employee_punch_details');
        $this->db->WHERE('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result();

            $punch_in = json_decode($data[0]->punch_in);
            if (!empty(($data[0]->punch_out))) {
                $punch_out = json_decode($data[0]->punch_out);
            } else {
                $punch_out = (object) array('location' => array('coordinates' => array(null, null)), 'time' => '');
            }
            $returnData = array(
                'start' => array(
                    'lat' => $punch_in->location->coordinates[0],
                    'lng' => $punch_in->location->coordinates[1],
                    'time' => $punch_in->date
                ),
                'end' => array(
                    'lat' => $punch_out->location->coordinates[0],
                    'lng' => $punch_out->location->coordinates[1],
                    'time' => $punch_out->date
                )
            );
            return $returnData;
        } else {
            return false;
        }
    }

    /**
     * Insert SMS contents
     */
    function import_sms_contents($data = array())
    {
        $insertData = array();
        $data = json_decode($data);
        foreach ($data as $key => $value) {
            $insertData[] = array('sender_mobile_number' => $value->mobile, 'sms_content' => $value->sms_content);
        }

        if (count($insertData) > 0) {
            $this->db->insert_batch('mobile_sms_contents', $insertData);
            return true;
        } else {
            return false;
        }
    }


    function get_notification($user_Id)
    {
        $this->db->SELECT('*');
        $this->db->FROM('notifications');
        $this->db->WHERE('user_id', $user_Id);
        $this->db->order_by('created_at', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_notification_by_id($user_Id, $notification_id)
    {
        $this->db->SELECT('*');
        $this->db->FROM('notifications');
        $this->db->WHERE('user_id', $user_Id);
        $this->db->WHERE('id', $notification_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getAppUpdateDetails()
    {
        $this->db->SELECT('IF(update_required = 0, "false", "true") as update_required, latest_version_code', true);
        $this->db->FROM('app_version');
        $this->db->limit(1);
        $this->db->order_by('created_at', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getLastLocationlatlog($user_id, $punch_id)
    {
        $this->db->SELECT('lat,lng', true);
        $this->db->FROM('employee_punch_logs');
        $this->db->WHERE('user_id', $user_id);
        $this->db->WHERE('employee_punch_details_id', $punch_id);
        $this->db->limit(1);
        //$this->db->order_by('created_at', 'desc');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function gettotalKmoflogDay($user_id, $punch_id)
    {
        $this->db->SELECT('SUM(distance) as distance', true);
        $this->db->FROM('employee_punch_logs');
        $this->db->WHERE('user_id', $user_id);
        $this->db->WHERE('employee_punch_details_id', $punch_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function getPoProduct($user_id)
    {
        $this->db->SELECT('*');
        $this->db->FROM('dealers');
        if ($user_id != NULL) {
            $this->db->WHERE('employee_id', $user_id);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $parties =  $query->result();
            $product = [];
            foreach ($parties as $party) {
                $this->db->SELECT('products.*, dealer_product.dealer_price');
                $this->db->FROM('products');
                $this->db->join('dealer_product', 'dealer_product.product_id = products.id', 'left');
                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                    //                    echo "<pre>";print_r($result->result());exit;
                    return $result->result();
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    function getPoDetails($id)
    {
        $this->db->SELECT('products_order.*, dealers.firm_name, dealers.address, dealers.city_or_town, group_concat(products.name) as product_name, group_concat(products.item_code) as item_code, group_concat(products_order_product.nos) as nos, group_concat(products_order_product.rate) as rate');
        // $this->db->SELECT('products_order.*, dealers.firm_name, dealers.address, dealers.city_or_town, group_concat(products_order_product.rate) as rate');
        $this->db->FROM('products_order');
        $this->db->join('dealers', 'dealers.id = products_order.dealer_id', 'left');
        $this->db->join('products_order_product', 'products_order_product.product_order_id = products_order.id');
        $this->db->join('products', 'products_order_product.product_id = products.id');
        $this->db->group_by('products_order_product.product_order_id');
        $this->db->WHERE('products_order.id', $id);
        // return $this->db->get_compiled_select();
        $result = $this->db->get();
        if (!empty($result)) {
            return $result->result();
        } else {
            return false;
        }
    }

    function getComplainType()
    {
        $this->db->SELECT('*');
        $this->db->FROM('complain_type');
        $result = $this->db->get();
        if (!empty($result)) {
            return $result->result();
        } else {
            return false;
        }
    }

    function getFollowUpType()
    {
        $this->db->SELECT('*');
        $this->db->FROM('follow_up_type');
        $result = $this->db->get();
        if (!empty($result)) {
            return $result->result();
        } else {
            return false;
        }
    }

    function getNextComplainNumber()
    {
        $query = $this->db->select('id')->FROM('complains')->LIMIT(1)->ORDER_BY('id', 'DESC')->get()->result();
        //        return  $query;
        if (!empty($query)) {

            return (string)date('Ym') . (string)$query[0]->id;
        } else {
            return (string)date('Ym') . '0';
        }
    }

    function getNextFollowUpNumber()
    {
        $query = $this->db->select('id')->FROM('follow_up')->LIMIT(1)->ORDER_BY('id', 'DESC')->get()->result();
        //        return  $query;
        if (!empty($query)) {

            return (string)date('Ym') . (string)$query[0]->id;
        } else {
            return (string)date('Ym') . '0';
        }
    }

    function getNextInquiryNumber()
    {
        $query = $this->db->select('id')->FROM('enquiries')->LIMIT(1)->ORDER_BY('id', 'DESC')->get()->result();
        //        return  $query;
        if (!empty($query)) {

            return (string)date('Ym') . (string)$query[0]->id;
        } else {
            return (string)date('Ym') . '0';
        }
    }

    function getNextPoNumber()
    {
        $query = $this->db->select('id')->FROM('products_order')->LIMIT(1)->ORDER_BY('id', 'DESC')->get()->result();
        if (!empty($query)) {

            return (string)date('Ym') . (string)$query[0]->id;
        } else {
            return (string)date('Ym') . '0';
        }
    }

    function getPartiesByCategory($id)
    {
        $this->db->select('dealer_categories.id, dealer_categories.name');
        $this->db->from('dealer_categories');
        $this->db->join('dealers', 'dealers.dealer_category = dealer_categories.id', 'left');
        $this->db->where('dealers.employee_id', $id);
        $this->db->group_by('dealer_categories.id');
        $query = $this->db->get();
        if (!empty($query)) {
            $query = $query->result();
            // print_r($query);exit();
            $response = [];
            foreach ($query as $party) {
                $data['id'] = $party->id;
                $data['name'] = $party->name;
                $this->db->select('dealers.id, coalesce(dealers.dealer_name, dealer_owners.owner_name) as name');
                $this->db->from('dealers');
                $this->db->join('dealer_owners', 'dealer_owners.dealer_id = dealers.id', 'left');
                $this->db->where('dealer_category', $party->id);
                $model = $this->db->get();
                if (!empty($model)) {
                    $data['parties'] = $model->result();
                }
                $response[] = $data;
            }
            return  $response;
        } else {
            return false;
        }
    }

    function getProductsByCategory()
    {
        $this->db->select('product_categories.id, product_categories.name');
        $this->db->from('product_categories');
        $query = $this->db->get();
        if (!empty($query)) {
            $query = $query->result();
            $response = [];
            foreach ($query as $party) {
                $data['id'] = $party->id;
                $data['name'] = $party->name;
                $this->db->select('products.id, products.name as name');
                $this->db->from('products');
                $this->db->where('category_id', $party->id);
                $model = $this->db->get();
                if (!empty($model)) {
                    $data['products'] = $model->result();
                }
                $response[] = $data;
            }
            return  $response;
        } else {
            return false;
        }
    }

    function getPoList($id)
    {
        $this->db->SELECT('products_order.*,CONCAT(users.first_name," ", users.last_name) as employee_name, dealer_owners.owner_name, dealer_owners.phone_no, dealers.dealer_name, dealers.address, dealers.gst_number, dealers.dealer_phone, dealers.firm_name');
        $this->db->FROM('products_order');
        $this->db->join('dealers', 'dealers.id = products_order.dealer_id', 'left');
        $this->db->join('dealer_owners', 'dealer_owners.dealer_id = dealers.id', 'left');
        $this->db->join('users', 'users.id = products_order.employee_id', 'left');
        $this->db->where('products_order.employee_id', $id);
        $this->db->order_by('created_at', 'DESC');

        $result = $this->db->get();
        // print_r($this->db->last_query());exit;
        if (!empty($result)) {
            $query =  $result->result();
            $response = [];
            foreach ($query as $po) {
                $data['id'] = $po->id;
                $data['po_number'] = $po->po_number;
                $data['dispatch_date'] = $po->dispatch_date;
                $data['employee_name'] = $po->employee_name;
                // $data['dealer_name'] = (!empty($po->dealer_name)) ? $po->dealer_name : $po->owner_name;
                $data['dealer_name'] = $po->firm_name;
                $data['address'] = $po->address;
                $data['gst_number'] = $po->gst_number;
                $data['dealer_phone'] = (!empty($po->dealer_phone)) ? $po->dealer_phone : $po->phone_no;
                $data['total_weight'] = '12312321';
                $data['total_nos'] = '12321321';
                $data['total_rate'] = '123123';
                $pdf_url = base_url('assets/admin/pdf/po_invoice/' . $po->po_number . '.pdf');
                $data['pdf_link'] = (stripos(get_headers($pdf_url)[0], '200 OK')) ? $pdf_url : '';
                // $data['pdf_link'] = PO_INVOICE.$po->po_number.'.pdf';
                $this->db->select('*');
                $this->db->from('products_order_product');
                $this->db->join('products', 'products.id = products_order_product.product_id', 'left');
                $this->db->where('product_order_id', $po->id);
                $model = $this->db->get();
                if (!empty($model)) {
                    $data['products'] = $model->result();
                    $total_weight = $total_nos = $total_rate = 0;
                    foreach ($data['products'] as $product) {
                        $total_weight = $total_weight + (float)$product->weight;
                        $total_nos = $total_nos + (float)$product->nos;
                        $total_rate = $total_rate + (float)$product->rate;
                    }
                    $data['total_weight'] = (string)$total_weight;
                    $data['total_nos'] = (string)$total_nos;
                    $data['total_rate'] = (string)$total_rate;
                    $data['dealer_name'] = ($data['dealer_name'] == null) ? 'Dealer deleted' : $data['dealer_name'];
                    $data['address'] = ($data['address'] == null) ? 'Dealer deleted' : $data['address'];
                    $data['gst_number'] = ($data['gst_number'] == null) ? 'Dealer deleted' : $data['gst_number'];
                    $data['dealer_phone'] = ($data['dealer_phone'] == null) ? 'Dealer deleted' : $data['dealer_phone'];
                }
                $response[] = $data;
            }
            return  $response;
        } else {
            return false;
        }
    }

    function getData($employeeId)
    {

        //        $this->db->SELECT('*');
        //
        //        $this->db->FROM('products_order_product POP');
        //
        //        $this->db->join('products_order PO','POP.product_order_id = PO.id');
        //        $this->db->join('products P','POP.product_id = P.id');
        //        $this->db->join('users U',' U.id = PO.employee_id');
        //
        //        $this->db->where('PO.employee_id', 2);
        //        return $this->db->get();
    }

    function getComplainList($id)
    {
        $this->db->select('complains.id, 
        complains.complain_no, 
        users.emp_id,users.first_name ,
         users.last_name,
         COALESCE(
            (SELECT dealer_name FROM dealers WHERE id = complains.party_id),
            party_name
        ) AS party_name, 
         complain_type.name as complain_type,
        
          complains.Date as date,
          complains.remark, statuses.name as status_name,complains.closed_by, complains.admin_remark,complains.status_id');
        $this->db->from('complains');
        $this->db->join('users', 'users.id = complains.user_id');
        $this->db->join('statuses', 'statuses.id = complains.status_id');
        $this->db->join('complain_type', 'complain_type.id = complains.complain_type_id');
        $this->db->join('dealers', 'dealers.id = complains.party_id', 'left');
        $this->db->where('users.id', $id);

        $this->db->order_by("complains.id", "desc");
        return  $this->db->get()->result_array();
    }
}
