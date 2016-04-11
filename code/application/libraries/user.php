<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user {
    
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
    
    public function userLogin($params) {
        try {

            $CI = & get_instance();
            $CI->load->model('user_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_user_login($params);
            if ($result['status'] == 1) {
                $result = array();
                $params['password'] = md5($params['password']);
                $res = $CI->user_model->userLogin($params);
                if ($res) {
                    $result['status'] = 1;
                    $result['errors'] = array();
                    $result['msg'] = "User Login Successfully";
                    $result['data']["user_id"] = $res["id"];
                    $result['data']["name"] = $res["name"];
                } else {
                    $result['status'] = 1;
                    $result['msg'] = "User Login Fail";
                    $result['errors'][] = "Invalid email or password";
                    $result['data'] = array();
                }
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = "Failed to login";
            $result['errors'] = $ex->getMessage();
            $result['data'] = array();
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
