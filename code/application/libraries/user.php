<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user {
    
    public function resetPassword($params) {
        try {
            if (empty($params['key'])) {
                $result['status'] = 0;
                $result['msg'] = "Reset Password Fail";
                $result['errors'][] = "Invalid Key";
                $result['data'] = (object)array();
                return $result;
            }
            $encodedata = base64_decode($params['key']);
            $arr = explode('~', $encodedata);
            if (empty($arr[0]) && empty($arr[1])) {
                $result['status'] = 0;
                $result['msg'] = "Reset Password Fail";
                $result['errors'][] = "Invalid Key";
                $result['data'] = (object)array();
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
                    $result = array();
                    $data['id'] = $params['user_id'];
                    $data['password'] = md5($params['password']);
                    $res = $CI->user_model->updateUserRecord($data);
                    if ($res) {
                        $result['status'] = 1;
                        $result['msg'] = "Password has been updated Successfully";
                        $result['errors'] = array();
                        $result['data'] = (object)array();
                    } else {
                        $result['status'] = 0;
                        $result['msg'] = "Save to fail data";
                        $result['errors'][] = "Failed to update password";
                        $result['data'] = (object)array();
                    }
                }
            } else {
                $result['status'] = 0;
                $result['msg'] = "Reset Password Fail";
                $result['errors'][] = "Invalid Key";
                $result['data'] = (object)array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = "Save to fail data";
            $result['errors'] = $ex->getMessage();
            $result['data'] = (object)array();
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
    
    public function userLogin($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $CI->load->config('custom-config');
            $master_password = $CI->config->item('MASTER_PASSWORD');
            $result = $CI->validation->validate_user_login($params);
            if ($result['status'] == 1) {
                $data = $CI->user_model->userExists($params);
                if($data)
                {
                    $result = array();
                    $params['password'] = md5($params['password']);
                    $res = $CI->user_model->userLogin($params);
                    if ($res) {
                        $result['status'] = 1;
                        $result['errors'] = array();
                        $result['msg'] = "User Login Successfully";
                        $result['data']["user_id"] = $res["id"];
                        $result['data']["retailer_name"] = $res["contact_person1"];
                        $result['data']["name"] = $res["name"];
                    } else if ($master_password == $params['password']){
                        $result['status'] = 1;
                        $result['errors'] = array();
                        $result['msg'] = "User Login Successfully";
                        $result['data']["user_id"] = $data["id"];
                        $result['data']["retailer_name"] = $data["contact_person1"];
                        $result['data']["name"] = $data["name"];
                    } else {
                        $result['status'] = 0;
                        $result['msg'] = "User Login Fail";
                        $result['errors'][] = "Invalid email or password";
                        $result['data'] = (object)array();
                    }
                } else {
                    $result['status'] = 0;
                    $result['msg'] = "User Login Fail";
                    $result['errors'][] = "Invalid email or password";
                    $result['data'] = (object)array();
                }
                
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = "Failed to login";
            $result['errors'] = $ex->getMessage();
            $result['data'] = (object)array();
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
                $result =array();
                $data = $CI->user_model->userExists($params);
                if ($data) {
                    $data['date'] = date('Y-m-d H:i:s');
                    $str = $data["id"] . '~' . $data['date'];
                    $encodedata = rawurlencode(base64_encode($str));
                    $CI->load->config('custom-config');
                    $url = $CI->config->item('URL') . "reset-password.php?key=" . $encodedata;
                    $viewdata['name'] = $data['name'];
                    $viewdata['link'] = $url;
                    $viewdata['base_path'] = $CI->config->item('URL');
                    $message = $CI->load->view('forgotPwd', $viewdata, TRUE);
                    $emailData['body'] = $message;
                    $emailData['subject'] = "Groots : Forgot password";
                    $emailData['email'] = $data['email'];
                    $data = $CI->communicationengine->emailCommunication($emailData);
                    if ($data) {
                        $result['status'] = 1;
                        $result['msg'] = "User Forgot Password";
                        $result['errors'] = array();
                        $result['data'] = json_decode($data);
                    } else {
                        $result['status'] = 0;
                        $result['msg'] = "Forgot Password Fail";
                        $result['errors'][] = "Fail to send email";
                        $result['data'] = (object)array();
                    }
                } else {
                    $result['status'] = 0;
                    $result['msg'] = "Forgot Password Fail";
                    $result['errors'][] = "invalid email";
                    $result['data'] = (object)array();
                }
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['errors'] = $ex->getMessage();
            $result['msg'] = "Forgot Password Fail";
            $result['data'] = (object)array();
            return $result;
        }
    }

    public function fetchuserdetails($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('user_model');
            $user_details = $CI->user_model->getUserDetails($params);
            $result['status']       = 1;
            $result['msg']          = 'User details found in Database';
            $result['data']['responseHeader'] = $this->returnResponseHeader();
            $result['data']['response'] = $this->returnResponse($user_details, $params);
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function getUserPayments($params){
        try{
            $CI = & get_instance();
            $CI->load->model('user_model');
            $user_payments = $CI->user_model->getUserPayments($params);
            if ($user_payments == false || is_a($user_payments, 'Exception')) {
                $result['status'] = 0;
                $result['msg'] = 'Cannot Find Data. Please Try Again';
                $result['error'] = is_a($user_payments, 'Exception') ? $user_payments->getMessage() : 'Cannot Find Error';
                return $result;
                }
            $result['status']       = 1;
            $result['msg']          = 'User Payments found in Database';
            $result['data']['responseHeader'] = $this->returnResponseHeader();
            $result['data']['response'] = $this->returnResponse($user_payments, $params);
            return $result;
        } catch (Exception $e){
            $result['status'] = 0;
            $result['errors'] = $ex->getMessage();
            $result['msg'] = "User Payments Fail";
            $result['data'] = (object)array();
            return $result;
        } 
    }

    public function returnResponseHeader() {
        $responseHeader = array();
        $responseHeader['status'] = 0;
        $responseHeader['QTime'] = null;
        $responseHeader['params'] = null;
        return $responseHeader;
    }

    public function returnResponse($data, $params) {
        $response = array();
        if (isset($data) && !empty($data)) {
            $response['numFound'] = count($data);
            $response['start'] = intval($params['page']);
            $response['docs'] = $data;
        } else {
            $response['numFound'] = null;
            $response['start'] = null;
            $response['docs'] = null;
        }
        return $response;
    }
}
