<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


class Apis extends REST_Controller
{
    public $authUser;

    function __construct()
    {
        // Construct our parent class
        parent::__construct();
        $this->load->model('Api');
        //$this->output->enable_profiler(FALSE);
        $this->authUser = $this->authVerify();
    }

    function authVerify()
    {
        $header = $this->input->request_headers();
        // pr($header,1);
        if (!isset($header['Authorization'])) {
            // print_r($header['Authorization']);exit;
            return array("status" => 0, "message" => "Unauthorized request");
        } else {
            $auth = $header['Authorization'];
            $auth = explode('_', $auth);
            // print_r(count($auth));exit;
            if (count($auth) == 1) {
                return array("status" => 0, "message" => "Unauthorized request");
            } else {
                //$userDetais = $this->Api->getUserByType(4,$auth[1]);
                $userDetais = $this->Api->getUserByTypeandAuth(4, $auth[1], $header['Authorization']);
                if ($userDetais) {
                    return $userDetais[0];
                } else {
                    return array("status" => 0, "message" => "Invalid user");
                }
            }
        }
    }

    public function setUploadConfig($imageName, $path)
    {
        $this->createDir($path . 'thumb'); //Create thumb dir if not exist
        $this->createDir($path . 'large'); //Create large dir if not exist
        $config['upload_path'] = $path . 'large';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '204800';
        $config['file_ext_tolower'] = TRUE;
        $config['remove_spaces'] = TRUE;
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

        if (!$this->image_lib->resize()) {
            return array('status' => 0, 'message' => $this->image_lib->display_errors());
        } else {
            $this->image_lib->clear();
            return array('status' => 1, 'message' => $newFileName);
        }
    }

