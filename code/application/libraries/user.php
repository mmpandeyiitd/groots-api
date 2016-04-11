<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user {
    
    public function userRegistration($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_add_user($params);
            if ($result['status'] == 1) {
                $userExist = $CI->user_model->userExists($params);
                if ($userExist) {
                    $result['status'] = "Failed";
                    $result['msg'] = "User Registration Fail";
                    $result['errors'] = array("Email address already exist in our database");
                    return $result;
                }
                $params['password'] = md5($params['password']);
                $user_id = $CI->user_model->saveUserData($params);
                if ($user_id) {
                    $result['status'] = "Success";
                    $result['msg'] = "User Registrated Successfully";
                    $result['response']["user_id"] = $user_id;
                    $result['response']['name'] = $params['name'];
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "User Registrated Fail";
                    $result['errors'] = array("Registration Fail , Please try again");
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = array($ex->getMessage());
            return $result;
        }
    }
    
    
    public function userProfileupdate($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_update_user($params);
            if ($result['status'] == 1) {
                $update_data = array();
                
                $update_data['id'] =  $params['id'];
                if(isset($params['name']) && $params['name'] != ''){
                   $update_data['name'] =  $params['name'];
                }
                if(isset($params['address']) && $params['address'] != ''){
                    $update_data['address'] =  $params['address'];
                }
                if(isset($params['city']) && $params['city'] != ''){
                    $update_data['city'] =  $params['city'];
                }
                if(isset($params['state']) && $params['state'] != ''){
                    $update_data['state'] =  $params['state'];
                }
                if(isset($params['mobile']) && $params['mobile'] != ''){
                    $update_data['mobile'] =  $params['mobile'];
                }
                if(isset($params['telephone']) && $params['telephone'] != ''){
                    $update_data['telephone'] =  $params['telephone'];
                }
                if(isset($params['website']) && $params['website'] != ''){
                    $update_data['website'] =  $params['website'];
                }
                if(isset($params['contact_person1']) && $params['contact_person1'] != ''){
                    $update_data['contact_person1'] =  $params['contact_person1'];
                }
                if(isset($params['contact_person2']) && $params['contact_person2'] != ''){
                    $update_data['contact_person2'] =  $params['contact_person2'];
                }
                if(isset($params['store_size']) && $params['store_size'] != ''){
                    $update_data['store_size'] =  $params['store_size'];
                }
                if(isset($params['product_categories']) && $params['product_categories'] != ''){
                    $update_data['product_categories'] =  $params['product_categories'];
                }
                if(isset($params['key_brand_stocked']) && $params['key_brand_stocked'] != ''){
                    $update_data['key_brand_stocked'] =  $params['key_brand_stocked'];
                }
                if(isset($params['categories_of_interest']) && $params['categories_of_interest'] != ''){
                    $update_data['categories_of_interest'] =  $params['categories_of_interest'];
                }
                $status = $CI->user_model->updateUserRecord($update_data);
                if ($status) {
                    $result['status'] = "Success";
                    $result['msg'] = "User Profile updated Successfully";
                    $result['response']["user_id"] = $params['id'];
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "User Profile update Fail";
                    $result['errors'] = array("User profile update Fail , Please try again");
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = array($ex->getMessage());
            return $result;
        }
    }

    public function resetPassword($params) {
        try {
            if (empty($params['key'])) {
                $result['status'] = "Failed";
                $result['msg'] = "Reset Password Fail";
                $result['errors'] = "Invalid Key";
                return $result;
            }
            $encodedata = base64_decode($params['key']);
            $arr = explode('~', $encodedata);
            if (empty($arr[0]) && empty($arr[1])) {
                $result['status'] = "Failed";
                $result['msg'] = "Reset Password Fail";
                $result['errors'] = "Invalid Key";
                return $result;
            }
            $user_id = $arr[0];
            $date = $arr[1];
            $currenttime = date('Y-m-d H:i:s');
            $date1 = strtotime($currenttime);
            $date2 = strtotime($date);
            $diff = $date1 - $date2;
            $CI = & get_instance();
            $CI->load->config('custom-config');
            if ($diff <= intval($CI->config->item('SEC'))) {
                $params['user_id'] = $user_id;
                $CI->load->model('user_model');
                $CI->load->library('validation');

                $result = $CI->validation->validate_reset_pwd($params);
                if ($result['status'] == 1) {
                    $data['id'] = $params['user_id'];
                    $data['password'] = md5($params['password']);
                    $res = $CI->user_model->updateUserRecord($data);
                    if ($res) {
                        $result['status'] = "Success";
                        $result['msg'] = "Password has been updated Successfully";
                    } else {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Failed to update password";
                    }
                } else {
                    $result['status'] = "Failed";
                }
            } else {
                $result['status'] = "Failed";
                $result['msg'] = "Reset Password Fail";
                $result['errors'] = "Invalid Key";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function changePassword($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_change_pwd($params);
            if ($result['status'] == 1) {
                $params['current_password'] = md5($params['current_password']);
                $res = $CI->user_model->userLogin_check($params);
                if ($res) {
                    $data['id'] = $params['id'];
                    $data['password'] = md5($params['new_password']);
                    $res = $CI->user_model->updateUserRecord($data);
                    if ($res) {
                        $result['status'] = "Success";
                        $result['msg'] = "Password has been updated Successfully";
                    } else {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Failed to update password";
                    }
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "Save to fail data";
                    $result['errors'] = "Invalid current password";
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function accessrequestbrand($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_accessrequestbrand($params);
            if ($result['status'] == 1) {
                $res = $CI->user_model->accessrequestexist($params);
                if ($res) {
                        $result['status'] = "Failed";
                        $result['msg'] = "Fail to Save data";
                        $result['errors'] = "Request already exist or rejected. ";
                }  else {
                    $data['retailer_id'] = $params['retailer_id'];
                    $data['store_id'] = $params['brand_id'];
                    $data['comment'] = $params['comment'];
                    $res = $CI->user_model->saveaccessrequest($data);
                    if ($res) {
                        $result['status'] = "Success";
                        $result['msg'] = "Request sent successfully";
                    } else {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Failed to sent access request";
                    }
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function userLogin($params) {
        try {

            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_user_login($params);
            if ($result['status'] == 1) {
                $params['password'] = md5($params['password']);
                $res = $CI->user_model->userLogin($params);
                if ($res) {
                    $result['status'] = "Success";
                    $result['msg'] = "User Login Successfully";
                    $result['response']["user_id"] = $res["id"];
                    $result['response']["name"] = $res["name"];
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "User Login Fail";
                    $result['errors'] = "Invalid email or password";
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function forgotPassword($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $CI->load->library('communicationengine');
            $result = $CI->validation->validate_forgotpasswod($params);
            if ($result['status'] == 1) {
                $data = $CI->user_model->userExists($params);
                if ($data) {
                    $data['date'] = date('Y-m-d H:i:s');
                    $str = $data["id"] . '~' . $data['date'];
                    $encodedata = base64_encode($str);
                    $CI->load->config('custom-config');
                    $url = $CI->config->item('URL') . "reset-password.php?key=" . $encodedata;
                    $viewdata['name'] = $data['name'];
                    $viewdata['link'] = $url;
                    $message = $CI->load->view('forgotPwd', $viewdata, TRUE);
                    $emailData['body'] = $message;
                    $emailData['subject'] = "Yorder : Forgot password";
                    $emailData['email'] = $data['email'];
                    $data = $CI->communicationengine->emailCommunication($emailData);
                    if ($data) {
                        $result['status'] = "Success";
                        $result['msg'] = "User Forgot Password";
                        $result['response'] = $data;
                    } else {
                        $result['status'] = "Failed";
                        $result['msg'] = "Forgot Password Fail";
                        $result['errors'] = "Fail to send email";
                    }
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "Forgot Password Fail";
                    $result['errors'] = "invalid email";
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function userExists($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_forgotpasswod($params);
            if ($result['status'] == 1) {
                $userExist = $CI->user_model->userExists($params);
                if ($userExist) {
                    $result['status'] = "Success";
                    $result['msg'] = "User Exists";
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "User Not Exists";
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function userRequestCallback($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->config('custom-config');
            $CI->load->library('communicationengine');
            $CI->load->library('validation');
            $get_res = $CI->validation->validate_request_callback($params);
            if ($get_res['status'] == 1) {
                $data = array();
                $data['product_id'] = $params['product_id'];
                $data['product_title'] = $params['product_title'];
                $data['quantity'] = $params['quantity'];
                $data['name'] = $params['name'];
                $data['email'] = $params['email'];
                $data['phone'] = $params['phone'];
                //print_r($data);
                //die("hhh1");

                $message = $CI->load->view('requestCallback', $data, TRUE);

                $emailData['body'] = $message;
                $emailData['subject'] = "Supplified : Request Callback";
                $emailData['email'] = $CI->config->item('ADMIN_EMAIL');

                $data = $CI->communicationengine->emailCommunication($emailData);
                if ($data) {
                    $result['status'] = "Success";
                    $result['msg'] = 'User request callback mail send to admin';
                    $result['data'] = $data;
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "User request callback mail Failed";
                    $result['errors'] = "Fail to send email";
                }
            } else {
                $result['status'] = "Fail";
                $result['msg'] = $get_res['msg'];
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function userRequestQuotation($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->config('custom-config');
            $CI->load->library('communicationengine');
            $CI->load->library('validation');
            $get_res = $CI->validation->validate_request_quotation($params);
            if ($get_res['status'] == 1) {
                $data = array();
                $data['product_id'] = $params['product_id'];
                $data['product_title'] = $params['product_title'];
                $data['quantity'] = $params['quantity'];
                $data['name'] = $params['name'];
                $data['email'] = $params['email'];
                $data['phone'] = $params['phone'];
                $data['company_name'] = $params['company_name'];
                $data['industry_type'] = $params['industry_type'];
                $data['city'] = $params['city'];
                //print_r($data);
                //die("hhh2");

                $message = $CI->load->view('requestQuotation', $data, TRUE);

                $emailData['body'] = $message;
                $emailData['subject'] = "Supplified : Request Quotation";
                $emailData['email'] = $CI->config->item('ADMIN_EMAIL');

                $data = $CI->communicationengine->emailCommunication($emailData);
                if ($data) {
                    $result['status'] = "Success";
                    $result['msg'] = 'User request Quotation mail send to admin';
                    $result['data'] = $data;
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "User request callback mail Failed";
                    $result['errors'] = "Fail to send email";
                }
            } else {
                $result['status'] = "Fail";
                $result['msg'] = $get_res['msg'];
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function fetchuserdetails($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $data['id']        = $params['id'];
            $user_details = $CI->user_model->getUserDetails($data);
            $result['status']       = 'SUCCESS';
            $result['msg']          = 'User details found in Database';
            $result['data']         = $user_details;
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

}
