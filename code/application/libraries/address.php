<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class address {

    public function addaddress($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('addresses_model');
            $CI->load->config('custom-config');
            $CI->load->library('validation');
            $get_res = $CI->validation->validate_add_user_addresses($params);
            if ($get_res['status']==1)
            {
                $data = array();
                $data[0]        = $params['name'];
                $data[1]        = $params['email'];
                $data[2]        = $params['phone'];
                $data[3]        = $params['address'];
                $data[4]        = $params['city'];
                $data[5]        = $params['state'];
                $data[6]        = $params['pincode'];

                $address_string = implode("~", $data);

                $new_params = array();
                $new_params['user_id']        = $params['user_id'];
                $new_params['address']        = $address_string;
                $new_params['type']           = 'B';

                $add_addresses = $CI->addresses_model->addUserAddresses($new_params);
                //print_r($add_addresses);
                if($add_addresses['status'] == 'SUCCESS')
                {
                    $result['status']       = 'SUCCESS';
                    $result['msg']          = 'Addresses Added in Database Successfully';
                }
                else
                {
                    $result['status']       = 'Failed';
                    $result['msg']          = $add_addresses['msg'];
                }
            }
            else
            {
                $result['status']   = "Fail";
                $result['msg']      = $get_res['msg'];
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function fetchaddress($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('addresses_model');
            $CI->load->config('custom-config');
            $CI->load->library('validation');
            $get_res = $CI->validation->validate_fetch_user_addresses($params);
            if ($get_res['status']==1)
            {
                $data = array();
                $data['user_id']        = $params['user_id'];

                $user_addresses = $CI->addresses_model->getUserAddresses($data);

                $count = count($user_addresses);
                for($i=0; $i < $count; $i++)
                {
                    $arr = explode("~", $user_addresses[$i]->address);
                    $new_arr[$i]['name']        = $arr[0];
                    $new_arr[$i]['email']       = $arr[1];
                    $new_arr[$i]['phone']       = $arr[2];
                    $new_arr[$i]['address']     = $arr[3];
                    $new_arr[$i]['city']        = $arr[4];
                    $new_arr[$i]['state']       = $arr[5];
                    $new_arr[$i]['pincode']     = $arr[6];
                    $new_arr[$i]['add_id']      = $user_addresses[$i]->id;
                }

                $result['data']         = $new_arr;
                $result['count']        = $count;
                $result['status']       = 'SUCCESS';
                $result['msg']          = 'Addresses found in Database';
            }
            else
            {
                $result['status']   = "Fail";
                $result['msg']      = $get_res['msg'];
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function deleteaddress($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('addresses_model');
            $CI->load->config('custom-config');
            $CI->load->library('validation');
            $get_res = $CI->validation->validate_delete_user_address($params);
            if ($get_res['status']==1)
            {
                $data = array();
                $data['user_id']          = $params['user_id'];
                $data['address_id']       = $params['address_id'];

                $remove_address = $CI->addresses_model->removeUserAddress($data);

                if($remove_address == 1)
                {
                    $result['status']       = 'SUCCESS';
                    $result['msg']          = 'Address removed from Database';
                }
                else
                {
                    $result['status']       = 'Failed';
                    $result['msg']          = 'Address is not removed from Database';
                    $result['error']        = 'Query failed to execute';
                }
            }
            else
            {
                $result['status']   = "Fail";
                $result['msg']      = $get_res['msg'];
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
}