    function login_post()
    {
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('device_model', 'Device Model', 'trim|required|strip_tags|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Invalid phone", //implode(',',$this->form_validation->error_array())
                "user" => (object)[]
            ));
        } else {
            $validateUser = $this->Api->validate_user();
            $response = array();
            if ($validateUser) {
                if ($validateUser[0]->is_deleted == 0) {
                    $token = bin2hex(random_bytes(74));
                    $validateUser[0]->authorization = $token . "_" . $validateUser[0]->id;
                    $this->db->set('authorization', $token . "_" . $validateUser[0]->id);
                    if (isset($_POST['fcm_token'])) {
                        $this->db->set('fcm_token', $this->input->post('fcm_token'));
                    }
                    $this->db->where('id', $validateUser[0]->id);
                    $this->db->update('users');
                    $validateUser[0]->fcm_token = $this->input->post('fcm_token');
                    //$validateUser[0]->authorization = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImFuaWxtc2FpbmlAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpY2F0aW9uIjp0cnVlLCJzdGF0dXMiOiJvZmZsaW5lIiwidHlwZSI6ImVtcGxveWVlIiwiaXNfZGVsZXRlZCI6ZmFsc2UsImNyZWF0ZWRfYXQiOiIyMDIwLTAxLTEwVDE2OjQwOjU0LjMxN1oiLCJfaWQiOiI1ZTRhY2IwZmI2NjE3ODIxODViNDYxZjEiLCJmaXJzdF9uYW1lIjoiVGVzdCIsImxhc3RfbmFtZSI6IkFuaWwgU2FpbmkiLCJlbXBsb3llZV9pZCI6MTIzNDU2LCJwaG9uZSI6Ijg2OTA0MTU2NzUiLCJkZXZpY2VfaWQiOiJmYjk2MTMxMzQ4MDk4NmRkIiwicHJvZmlsZSI6eyJ0aHVtYiI6IiIsImxhcmdlIjoiIn0sImlhdCI6MTU4MjA0NTg5NSwiZXhwIjoxNjEzNTgxODk1fQ_" . $validateUser[0]->id;
                    $validateUser[0]->profile_image = EMPLOYEE_IMAGE . "large/" . $validateUser[0]->profile_image;
                    $response = array('status' => 1, 'message' => 'Login successfully', 'user' => $validateUser[0]);
                } else {
                    $response = array('status' => 0, 'message' => 'Account is deleted by admin.');
                }
            } else {
                $response = array('status' => 0, 'message' => 'Invalid device OR user');
            }
            $this->response($response);
        }
    }

    function uploadPunchImage($key = 'meter_reading_photo', $dir = PUNCH_IMAGE_DIR, $thumbWidth = PUNCH_THUMB_IMAGE_WIDTH, $thumbHeight = PUNCH_THUMB_IMAGE_HEIGHT)
    {
        $errors = '';
        if (isset($_FILES[$key]['name'])) {
            $files = $_FILES;
            $product_image = array();
            $imageName = strtotime(date('Y-m-d H:i:s'));
            $this->setUploadConfig($imageName, $dir);

            if ($this->upload->do_upload($key)) {
                $resultData = $this->upload->data();
                // initialize full configuration then upload
                /* $large_img = $this->setImageConfigParameter($resultData, PUNCH_IMAGE_WIDTH, PUNCH_IMAGE_HEIGHT, $imageName, PUNCH_IMAGE_DIR.'large'); */


                // initialize thumb configuration then upload
                $thumb_image = $this->setImageConfigParameter($resultData, $thumbWidth, $thumbHeight, $imageName, $dir . 'thumb');
                return $thumb_image;
            } else {
                return array('status' => 0, 'message' => $this->upload->display_errors());
            }
        } else {
            return array('status' => 0, 'message' => array('Please select file to upload'));
        }
    }

    /**
     * Punch In feature
     */
    // function punch_in_post()
    // {
    //     if (gettype($this->authUser) == 'array') {
    //         $this->response($this->authUser, 401);
    //     }
    //     $this->form_validation->set_rules('lat', 'Latitude ', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('lng', 'Logitude', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('place', 'Place', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('meter_reading_in_km', 'Meter reading', 'trim|required|strip_tags|xss_clean');

    //     if ($this->form_validation->run() == FALSE) {
    //         $this->response(array(
    //             "status" => 0,
    //             "message" => "Missing parameters",//implode(',',$this->form_validation->error_array())
    //         ));
    //     } else {
    //         $result = $this->Api->checkDataExist('id', array('user_id' => $this->authUser->id, 'punch_in_date' => date('Y-m-d')), 'employee_punch_details');

    //         if (!$result) { //if duplicate records not exist then will execute if flow
    //             $uploadResponse = $this->uploadPunchImage();
    //             $PostData = $this->input->post();
    //             if ($uploadResponse['status'] == '1') {
    //                 $PostData['meter_reading_photo'] = $uploadResponse['message'];
    //             } else {
    //                 $this->response($uploadResponse);
    //             }

    //             $punchInVal = array("location" => array("type" => "Point", "coordinates" => array($PostData['lat'], $PostData['lng'])), "meter_reading_photo" => $PostData['meter_reading_photo'], "date" => date('Y-m-d H:i:s'), "place" => $PostData['place'], "meter_reading_in_km" => $PostData['meter_reading_in_km']);

    //             $punchInVal = json_encode($punchInVal);
    //             $punchIndata = array('user_id' => $this->authUser->id, 'punch_in' => $punchInVal, 'punch_in_date' => date('Y-m-d'), 'current' => $punchInVal, 'vehicle_type' => $PostData['vehicle_type']);

    //             $insertData = $this->Api->insertPunch($punchIndata); //insert punch data
    //             /*Map Ping Interval*/
    //             $map_ping_interval = $this->Api->getSiteSettingAPI("ping_interval");
    //             /*END Map Ping Interval */
    //             if ($insertData) {
    //                 $this->response(["status" => 1, "message" => "You have started a job successfully", "result" => array('attendance_id' => $insertData, "user_id" => $this->authUser->id, "ping_interval" => $map_ping_interval)]);
    //             } else {
    //                 $this->response(["status" => 0, "message" => "Error in punch in, please try again."]);
    //             }
    //         } else {
    //             $this->response(["status" => 0, "message" => "Sorry! You have already punched in for a day"]);
    //         }
    //     }
    // }

    /**
     * Punch Out feature
     */
    // function punch_out_post()
    // {
    //     if (gettype($this->authUser) == 'array') {
    //         $this->response($this->authUser, 401);
    //     }
    //     $this->form_validation->set_rules('lat', 'Latitude ', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('lng', 'Logitude', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('place', 'Place', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('tour_details', 'Tour Details', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('partner_name', 'Partner Name', 'trim|required|strip_tags|xss_clean');
    //     $this->form_validation->set_rules('meter_reading_in_km', 'Meter reading', 'trim|required|strip_tags|xss_clean');

    //     if ($this->form_validation->run() == FALSE) {
    //         $this->response(array(
    //             "status" => 0,
    //             "message" => "Missing parameters",//implode(',',$this->form_validation->error_array())
    //         ));
    //     } else {
    //         $result = $this->Api->checkDataExist('id', array('user_id' => $this->authUser->id, 'punch_out_date' => NULL), 'employee_punch_details');
    //         //pr($result,1);
    //         if ($result) { //if records exist then will execute if flow
    //             $PostData = $this->input->post();
    //             if (!is_null($result[0]->punch_out)) {
    //                 $this->response(["status" => 0, "message" => "Sorry!, You are already done the punch out process for the day."]);
    //             } else {
    //                 $punch_id = $result[0]->id;
    //                 $punch_in = json_decode($result[0]->punch_in);
    //                 $totalKm = max($PostData['meter_reading_in_km'] - $punch_in->meter_reading_in_km, 0);
    //                 $uploadResponse = $this->uploadPunchImage();
    //                 if ($uploadResponse['status'] == '1') {
    //                     $PostData['meter_reading_photo'] = $uploadResponse['message'];
    //                 } else {
    //                     $this->response($uploadResponse);
    //                 }

    //                 /*Get Total Distanc one Point to Second Point*/

    //                 $total_distanceCount = $this->Api->gettotalKmoflogDay($this->authUser->id, $punch_id);
    //                 $total_distance = 0;
    //                 if (isset($total_distanceCount[0])) {
    //                     $total_distance = $total_distanceCount[0]->distance;
    //                 }


    //                 $punchOutVal = array("location" => array("type" => "Point", "coordinates" => array($PostData['lat'], $PostData['lng'])), "meter_reading_photo" => $PostData['meter_reading_photo'], "date" => date('Y-m-d H:i:s'), "place" => $PostData['place'], "meter_reading_in_km" => $PostData['meter_reading_in_km']);

    //                 $punchOutVal = json_encode($punchOutVal);
    //                 $punchOutdata = array('punch_out' => $punchOutVal, 'punch_out_date' => date('Y-m-d'), 'current' => $punchOutVal, 'tour_details' => $PostData['tour_details'], 'partner_name' => $PostData['partner_name'], 'traveled_km' => $totalKm, 'total_distance' => $total_distance);
    //                 //$this->authUser->id
    //                 $updateData = $this->Api->updatePunch($punchOutdata, $punch_id); //insert punch data

    //                 if ($updateData) {
    //                     $this->response(["status" => 1, "message" => "You have done a job successfully"]);
    //                 } else {
    //                     $this->response(["status" => 0, "message" => "Error in punch out, please try again."]);
    //                 }
    //             }
    //         } else {
    //             $this->response(["status" => 0, "message" => "Sorry! You have not punched in for a day"]);
    //         }
    //     }
    // }

    //new Updated methods 

    function upload_punch_image_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $uploadResponse = $this->uploadPunchImage();

        $PostData = $this->input->post();
        $punch_id = $PostData['id'];
        $result = $this->Api->checkDataExist('id', array('user_id' => $this->authUser->id, 'punch_out_date' => NULL), 'employee_punch_details', NULL, 'punch_in');
        $json = json_decode($result[0]->punch_in, true);
        //    $json['meter_reading_photo'] = '131212311131321111.jpg';
        if ($uploadResponse['status'] == '1') {
            $json['meter_reading_photo'] = $uploadResponse['message'];
        } else {
            $this->response($uploadResponse);
        }
        $punchInVal = array("location" => array("type" => $json['location']['type'], "coordinates" => array($json['location']['coordinates'][0], $json['location']['coordinates'][1])), "meter_reading_photo" => $json['meter_reading_photo'], "date" => $json['date'], "place" => $json['place'], "meter_reading_in_km" => $json['meter_reading_in_km']);
        $punchInVal = json_encode($punchInVal);
        $punchIndata = array('punch_in' => $punchInVal);
        $updateData = $this->Api->updatePunch($punchIndata, $punch_id); //insert punch data
        if ($updateData) {
            $this->response(["status" => 1, "message" => "Image uploaded successfully"]);
        } else {
            $this->response(["status" => 0, "message" => "Error in image upload, please try again."]);
        }
    }

    function upload_punch_out_image_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $uploadResponse = $this->uploadPunchImage();

        $PostData = $this->input->post();
        $punch_id = $PostData['id'];

        $result = $this->Api->checkDataExist('id', array('user_id' => $this->authUser->id, 'id' => $punch_id), 'employee_punch_details');
        if ($result[0]->punch_out !== null) {
            $json = json_decode($result[0]->punch_out, true);
            if ($uploadResponse['status'] == '1') {
                $json['meter_reading_photo'] = $uploadResponse['message'];
            } else {
                $this->response($uploadResponse);
            }
            $punchOutVal = array("location" => array("type" => $json['location']['type'], "coordinates" => array($json['location']['coordinates'][0], $json['location']['coordinates'][1])), "meter_reading_photo" => $json['meter_reading_photo'], "date" => $json['date'], "place" => $json['place'], "meter_reading_in_km" => $json['meter_reading_in_km']);
            $punchOutVal = json_encode($punchOutVal);
            $punchOutdata = array('punch_out' => $punchOutVal);
            $updateData = $this->Api->updatePunch($punchOutdata, $punch_id); //insert punch data
            if ($updateData) {
                $this->response(["status" => 1, "message" => "Image uploaded successfully"]);
            } else {
                $this->response(["status" => 0, "message" => "Error in image upload, please try again."]);
            }
        } else {
            $this->response(["status" => 0, "message" => "Please Punch Out First."]);
        }
    }


    /**
     * Punch In feature
     */
    function punch_in_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $this->form_validation->set_rules('lat', 'Latitude ', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('lng', 'Logitude', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('place', 'Place', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'trim|required|strip_tags|xss_clean');
        if ($this->input->post('vehicle_type') != 'Other') {
            $this->form_validation->set_rules('meter_reading_in_km', 'Meter reading', 'trim|required|strip_tags|xss_clean');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters" . implode(',', $this->form_validation->error_array()),
            ));
        } else {
            $result = $this->Api->checkDataExist('id', array('user_id' => $this->authUser->id, 'punch_in_date' => date('Y-m-d')), 'employee_punch_details');

            if (!$result) { //if duplicate records not exist then will execute if flow
                $PostData = $this->input->post();

                if (isset($_FILES['meter_reading_photo'])) {
                    $uploadResponse = $this->uploadPunchImage();
                    if ($uploadResponse['status'] == '1') {
                        $PostData['meter_reading_photo'] = $uploadResponse['message'];
                    } else {
                        $this->response($uploadResponse);
                    }
                } else {
                    $PostData['meter_reading_photo'] = '';
                }


                $punchInVal = array("location" => array("type" => "Point", "coordinates" => array($PostData['lat'], $PostData['lng'])), "meter_reading_photo" => $PostData['meter_reading_photo'],  "date" => date('Y-m-d H:i:s'), "place" => $PostData['place'], "meter_reading_in_km" => $PostData['meter_reading_in_km']);

                $punchInVal = json_encode($punchInVal);
                $punchIndata = array('user_id' => $this->authUser->id, 'punch_in' => $punchInVal, 'punch_in_date' => date('Y-m-d'), 'current' => $punchInVal, 'vehicle_type' => $PostData['vehicle_type']);

                $insertData = $this->Api->insertPunch($punchIndata); //insert punch data
                /*Map Ping Interval*/
                $map_ping_interval = $this->Api->getSiteSettingAPI("ping_interval");
                /*END Map Ping Interval */
                if ($insertData) {
                    $this->response(["status" => 1, "message" => "You have started a job successfully", "result" => array('attendance_id' => $insertData, "user_id" => $this->authUser->id, "ping_interval" => $map_ping_interval)]);
                } else {
                    $this->response(["status" => 0, "message" => "Error in punch in, please try again."]);
                }
            } else {
                $this->response(["status" => 0, "message" => "Sorry! You have already punched in for a day"]);
            }
        }
    }

    /**
     * Punch Out feature
     */
    function punch_out_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $this->form_validation->set_rules('lat', 'Latitude ', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('lng', 'Logitude', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('place', 'Place', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('tour_details', 'Tour Details', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('partner_name', 'Partner Name', 'trim|required|strip_tags|xss_clean');
        if ($this->input->post('vehicle_type') != 'Other') {
            $this->form_validation->set_rules('meter_reading_in_km', 'Meter reading', 'trim|required|strip_tags|xss_clean');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters" . implode(',', $this->form_validation->error_array()),
            ));
        } else {
            $result = $this->Api->checkDataExist('id', array('user_id' => $this->authUser->id, 'punch_out_date' => NULL), 'employee_punch_details');

            if ($result) { //if records exist then will execute if flow
                $PostData = $this->input->post();
                if (!is_null($result[0]->punch_out)) {
                    $this->response(["status" => 0, "message" => "Sorry!, You are already done the punch out process for the day."]);
                } else {
                    $punch_id = $result[0]->id;
                    $punch_in = json_decode($result[0]->punch_in);
                    $totalKm = max($PostData['meter_reading_in_km'] - $punch_in->meter_reading_in_km, 0);
                    if (isset($_FILES['meter_reading_photo'])) {
                        $uploadResponse = $this->uploadPunchImage();
                        if ($uploadResponse['status'] == '1') {
                            $PostData['meter_reading_photo'] = $uploadResponse['message'];
                        } else {
                            $this->response($uploadResponse);
                        }
                    } else {
                        $PostData['meter_reading_photo'] = '';
                    }

                    /*Get Total Distanc one Point to Second Point*/

                    $total_distanceCount = $this->Api->gettotalKmoflogDay($this->authUser->id, $punch_id);
                    $total_distance = 0;
                    if (isset($total_distanceCount[0])) {
                        $total_distance = $total_distanceCount[0]->distance;
                    }


                    $punchOutVal = array("location" => array("type" => "Point", "coordinates" => array($PostData['lat'], $PostData['lng'])), "meter_reading_photo" => $PostData['meter_reading_photo'],  "date" => date('Y-m-d H:i:s'), "place" => $PostData['place'], "meter_reading_in_km" => $PostData['meter_reading_in_km']);

                    $punchOutVal = json_encode($punchOutVal);
                    $punchOutdata = array('punch_out' => $punchOutVal, 'punch_out_date' => date('Y-m-d'), 'current' => $punchOutVal, 'tour_details' => $PostData['tour_details'], 'partner_name' => $PostData['partner_name'], 'traveled_km' => $totalKm, 'total_distance' => $total_distance);
                    //$this->authUser->id
                    $map_ping_interval = $this->Api->getSiteSettingAPI("ping_interval");
                    $updateData = $this->Api->updatePunch($punchOutdata, $punch_id); //insert punch data

                    if ($updateData) {
                        //  pr($result,1);
                        $this->response(["status" => 1, "message" => "You have done a job successfully", "result" => array('attendance_id' => $punch_id, "user_id" => $this->authUser->id, "ping_interval" => $map_ping_interval)]);
                    } else {
                        $this->response(["status" => 0, "message" => "Error in punch out, please try again."]);
                    }
                }
            } else {
                $this->response(["status" => 0, "message" => "Sorry! You have not punched in for a day"]);
            }
        }
    }

    /**
     * Party module started
     */
    function party_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $parties = $this->Api->getPartiesNew($this->authUser->id);
        $parties = ($parties) ? $parties : array();
        $this->response(array(
            "status" => 1,
            "results" => array("parties" => $parties), //implode(',',$this->form_validation->error_array())
        ));
    }

    /**
     * Insert function to add party
     */
    function party_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        // $this->form_validation->set_rules('firm_name', 'Firm Name', 'trim|required|strip_tags|xss_clean');
        // // $this->form_validation->set_rules('contact_person_name', 'Contact Person Name', 'trim|required|strip_tags|xss_clean');
        // // $this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|required|strip_tags|xss_clean');
        // $this->form_validation->set_rules('address', 'Address', 'trim|required|strip_tags|xss_clean');
        // $this->form_validation->set_rules('city_or_town', 'City/Town', 'trim|required|strip_tags|xss_clean');
        // //$this->form_validation->set_rules('gst_number', 'GST Number', 'trim|required|strip_tags|xss_clean'); //hide 05-07-2020

        // if ($this->form_validation->run() == FALSE) {
        if (FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters", //implode(',',$this->form_validation->error_array())
            ));
        } else {
            $PostData = file_get_contents('php://input');
            $PostData = json_decode($PostData);
            // pr($PostData,1);exit;
            // $Posts['dealer_name'] = $PostData->contact_person_name;
            // $Posts['dealer_phone'] = $PostData->contact_number;
            // $Posts['dealer_dob'] = $PostData->contact_person_dob;
            // $Posts['dealer_doa'] = $PostData->contact_person_doa;
            $Post['firm_name'] = $PostData->firm_name;
            $Post['dealer_category'] = $PostData->dealer_category;
            $Post['address'] = $PostData->address;
            $Post['city_or_town'] = $PostData->city_or_town;
            $Post['gst_number'] = $PostData->gst_number;
            // $Post['dealer_aadhar'] = $PostData->contact_person_aadhar;
            $Post['dealer_aadhar'] = ($PostData->contact_person_aadhar) ? $PostData->contact_person_aadhar : NULL;
            $Post['employee_id'] = $this->authUser->id;

            // pr($Post,1);exit;
            // unset($PostData['contact_person_name']); //unset data
            // unset($PostData['contact_number']);//unset data
            // unset($PostData['contact_person_aadhar']);//unset data
            // print_r($Post);exit;

            $response = $this->Api->addParty_New($Post);

            if ($response) {
                // $i = 0;
                // print_r($Post);exit;
                for ($i = 0; $i < count($PostData->contact_details); $i++) {
                    $data = array();
                    $data['dealer_id'] = $response;
                    $data['owner_name'] = $PostData->contact_details[$i]->name;
                    $data['phone_no'] = $PostData->contact_details[$i]->number;
                    $data['dob'] = (!empty($PostData->contact_details[$i]->dob)) ? date('Y-m-d', strtotime(strtr($PostData->contact_details[$i]->dob, '/', '-'))) : null;
                    // $data['dob'] = null;
                    $data['doa'] = ($PostData->contact_details[$i]->doa != '') ? date('Y-m-d', strtotime(strtr($PostData->contact_details[$i]->doa, '/', '-'))) : null;
                    $result =  $this->Api->addPartyOwner($data);
                }
                $this->response(array(
                    "status" => 1,
                    "message" => "Party added successfully."
                ));
            } else {
                $this->response(array(
                    "status" => 0,
                    "results" => 'Party not added, please try again'
                ));
            }
        }
    }

    /**
     * Update function to update party
     */
    function party_put($id = NULL)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        // $PostData = file_get_contents('php://input');
        // $PostData = json_decode($PostData);
        $PostData = file_get_contents('php://input');
        $PostData = json_decode($PostData);
        // print_r($PostData);exit;
        // $Posts['dealer_name'] = $PostData->contact_person_name;
        // $Posts['dealer_phone'] = $PostData->contact_number;
        // $Posts['dealer_dob'] = $PostData->contact_person_dob;
        // $Posts['dealer_doa'] = $PostData->contact_person_doa;
        $Post['firm_name'] = $PostData->firm_name;
        $Post['dealer_category'] = $PostData->dealer_category;
        $Post['address'] = $PostData->address;
        $Post['city_or_town'] = $PostData->city_or_town;
        $Post['gst_number'] = $PostData->gst_number;
        // $Post['dealer_aadhar'] = $PostData->contact_person_aadhar;
        $Post['dealer_aadhar'] = ($PostData->contact_person_aadhar) ? $PostData->contact_person_aadhar : NULL;
        $Post['employee_id'] = $this->authUser->id;


        // print_r($Post);exit;
        $response = $this->Api->updateParty($Post, array('id' => $id, 'employee_id' => $this->authUser->id));
        if ($response) {

            // if (isset($Posts['dealer_name'])) {
            $response = $this->Api->deletePartyOwners($id);
            for ($i = 0; $i < count($PostData->contact_details); $i++) {
                $data = array();
                $data['dealer_id'] = $id;
                $data['owner_name'] = $PostData->contact_details[$i]->name;
                $data['phone_no'] = $PostData->contact_details[$i]->number;
                $data['dob'] = (!empty($PostData->contact_details[$i]->dob)) ? date('Y-m-d', strtotime(strtr($PostData->contact_details[$i]->dob, '/', '-'))) : null;
                // $data['dob'] = null;
                $data['doa'] = ($PostData->contact_details[$i]->doa != '') ? date('Y-m-d', strtotime(strtr($PostData->contact_details[$i]->doa, '/', '-'))) : null;
                $result =  $this->Api->addPartyOwner($data);
            }
            $this->response(array(
                "status" => 1,
                "message" => "Party updated successfully."
            ));
            // } else {
            // }
        } else {
            $this->response(array(
                "status" => 0,
                "results" => 'Party not updated, please try again'
            ));
        }
    }



    /**
     * Insert function to add party
     */
    // function party_new_post()
    // {
    //     if (gettype($this->authUser) == 'array') {
    //         $this->response($this->authUser, 401);
    //     }


    //     $PostData = $this->input->post();
    //     $add_dealer_Data['firm_name'] = $PostData['firm_name'];
    //     $add_dealer_Data['dealer_category'] = $PostData['firm_category'];
    //     $add_dealer_Data['dealer_type'] = $PostData['firm_type'];
    //     $add_dealer_Data['address'] = $PostData['firm_address'];
    //     $add_dealer_Data['city_or_town'] = $PostData['firm_city_or_town'];
    //     $add_dealer_Data['gst_number'] = $PostData['firm_gst_number'];

    //     $add_dealer_Data['dealer_name'] = $PostData['firm_contact_person_name'];
    //     $add_dealer_Data['dealer_phone'] = $PostData['firm_contact_number'];
    //     // $add_dealer_Data['dealer_aadhar'] = ($PostData['contact_person_aadhar'])? $PostData['contact_person_aadhar'] : NULL;
    //     $add_dealer_Data['employee_id'] = $this->authUser->id;
    //     $response_last_id = $this->Api->addParty_New($add_dealer_Data);
    //     if ($response_last_id) {
    //         // foreach ($PostData['branch_name'] as $key => $value) {
    //         //         // $dealer_branches[] = $value adddealer_branches
    //         //         $dealer_branches = array();
    //         //         $dealer_branches['dealer_id'] = $response_last_id;
    //         //         $dealer_branches['name'] = $value['name'];
    //         //         $dealer_branches['contact_number'] = $value['contact_number'];
    //         //         $dealer_branches['address'] = $value['address'];
    //         //         $dealer_branches['city_or_town'] = $value['city_or_town'];
    //         //         $dealer_branches['gst_number'] = $value['gst_number'];
    //         //         $dealer_branches['contact_person_aadhar'] = $value['contact_person_aadhar'];
    //         //         $dealer_branches['is_whatapp'] = $value['is_whatapp'];
    //         //         $branch_last_id = $this->Api->adddealer_branches($dealer_branches);
    //         //         foreach ($value['product'] as $key_new => $value_new) {
    //         //             $value_new['dealer_id'] = $response_last_id;
    //         //             $value_new['branch_id'] = $branch_last_id;
    //         //             $product_last_id = $this->Api->adddealer_branches_product($value_new);
    //         //         }
    //         //         //pr($value);
    //         // }
    //         $this->response(array(
    //             "status" => 1,
    //             "message" => "Party added successfully."
    //         ));
    //     } else {
    //         $this->response(array(
    //             "status" => 0,
    //             "results" => 'Party not added, please try again'
    //         ));
    //     }

    //     // pr($_POST);
    //     // exit;
    //     // $this->form_validation->set_rules('firm_name', 'Firm Name', 'trim|required|strip_tags|xss_clean');
    //     // $this->form_validation->set_rules('contact_person_name', 'Contact Person Name', 'trim|required|strip_tags|xss_clean'); 
    //     // $this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|required|strip_tags|xss_clean'); 
    //     // $this->form_validation->set_rules('address', 'Address', 'trim|required|strip_tags|xss_clean'); 
    //     // $this->form_validation->set_rules('city_or_town', 'City/Town', 'trim|required|strip_tags|xss_clean'); 
    //     // //$this->form_validation->set_rules('gst_number', 'GST Number', 'trim|required|strip_tags|xss_clean'); //hide 05-07-2020

    //     // if($this->form_validation->run() == FALSE) {
    //     //     $this->response(array(
    //     //         "status" => 0,
    //     //         "message" => "Missing parameters",//implode(',',$this->form_validation->error_array())
    //     //     ));
    //     // }else{
    //     //     $PostData = $this->input->post();
    //     //     $PostData['dealer_name'] = $PostData['contact_person_name'];
    //     //     $PostData['dealer_phone'] = $PostData['contact_number'];
    //     //     $PostData['dealer_aadhar'] = ($PostData['contact_person_aadhar'])? $PostData['contact_person_aadhar'] : NULL;
    //     //     $PostData['employee_id'] = $this->authUser->id;

    //     //     unset($PostData['contact_person_name']); //unset data
    //     //     unset($PostData['contact_number']);//unset data
    //     //     unset($PostData['contact_person_aadhar']);//unset data

    //     //     $response = $this->Api->addParty($PostData);

    //     //     if($response){
    //     //         $this->response(array(
    //     //             "status" => 1,
    //     //             "message" => "Party added successfully."
    //     //         ));
    //     //     }else{
    //     //         $this->response(array(
    //     //             "status" => 0,
    //     //             "results" => 'Party not added, please try again'
    //     //         ));
    //     //     }
    //     // }
    // }

    /**
     * Insert function to add party branch
     */
    function add_branch_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        $PostData = $this->input->post();
        $dealer_branches = array();
        $dealer_branches['dealer_id'] = $PostData['dealer_id'];
        $dealer_branches['name'] = $PostData['name'];
        $dealer_branches['contact_number'] = $PostData['contact_number'];
        $dealer_branches['address'] = $PostData['address'];
        $dealer_branches['city_or_town'] = $PostData['city_or_town'];
        $dealer_branches['gst_number'] = $PostData['gst_number'];
        $dealer_branches['contact_person_aadhar'] = $PostData['contact_person_aadhar'];
        $dealer_branches['is_whatapp'] = $PostData['is_whatapp'];
        $dealer_branches['lat'] = $PostData['lat'];
        $dealer_branches['lng'] = $PostData['lng'];
        $branch_last_id = $this->Api->adddealer_branches($dealer_branches);
        if ($branch_last_id) {
            $this->response(array(
                "status" => 1,
                "message" => "Party Branch added successfully."
            ));
        } else {
            $this->response(array(
                "status" => 0,
                "results" => 'Party Branch not added, please try again'
            ));
        }
    }

    /**
     * Update function to add party branch
     */
    function update_branch_post($id = NULL)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        $PostData = $this->input->post(); //$this->put();
        $response = $this->Api->updatePartyBranch($PostData, array('branch_id' => $id));

        if ($response) {
            $this->response(array(
                "status" => 1,
                "message" => "Branch updated successfully."
            ));
        } else {
            $this->response(array(
                "status" => 0,
                "results" => 'Branch not updated, please try again'
            ));
        }
    }

    /**
     * Update function to add party branch
     */
    function update_branch_put($id = NULL)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        $PostData = $this->put();
        $response = $this->Api->updatePartyBranch($PostData, array('branch_id' => $id));

        if ($response) {
            $this->response(array(
                "status" => 1,
                "message" => "Branch updated successfully."
            ));
        } else {
            $this->response(array(
                "status" => 0,
                "results" => 'Branch not updated, please try again'
            ));
        }
    }

    /**
     * Insert function to add party branch
     */
    function add_branch_product_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $PostData = $this->input->post();
        $uploadResponse = $this->uploadPunchImage('image', DEALER_IMAGE_PRODUCT);

        if ($uploadResponse['status'] == '1') {
            $PostData['image'] = $uploadResponse['message'];
        } else {
            $this->response($uploadResponse);
        }
        $product_last_id = $this->Api->adddealer_branches_product($PostData);

        if ($product_last_id) {
            $this->response(array(
                "status" => 1,
                "message" => "Product added successfully."
            ));
        } else {
            $this->response(array(
                "status" => 0,
                "results" => 'Product not added, please try again'
            ));
        }
    }

    /**
     * Update function to add party branch
     */
    function update_branch_product_post($id = NULL)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        $PostData = $this->input->post(); //$this->put();        
        if (isset($_FILES) && !empty($_FILES)) {
            $uploadResponse = $this->uploadPunchImage('image', DEALER_IMAGE_PRODUCT);
            if ($uploadResponse['status'] == '1') {
                $PostData['image'] = $uploadResponse['message'];
            } else {
                $this->response($uploadResponse);
            }
        } else {
            unset($PostData['image']);
        }

        $response = $this->Api->updateBranchProduct($PostData, array('product_id' => $id));
        if ($response) {
            $this->response(array(
                "status" => 1,
                "message" => "Product updated successfully."
            ));
        } else {
            $this->response(array(
                "status" => 0,
                "results" => 'Product not updated, please try again'
            ));
        }
    }

    /**
     * Update function to add party branch
     */
    function update_branch_product_put($id = NULL)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        $PostData = $this->put();
        if (isset($_FILES) && !empty($_FILES)) {
            $uploadResponse = $this->uploadPunchImage('image', DEALER_IMAGE_PRODUCT);
            if ($uploadResponse['status'] == '1') {
                $PostData['image'] = $uploadResponse['message'];
            } else {
                $this->response($uploadResponse);
            }
        } else {
            unset($PostData['image']);
        }

        $response = $this->Api->updateBranchProduct($PostData, array('product_id' => $id));
        if ($response) {
            $this->response(array(
                "status" => 1,
                "message" => "Product updated successfully."
            ));
        } else {
            $this->response(array(
                "status" => 0,
                "results" => 'Product not updated, please try again'
            ));
        }
    }


    /**
     * Get Party Branch module started
     */
    function party_branch_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $part_id = $_GET['`dealer`_id'];

        $partiesBranch = $this->Api->getBranchParty($part_id);
        $partiesBranch = ($partiesBranch) ? $partiesBranch : array();
        $this->response(array(
            "status" => 1,
            "results" => array("partiesBranch" => $partiesBranch), //implode(',',$this->form_validation->error_array())
        ));
    }

    /**
     * Get Party Branch module started
     */
    function party_branch_products_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $part_id = $_GET['dealer_id'];
        $branch_id = $_GET['branch_id'];
        $partiesBranchproducts = $this->Api->getBranchProducts($part_id, $branch_id);
        $partiesBranchproducts = ($partiesBranchproducts) ? $partiesBranchproducts : array();
        $this->response(array(
            "status" => 1,
            "results" => array("partiesBranchproducts" => $partiesBranchproducts), //implode(',',$this->form_validation->error_array())
        ));
    }


    function payment_collections_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $this->form_validation->set_rules('party', 'Party ID ', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('amount_type', 'Amount Type', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('collection_of', 'Collection Of', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('payment_details', 'Payment Details', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('extra', 'Extra', 'trim|required|strip_tags|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters", //implode(',',$this->form_validation->error_array())
            ));
        } else {
            $PostData = $this->input->post();
            $uploadResponse = $this->uploadPunchImage('meter_reading_photo', PAYMENTS_IMAGE_DIR, PUNCH_THUMB_IMAGE_WIDTH, PUNCH_THUMB_IMAGE_HEIGHT);

            if ($uploadResponse['status'] == '1') {
                $PostData['photo'] = $uploadResponse['message'];
            } else {
                $this->response($uploadResponse);
            }

            $paymentData = array('employee_id' => $this->authUser->id, 'photo' => $PostData['photo'], 'dealer_id' => $PostData['party'], 'amount' => $PostData['amount'], 'payment_method' => str_replace('"', '', $PostData['amount_type']), 'collection_of' => $PostData['collection_of'], 'payment_details' => $PostData['payment_details'], 'extra' => $PostData['extra']);

            if (isset($PostData['cheque_detail'])) {
                $paymentData['cheque_detail'] = $PostData['cheque_detail'];
            }

            $insertData = $this->Api->addPayment($paymentData); //insert punch data

            if ($insertData) {
                $this->response(["status" => 1, "message" => "Payment added successfully"]);
            } else {
                $this->response(["status" => 0, "message" => "Error in payment add, please try again."]);
            }
        }
    }

    function payment_collections_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $para = $this->input->get();
        if (isset($para['status'])) {
            $para['employee_id'] = $this->authUser->id;
            $getPaymenst = $this->Api->paymentList($para);
            $this->response(array('status' => 1, "results" => array("payments" => $getPaymenst)));
        } else {
            $this->response(array('status' => 0, 'message' => 'Invalid parameter'));
        }
    }

    function expense_categories_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $getCatList = $this->Api->getCatList();
        $this->response(array('status' => 1, "result" => array('categories' => $getCatList)));
    }

    function expenses_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $empId = $this->authUser->id;
        $catId = (isset($_GET['categoryId'])) ? $_GET['categoryId'] : NULL;
        $statusVal = (isset($_GET['expenseStatus'])) ? $_GET['expenseStatus'] : NULL;
        $startDate = (isset($_GET['startDate'])) ? str_replace("/", "-", $_GET['startDate']) : "";
        $endDate = (isset($_GET['endDate'])) ? str_replace("/", "-", $_GET['endDate']) : "";

        $expenses = $this->Api->getExpenseList(NULL, $empId, $catId, $statusVal, $startDate, $endDate);
        if ($expenses) {
            $this->response(array('status' => 1, 'result' => array('expenses' => $expenses)));
        } else {
            $this->response(array('status' => 1, 'result' => array('expenses' => array())));
        }
    }

    function expenses_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $this->form_validation->set_rules('city_id', 'City ', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('category_id', 'Category', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('requested_amount', 'Requested Amount', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('expense_details', 'Expense Details', 'trim|required|strip_tags|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters", //implode(',',$this->form_validation->error_array())
            ));
        } else {
            $PostData = $this->input->post();
            if (isset($PostData['city_name'])) {
                $cityName = $PostData['city_name'];

                unset($PostData['city_name']);
            }

            $uploadResponse = $this->uploadPunchImage('expense_photo', EXPENSES_IMAGE_DIR, PUNCH_THUMB_IMAGE_WIDTH, PUNCH_THUMB_IMAGE_HEIGHT);

            if ($uploadResponse['status'] == '1') {
                $PostData['expense_photo'] = $uploadResponse['message'];
            } else {
                $this->response($uploadResponse);
            }
            $PostData['employee_id'] = $this->authUser->id;
            $PostData['city_name'] = $PostData['city_id'];
            unset($PostData['city_id']);
            $insertData = $this->Api->addExpense($PostData); //insert punch data

            if ($insertData) {
                $this->response(["status" => 1, "message" => "Expense added successfully"]);
            } else {
                $this->response(["status" => 0, "message" => "Error in expense add, please try again."]);
            }
        }
    }

    function city_grade_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $cityGrades = $this->Api->getList();
        $this->response(["status" => 1, "result" => array('city_list' => $cityGrades)]);
    }

    function employee_visits_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $this->form_validation->set_rules('party_id', 'Party ', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('visited_at', 'Visited At', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('discuss_duration', 'Discuss Duration', 'trim|required|strip_tags|xss_clean');
        $this->form_validation->set_rules('discuss_point', 'Discuss Point', 'trim|required|strip_tags|xss_clean');
        //$this->form_validation->set_rules('remark', 'Remark', 'trim|required|strip_tags|xss_clean'); //hide 05-07-2020

        if ($this->form_validation->run() == FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters", //implode(',',$this->form_validation->error_array())
            ));
        } else {
            $PostData = $this->input->post();
            $PostData['employee_id'] = $this->authUser->id;
            unset($PostData['visited_at']);
            // $PostData['visited_at'] = date('Y-m-d', strtotime($PostData['visited_at']));
            $visitResult = $this->Api->addVisit($PostData);

            if ($visitResult) {
                $this->response(["status" => 1, "message" => "Employee visit added successfully"]);
            } else {
                $this->response(["status" => 0, "message" => "Error in visit add, please try again."]);
            }
        }
    }

    function employee_visits_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        $result = $this->Api->getVisits($this->authUser->id);
        if ($result) {
            $this->response(["status" => 1, "result" => array('visits' => $result)]);
        } else {
            $this->response(["status" => 0, "result" => array('visits' => array())]);
        }
    }

    function employee_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $result = $this->Api->getUserById($this->authUser->id);
        if ($result) {
            if (!is_null($result[0]->profile_image)) {
                $result[0]->profile_image = EMPLOYEE_IMAGE . "large/" . $result[0]->profile_image;
            }
            $this->response(["status" => 1, "result" => $result[0]]);
        } else {
            $this->response(["status" => 0, "message" => "Not able to get user profile."]);
        }
    }

    function update_profile_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $email = $this->input->post('email');
        //Set Rule for Validation
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('email','email','trim|required|xss_clean|is_unique[users.email]');
        // $this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean|callback_checkemailedit[email]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean|edit_unique[users.phone.' . $this->authUser->id . ']');

        if ($this->form_validation->run() === FALSE) {
            $this->response(array(
                "status" => 0,
                // "message" => "Missing parameters OR email should be unique",
                "message" => $this->form_validation->error_array(),
            ));
        } else {

            $reqPara = array();
            $reqPara['first_name'] = $this->input->post('first_name');
            $reqPara['last_name'] = $this->input->post('last_name');
            // $reqPara['email'] = $email;//$this->input->post('email');
            $reqPara['address'] = $this->input->post('address');
            $reqPara['phone'] = $this->input->post('phone');

            if ($reqPara['first_name'] == '' || $reqPara['last_name'] == '') {
                $this->response(array(
                    "status" => 0,
                    "message" => "Missing parameters OR email should be unique"
                ));
                return;
            }
            $uploadResponse = $this->uploadPunchImage('profile_image', EMPLOYEE_IMAGE_DIR, PUNCH_THUMB_IMAGE_WIDTH, PUNCH_THUMB_IMAGE_HEIGHT);

            if ($uploadResponse['status'] == '1') {
                $reqPara['profile_image'] = $uploadResponse['message'];
            }

            $result = $this->Api->updateEmployee($reqPara, $this->authUser->id);
            if ($result) {
                $getUser = $this->Api->getUserById($this->authUser->id);
                if ($getUser) {
                    if (!is_null($getUser[0]->profile_image)) {
                        $getUser[0]->profile_image = EMPLOYEE_IMAGE . "large/" . $getUser[0]->profile_image;
                    }
                    $this->response(["status" => 1, "result" => $getUser[0], "message" => "Profile updated successfully."]);
                } else {
                    $this->response(["status" => 0, "message" => "Sorry, not able to update details."]);
                }
            } else {
                $this->response(["status" => 0, "message" => "Sorry, not able to update details."]);
            }
        }
    }

    function checkemailedit($val, $field)
    {
        $this->db->select('count(*) as duplicate');
        $this->db->from('users');
        $this->db->where_not_in('id', $this->authUser->id);
        if ($field == 'email') {
            $this->db->where('email', $this->input->post('email'));
        }
        $query = $this->db->get();
        $queryRes = $query->result();
        //$this->response($this->db->last_query());
        if ($queryRes[0]->duplicate > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function attendance_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $param = array('user_id' => $this->authUser->id);
        $getAttendance = $this->Api->getAttendance($param);
        $this->response(array('status' => 1, 'result' => array("attendance" => $getAttendance)));
    }

    function currency_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $currency = $this->Api->getCurrency();
        $this->response(["status" => 1, "result" => array('currency' => $currency)]);
    }

    function lastAttendance_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $empId = $this->authUser->id;
        $getAttendance = $this->Api->getLastAttendance($empId);
        $allow_punch_in = false;
        if (count($getAttendance) > 0) {
            $punchIn = json_decode($getAttendance[0]->punch_in);
            $punchInTime = $punchIn->date;

            $punchOut = (is_null($getAttendance[0]->punch_out)) ? NULL : json_decode($getAttendance[0]->punch_out);
            $punchOutTime = (is_null($punchOut)) ? '' : $punchOut->date;

            $date_now = new DateTime();
            $date2 = new DateTime($punchInTime);
            $date_now = $date_now->format('Y-m-d');
            $date2 = $date2->format('Y-m-d');
            //$this->response([$date_now, $date2 ]);
            // if ($date_now > $date2) {
            //     $this->response( ['greater than', $date_now, $date2]);
            // }else{
            //     $this->response( ['Less than', $date_now, $date2]);
            // }
            if (!empty($punchOutTime) && $date_now > $date2) {
                $allow_punch_in = true;
            }
            $this->response(array('status' => 1, 'attendance' => array(
                "user_id" => $empId, 'attendance_id' => $getAttendance[0]->id, "punch_in_time" => $punchInTime,
                "punch_out_time" => $punchOutTime, "offDayRequest" => false, "ping_interval" => $this->authUser->ping_interval, "allow_punch_in" => $allow_punch_in
            )));
        } else {
            $this->response(array('status' => 1, 'attendance' => array("user_id" => $empId, "offDayRequest" => false, "ping_interval" => $this->authUser->ping_interval, "allow_punch_in" => $allow_punch_in)));
        }
    }

    /**
     * Get Delaer categories module started
     */
    function dealercategories_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $dealercategories = $this->Api->getDealerCategories();
        $this->response(array(
            "status" => 1,
            "results" => array("dealercategories" => $dealercategories), //implode(',',$this->form_validation->error_array())
        ));
    }

    /**
     * Get Delaer Type module started
     */
    function dealertype_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $dealertype = $this->Api->getDealerType();
        $this->response(array(
            "status" => 1,
            "results" => array("dealertype" => $dealertype), //implode(',',$this->form_validation->error_array())
        ));
    }


    /**
     * Get PO module started
     */
    function po_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $parties = $this->Api->getParties($this->authUser->id);
        $product = $this->Api->getPoProduct($this->authUser->id);
        // print_r($parties);exit;
        $po = $this->Api->getNextPoNumber();
        $total_amount = "1000";
        $total_weight = "1111";
        $total_rate = "1222";
        $total_nos = "13333";
        //        $po = '100000000';
        // $fileUrl = date('YmdHi');
        $fileUrl = 'pdf_link';
        $product = ($product) ? $product : array();
        // if(count($product)>=1){
        //     array_walk($product, function($value,$key) {
        //       $product[$key]['unit_price'] = '111';
        //     });
        // }else{
        //     $product = array();
        // }
        $parties = ($parties) ? $parties : array();
        $this->response(array(
            "status" => 1,
            "results" => array(
                "count" => count($product),
                "po" => $po,
                "tatal_amount" => $total_amount,
                "total_weight" => $total_weight,
                "total_rate" => $total_rate,
                "total_nos" => $total_nos,
                "file" => $fileUrl,
                "products" => $product,
                "parties" => $parties,
            ), //implode(',',$this->form_validation->error_array())
        ));
    }

    function po_pdf($data)
    {
        // print_r($data);exit;
        $this->load->library('pdf');
        $this->pdf->load_view('Invoice', $data);
    }

    function dealerproduct_get()
    {
        $para = $this->input->get();
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $dealers = $this->Api->getPartyProductsList($para['dealer_id']);
        // $dealers = [
        //         [
        //         "id" => 5,
        //         "name" =>  "HIR ALPHA",
        //         "item_code" => "2022024",
        //         "category_id" =>  "7",
        //         "unit" => "1",
        //         "ah" => "20",
        //         "mrp" => "501",
        //         "created_at" => "2022-02-26 14:51:04",
        //         "dealer_price" => "225"
        //     ],
        //     [
        //         "id" => 6,
        //         "name" => "HIR BETA",
        //         "item_code" => "2022025",
        //         "category_id" => "7",
        //         "unit" => "1",
        //         "ah" => "20",
        //         "mrp" => "675",
        //         "created_at" => "2022-02-26 14:53:15",
        //         "dealer_price" => "305"
        //     ],
        //     [
        //         "id" =>  8,
        //         "name" => "HIR GAMMA GREY",
        //         "item_code" => "2022037",
        //         "category_id" => "7",
        //         "unit" => "1",
        //         "ah" => "20",
        //         "mrp" => "900",
        //         "created_at" => "2022-03-07 11:16:04",
        //         "dealer_price" => "100"
        //     ],
        //     ];
        $response = [
            'dealers' => $dealers
        ];
        $this->response(
            array(
                "status" => 1,
                "results" => $response, //implode(',',$this->form_validation->error_array())
            )
        );
    }

    function dealerprod_get()
    {
        $para = $this->input->get();
        // if (gettype($this->authUser) == 'array') {
        //     $this->response($this->authUser, 401);
        // }
        $dealers = $this->Api->getPartyProductsList($para['dealer_id']);
        $response = [
            'dealers' => $dealers
        ];
        $this->response(
            array(
                "status" => 1,
                "results" => $response, //implode(',',$this->form_validation->error_array())
            )
        );
    }



    function po_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = json_decode(json_encode($request), true);
        // print_r($data);exit;

        if ($data['dealer_id'] == null || $data['dealer_id'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Party is required',
            ));
        } elseif ($data['employee_id'] == null || $data['employee_id'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Employee is required',
            ));
        } elseif ($data['po'] == null || $data['po'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'PO is required',
            ));
        } elseif ($data['dispatch_date'] == null || $data['dispatch_date'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Dispatch Date is required',
            ));
        } elseif ($data['total'] == null || $data['total'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Total is required',
            ));
        } elseif ($data['products'] == null || $data['products'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Products is required',
            ));
        } else {
            $reqPara = array();
            $reqPara['dealer_id'] = $data['dealer_id'];
            $reqPara['employee_id'] = $data['employee_id'];
            $reqPara['po_number'] = $data['po'];
            $reqPara['dispatch_date'] = $data['dispatch_date'];
            $reqPara['total'] = $data['total'];

            $po = $this->db->insert('products_order', $reqPara);
            if ($po) {
                $po_id = $this->db->insert_id();
                foreach ($data['products'] as $product_order) {
                    $element = [];
                    $element['unit_price'] = $product_order['price'];
                    $element['product_order_id'] = $po_id;
                    $element['product_id'] = $product_order['product_id'];
                    $element['nos'] = $product_order['nos'];
                    $element['rate'] = $product_order['rate'];
                    $element['weight'] = $product_order['weight'];
                    $this->db->insert('products_order_product', $element);
                }

                $data = $this->Api->getPoDetails($po_id);
                $data = $this->po_pdf($data);
                // print_r($data);exit;

            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => '',
                ));
            }


            $this->response(array(
                "status" => 1,
                "message" => "po added successfully.",
            ));
        }
    }


    function uploadImageDelaer($branch_key = '0', $product_key = '0')
    {
        $errors = '';
        if (isset($_FILES)) {
            $files = $_FILES;
            $product_image = array();
            $imageName = strtotime(date('Y-m-d H:i:s'));
            $this->setUploadConfig($imageName, $dir);

            if ($this->upload->do_upload($key)) {
                $resultData = $this->upload->data();
                // initialize full configuration then upload
                /* $large_img = $this->setImageConfigParameter($resultData, PUNCH_IMAGE_WIDTH, PUNCH_IMAGE_HEIGHT, $imageName, PUNCH_IMAGE_DIR.'large'); */


                // initialize thumb configuration then upload
                $thumb_image = $this->setImageConfigParameter($resultData, $thumbWidth, $thumbHeight, $imageName, $dir . 'thumb');
                return $resultData;
            } else {
                return array('status' => 0, 'message' => $this->upload->display_errors());
            }
        } else {
            return array('status' => 0, 'message' => array('Please select file to upload'));
        }
    }

    public function getLiveAttandance_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $userType = ($this->authUser->user_type == 4) ? "Employee" : $this->authUser->user_type;
        $locations = $this->Api->getTodaysLiveLocations($this->authUser->id, $userType);
        $this->response(array(
            "status" => 1,
            "results" => $locations, //implode(',',$this->form_validation->error_array())
        ));
    }

    public function getLiveUserRoute_get($id)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
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

    /*Live Route view*/

    public function viewEmployeeDetails_get($id)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $data['user_details'] = $this->Api->employeedetails($id);
        $employeeattendance = $this->Api->employeeattendance($id);
        $data['user_attendance'] = array();
        foreach ($employeeattendance as $attendance) {
            array_push($data['user_attendance'], (object)[
                'id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'punch_in' => json_decode($attendance->punch_in),
                'punch_out' => json_decode($attendance->punch_out),
                'punch_in_date' => $attendance->punch_in_date,
                'punch_out_date' => $attendance->punch_out_date,
                'current' => json_decode($attendance->current),
                'vehicle_type' => $attendance->vehicle_type,
                'tour_details' => $attendance->tour_details,
                'partner_name' => $attendance->partner_name,
                'traveled_km' => $attendance->traveled_km,
                'updated_at' => $attendance->updated_at,
                'created_at' => $attendance->created_at,
            ]);
        }
        $this->response(array(
            "status" => 1,
            "data" => $data,
        ));
    }

    function getLiveUserRouteDetails_get($id, $date = null)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $locations = $this->Api->getLiveUserRouteDetails($id);
        $punchtimedata = $this->Api->getLiveUserRoutePunchDetails_New($id);
        $locations = json_decode(json_encode($locations), true);
        if (!$locations) {
            $locations = array();
        }
        array_unshift($locations, $punchtimedata['start']);
        array_push($locations, $punchtimedata['end']);
        $this->response(array(
            "status" => 1,
            "data" => $locations,
        ));
    }

    //New APis

    function sendLocation_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        //Set Rule for Validation
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lat', 'lat', 'trim|required|xss_clean');
        $this->form_validation->set_rules('long', 'long', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('isGpsOn', 'isGpsOn', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('battery', 'battery', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('mobile_network', 'mobile_network', 'trim|required|xss_clean');
        $this->form_validation->set_rules('employee_punch_id', 'employee_punch_id', 'trim|required|xss_clean');


        if ($this->form_validation->run() === FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters"
            ));
        } else {
            /**/

            $dataGetlastLocation = $this->Api->getLastLocationlatlog($this->input->post('user_id'), $this->input->post('employee_punch_id'));
            $distance = 0;
            if (isset($dataGetlastLocation[0])) {
                $dataGetlastLocation = $dataGetlastLocation[0];
                $distance = $this->distance($dataGetlastLocation->lat, $dataGetlastLocation->lng, $this->input->post('lat'), $this->input->post('long'), 'K');
                //$distance = $this->GetDistanceOpt($dataGetlastLocation->lat, $dataGetlastLocation->lng, $this->input->post('lat'), $this->input->post('long'));
            }

            $reqPara = array();
            $reqPara['user_id'] = $this->input->post('user_id');
            $reqPara['lat'] = $this->input->post('lat');
            $reqPara['lng'] = $this->input->post('long');
            $reqPara['isGpsOn'] = $this->input->post('isGpsOn');
            $reqPara['battery'] = $this->input->post('battery');
            $reqPara['mobile_network'] = $this->input->post('mobile_network');
            $reqPara['distance'] = $distance;

            $reqPara['employee_punch_details_id'] = $this->input->post('employee_punch_id');

            $query = $this->db->insert('employee_punch_logs', $reqPara);

            $this->response(array(
                "status" => 1,
                "data" => $this->db->insert_id(),
                "message" => "lat, long add successfully.",
            ));
        }
    }

    function sendBulkLocation_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        //        print_r($this->input->post());exit;
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = json_decode(json_encode($request), true);

        //$this->response(array("status" => $data));
        // //Set Rule for Validation
        // $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('lat', 'lat', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('long', 'long', 'trim|required|xss_clean');
        // // $this->form_validation->set_rules('isGpsOn', 'isGpsOn', 'trim|required|xss_clean');
        // // $this->form_validation->set_rules('battery', 'battery', 'trim|required|xss_clean');
        // // $this->form_validation->set_rules('mobile_network', 'mobile_network', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('employee_punch_id', 'employee_punch_id', 'trim|required|xss_clean');


        // if ($this->form_validation->run() === FALSE)
        // {
        //     $this->response(array(
        //         "status" => 0,
        //         "message" => "Missing parameters"
        //     ));
        // }else{
        foreach ($data as $key => $value) {
            $dataGetlastLocation = $this->Api->getLastLocationlatlog($this->input->post('user_id'), $this->input->post('employee_punch_id'));
            $distance = 0;
            if (isset($dataGetlastLocation[0])) {
                $dataGetlastLocation = $dataGetlastLocation[0];
                $distance = $this->distance($dataGetlastLocation->lat, $dataGetlastLocation->lng, $this->input->post('lat'), $this->input->post('long'), 'K');
                //$distance = $this->GetDistanceOpt($dataGetlastLocation->lat, $dataGetlastLocation->lng, $this->input->post('lat'), $this->input->post('long'));
            }

            $reqPara = array();
            $reqPara['user_id'] = $value['user_id'];
            $reqPara['lat'] = $value['lat'];
            $reqPara['lng'] = $value['long'];
            $reqPara['isGpsOn'] = $value['isGpsOn'];
            $reqPara['battery'] = $value['battery'];
            $reqPara['mobile_network'] = $value['mobile_network'];
            $reqPara['location'] = $value['location'];
            $reqPara['created_at'] = date('Y-m-d H:i:s', $value['timestamp']);;
            $reqPara['distance'] = $distance;
            $reqPara['employee_punch_details_id'] = $value['employee_punch_id'];

            $query = $this->db->insert('employee_punch_logs', $reqPara);
        }
        $this->response(array(
            "status" => 1,
            "data" => $this->db->insert_id(),
            "message" => "lat, long add successfully.",
        ));
        //     $reqPara = array();
        //     $reqPara['user_id'] = $this->input->post('user_id');
        //     $reqPara['lat'] = $this->input->post('lat');
        //     $reqPara['lng'] = $this->input->post('long');
        //     $reqPara['isGpsOn'] = $this->input->post('isGpsOn');
        //     $reqPara['battery'] = $this->input->post('battery');
        //     $reqPara['mobile_network'] = $this->input->post('mobile_network');

        //     $reqPara['employee_punch_details_id'] = $this->input->post('employee_punch_id');

        //     $query = $this->db->insert('employee_punch_logs', $reqPara);

        //     $this->response(array(
        //         "status" => 1,
        //         "data" => $this->db->insert_id(),
        //         "message" => "lat, long add successfully.",
        //     ));
        // }
    }

    function logout_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $this->db->set('authorization', '');
        $this->db->where('id', $this->authUser->id);
        $this->db->update('users');
        $this->response(array('status' => 1, 'message' => 'Employee logout successfully.'));
    }

    function sendFeedback_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        //Set Rule for Validation
        $this->form_validation->set_rules('subject', 'subject', 'trim|required|xss_clean');
        $this->form_validation->set_rules('feedback', 'feedback', 'trim|required|xss_clean');


        if ($this->form_validation->run() === FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters"
            ));
        } else {

            $reqPara = array();
            $reqPara['user_id'] = $this->authUser->id;
            $reqPara['subject'] = $this->input->post('subject');
            $reqPara['feedback'] = $this->input->post('feedback');

            $query = $this->db->insert('employee_feedback', $reqPara);

            $this->response(array(
                "status" => 1,
                "message" => "Feedback sent successfully.",
            ));
        }
    }

    public function importsms_post()
    {
        //Set Rule for Validation
        //$this->form_validation->set_rules('mobile', 'Mobile number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('content', 'SMS Content', 'trim|required|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            $this->response(array(
                "status" => 0,
                "message" => "Missing parameters"
            ));
        } else {
            $params = $this->input->post('content');
            $response = $this->Api->import_sms_contents($params);
            $this->response(array(
                "status" => 1,
                "message" => "SMS Inserted Successfully.",
            ));
        }
    }

    function complain_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $parties = $this->Api->getParties($this->authUser->id);
        $complain_type = ($this->Api->getComplainType()) ? $this->Api->getComplainType() : [];
        $no = $this->Api->getNextComplainNumber();
        $date = date('Y-m-d');
        //        $po = '100000000';
        $parties = ($parties) ? $parties : array();
        $this->response(array(
            "status" => 1,
            "results" => array("parties" => $parties, "no" => $no, "complain_type" => $complain_type, 'date' => $date,), //implode(',',$this->form_validation->error_array())
        ));
    }

    function complain_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = json_decode(json_encode($request), true);

        // made option to add party name for external party
        // if ($data['dealer_id'] == null || $data['dealer_id'] == '') {
        //     $this->response(array(
        //         "status" => 0,
        //         "message" => 'Party is required',
        //     ));
        // } else


        if ($data['complain_id'] == null || $data['complain_id'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Complain type is required',
            ));
        } elseif ($data['complain_number'] == null || $data['complain_number'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Number is required',
            ));
        } elseif ($data['date'] == null || $data['date'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Date is required',
            ));
        } elseif ($data['remark'] == null || $data['remark'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Remark is required',
            ));
        } else {
            $reqPara = array();
            $reqPara['party_id'] = (isset($data['dealer_id']) && $data['dealer_id'] != null && $data['dealer_id'] != '') ? $data['dealer_id'] : null;
            $reqPara['party_name'] = (isset($data['dealer_id']) && $data['dealer_id'] != null && $data['dealer_id'] != '') ? null : $data['dealer_name'];
            $reqPara['user_id'] = $this->authUser->id;
            $reqPara['complain_type_id'] = $data['complain_id'];
            $reqPara['complain_no'] = $data['complain_number'];
            $reqPara['Date'] = $data['date'];
            $reqPara['remark'] = $data['remark'];

            $po = $this->db->insert('complains', $reqPara);
            if (!$po) {
                $this->response(array(
                    "status" => 0,
                    "message" => 'complain not added, please try again later!',
                ));
            }

            $this->response(array(
                "status" => 1,
                "message" => "complain added successfully.",
            ));
        }
    }

    function followup_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $parties = $this->Api->getParties($this->authUser->id);
        $followup_type = ($this->Api->getFollowUpType()) ? $this->Api->getfollowUpType() : [];
        $no = $this->Api->getNextFollowUpNumber();
        $date = date('Y-m-d');
        //        $po = '100000000';
        $parties = ($parties) ? $parties : array();
        $this->response(array(
            "status" => 1,
            "results" => array("parties" => $parties, "no" => $no, "followup_type" => $followup_type, 'date' => $date,), //implode(',',$this->form_validation->error_array())
        ));
    }

    function followup_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = json_decode(json_encode($request), true);

        // inserted also external party
        // if ($data['dealer_id'] == null || $data['dealer_id'] == '') {
        //     $this->response(array(
        //         "status" => 0,
        //         "message" => 'Party is required',
        //     ));
        // } else
        if ($data['follow_up_id'] == null || $data['follow_up_id'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Follow up type is required',
            ));
        } elseif ($data['follow_up_number'] == null || $data['follow_up_number'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Number is required',
            ));
        } elseif ($data['date'] == null || $data['date'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Date is required',
            ));
        } elseif ($data['remark'] == null || $data['remark'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Remark is required',
            ));
        } elseif ($data['follow_up_date'] == null || $data['follow_up_date'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Follow up date is required',
            ));
        } else {
            $reqPara = array();
            $reqPara['party_id'] = (isset($data['dealer_id']) && $data['dealer_id'] != null && $data['dealer_id'] != '') ? $data['dealer_id'] : null;
            $reqPara['party_name'] = (isset($data['dealer_id']) && $data['dealer_id'] != null && $data['dealer_id'] != '') ? null : $data['dealer_name'];

            $reqPara['employee_id'] = $this->authUser->id;
            $reqPara['follow_up_type_id'] = $data['follow_up_id'];
            $reqPara['number'] = $data['follow_up_number'];
            $reqPara['submit_date'] = $data['date'];
            $reqPara['remark'] = $data['remark'];
            $reqPara['follow_up_date'] = $data['follow_up_date'];

            $po = $this->db->insert('follow_up', $reqPara);
            if (!$po) {
                $this->response(array(
                    "status" => 0,
                    "message" => 'follow up not added, please try again later!',
                ));
            }

            $this->response(array(
                "status" => 1,
                "message" => "follow up added successfully.",
            ));
        }
    }


    public function getNotifications_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $notification_list = $this->Api->get_notification($this->authUser->id);
        $result = array(
            "status" => 1,
            "data" => null,
            "message" => "Notification not found.",
        );
        if ($notification_list) {
            $result = array(
                "status" => 1,
                "data" => $notification_list,
                "message" => "Notification Found Successfully.",
            );
        }
        $this->response($result);
    }

    public function readNotification_get($id)
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $checkNotification = $this->Api->get_notification_by_id($this->authUser->id, $id);
        if ($checkNotification) {
            $this->db->set('read_status', 1);
            $this->db->where('user_id', $this->authUser->id);
            $this->db->where('id', $id);
            $updateData = $this->db->update('notifications');
            $this->response(array('status' => 1, 'message' => 'Read Notification successfully.'));
        }
        $this->response(array('status' => 1, 'message' => 'Notification not found.'));
    }

    function appUpdateInfo_get()
    {
        $app = $this->Api->getAppUpdateDetails();
        $app[0]->update_required = (bool)$app[0]->update_required;
        $result = array(
            "status" => 1,
            "data" => $app[0],
            'message' => "Version found successfully"
        );
        $this->response($result);
    }

    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    /*::                                                                         :*/
    /*::  This routine calculates the distance between two points (given the     :*/
    /*::  latitude/longitude of those points). It is being used to calculate     :*/
    /*::  the distance between two locations using GeoDataSource(TM) Products    :*/
    /*::                                                                         :*/
    /*::  Definitions:                                                           :*/
    /*::    South latitudes are negative, east longitudes are positive           :*/
    /*::                                                                         :*/
    /*::  Passed to function:                                                    :*/
    /*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
    /*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
    /*::    unit = the unit you desire for results                               :*/
    /*::           where: 'M' is statute miles (default)                         :*/
    /*::                  'K' is kilometers                                      :*/
    /*::                  'N' is nautical miles                                  :*/
    /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    /**
     * Optimized algorithm for Get Distance between two lat long
     *
     * @param float $latitudeFrom
     * @param float $longitudeFrom
     * @param float $latitudeTo
     * @param float $longitudeTo
     *
     * @return float [km]
     */
    function GetDistanceOpt($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $rad = M_PI / 180;
        //Calculate distance from latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin($latitudeFrom * $rad)
            * sin($latitudeTo * $rad) + cos($latitudeFrom * $rad)
            * cos($latitudeTo * $rad) * cos($theta * $rad);

        return acos($dist) / $rad * 60 * 1.853;
    }

    function enquiry_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $party_category = $this->Api->getPartiesByCategory($this->authUser->id);
        $product_category = $this->Api->getProductsByCategory();
        //        $product_cateogry = [
        //            [
        //            'id' => '1',
        //            'name' => 'test',
        //            'products' => [
        //                [
        //                    'id' => '1',
        //                    'name' => 'test 1 1'
        //                ],
        //                [
        //                    'id' => '2',
        //                    'name' => 'test 1 2'
        //                ]
        //            ]
        //
        //        ],
        //            [
        //                'id' => '2',
        //                'name' => 'test 2',
        //                'products' => [
        //                    [
        //                        'id' => '3',
        //                        'name' => 'test 2 1'
        //                    ],
        //                    [
        //                        'id' => '4',
        //                        'name' => 'test 2 2'
        //                    ]
        //                ]
        //
        //            ]];
        //        $no = $this->Api->getNextFollowUpNumber();
        $date = date('Y-m-d');
        $no = $this->Api->getNextInquiryNumber();
        //        $parties = ($parties) ? $parties : array();
        $this->response(array(
            "status" => 1,
            "results" => array("party_categories" => $party_category, "no" => $no, "product_category" => $product_category, 'date' => $date,), //implode(',',$this->form_validation->error_array())
        ));
    }

    function enquiry_post()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = json_decode(json_encode($request), true);

        // $data = $this->input->post();

        // pr($data,1);exit;

        // made party optional for external party
        // if ($data['party_id'] == null || $data['party_id'] == '') {
        //     $this->response(array(
        //         "status" => 0,
        //         "message" => 'Party is required',
        //     ));
        // } elseif ($data['party_category_id'] == null || $data['party_category_id'] == '') {
        //     $this->response(array(
        //         "status" => 0,
        //         "message" => 'Party type is required',
        //     ));
        // } else
        if ($data['enquiry_number'] == null || $data['enquiry_number'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Number is required',
            ));
        } elseif ($data['date'] == null || $data['date'] == '') {
            $this->response(array(
                "status" => 0,
                "message" => 'Date is required',
            ));
        } else {
            // pr($data,1);exit;
            $reqPara = array();
            $reqPara['party_id'] = (isset($data['dealer_id']) && $data['delaer_id'] != null && $data['dealer_id'] != '') ? $data['dealer_id'] : null;
            $reqPara['party_name'] = (isset($data['dealer_id']) && $data['dealer_id'] != null && $data['dealer_id'] != '') ? null : $data['dealer_name'];

            $reqPara['user_id'] = $this->authUser->id;
            $reqPara['party_category_id'] = (isset($data['party_category_id']) && $data['party_category_id'] != null && $data['party_category_id'] != '') ? $data['party_category_id'] : null;
            $reqPara['enquiry_no'] = $data['enquiry_number'];
            $reqPara['remark'] = (isset($data['remark']) && $data['remark'] != null && $data['remark'] != '') ? $data['remark'] : null;
            $reqPara['Date'] = $data['date'];

            $po = $this->db->insert('enquiries', $reqPara);
            if ($po) {
                $po_id = $this->db->insert_id();
                if (isset($data['products']) && $data['products'] != null && $data['products'] != '') {

                    foreach ($data['products'] as $product_order) {
                        $element = [];
                        $element['enquiry_id'] = $po_id;
                        $element['product_id'] = $product_order['product_id'];
                        $element['category_id'] = $product_order['category_id'];
                        $this->db->insert('enquiry_products', $element);
                    }
                }
                $this->response(array(
                    "status" => 1,
                    "message" => 'Enquiry added succesfully!',
                ));
            } else {
                $this->response(array(
                    "status" => 0,
                    "message" => 'Enquiry not added, please try again later!',
                ));
            }
        }
    }

    function polist_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }
        $list = ($this->Api->getPoList($this->authUser->id)) ? $this->Api->getPoList($this->authUser->id) : [];

        $this->response(array(
            "status" => 1,
            "results" => array("po_list" => $list), //implode(',',$this->form_validation->error_array())
        ));
    }

    function data_get()
    {

        $this->db->SELECT(['U.id', 'P.name', 'P.item_code', 'P.mrp', 'U.first_name', 'U.last_name']);

        $this->db->FROM('products_order_product POP');

        $this->db->join('products_order PO', 'POP.product_order_id = PO.id');
        $this->db->join('products P', 'POP.product_id = P.id');
        $this->db->join('users U', ' U.id = PO.employee_id');

        $this->db->where('PO.employee_id', 2);

        $result = $this->db->get();
        $result = $result->result_array();
        $this->response($result);
    }

    public function getPartyByEmployee($id)
    {
        return  $this->Api->getPartiesByCategory($id);
    }
    function complainlist_get()
    {
        if (gettype($this->authUser) == 'array') {
            $this->response($this->authUser, 401);
        }

        $list = $this->Api->getComplainList($this->authUser->id) ?? [];

        $this->response(array(
            "status" => 1,
            "results" => array("complain_list" => $list), //implode(',',$this->form_validation->error_array())
        ));
    }
}
