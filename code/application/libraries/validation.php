<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class validation {

    public function validate_review($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['name']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Name Required";
            }
            if ($CI->form_validation->min_length($params['name'], 4) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Name Min length 4 charaters";
            }
            if ($CI->form_validation->max_length($params['name'], 100) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Name Max length 100 charaters";
            }
            if ($CI->form_validation->required($params['title_of_review']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Tilte of review required";
            }
            if ($CI->form_validation->min_length($params['title_of_review'], 4) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Tilte of review Min length 4 charaters";
            }
            if ($CI->form_validation->required($params['title_of_review']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Tilte of review required";
            }
            if ($CI->form_validation->min_length($params['review'], 4) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Review Min length 4 charaters";
            }
            if ($CI->form_validation->required($params['ip_address']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "IP Address required";
            }

            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_order($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            $CI->load->config('custom-config');
            $delivery_date_limit = $CI->config->item('DELIVERY_DATE_LIMIT');
            $rex = '/^[a-z][a-z ]*$/i';
            if ($CI->form_validation->required($params['order_prefix']) == False) {
                $result['errors'][] = "Order Prefix required";
            }
            
            if ($CI->form_validation->alpha($params['order_prefix']) == False) {
                $result['errors'][] = "Order Prefix accept only alphabets";
            }
            
            if ($CI->form_validation->required($params['discount_amt']) == False) {
                $result['errors'][] = "Total Discount required";
            }
            if ($CI->form_validation->numeric($params['discount_amt']) === FALSE || $params['discount_amt'] < 0) {
                $result['errors'][] = "Total Discount should be numeric number and greater than zero";
            }
            if ($CI->form_validation->required($params['total_payable_amount']) == False) {
                $result['errors'][] = "Total Payable Amount required";
            }
            if ($CI->form_validation->numeric($params['total_payable_amount']) === FALSE || $params['total_payable_amount'] < 0) {
                $result['errors'][] = "Total Payable Amount should be numeric number and greater than zero";
            }
            if ($CI->form_validation->required($params['total']) == False) {
                $result['errors'][] = "Total  Amount required";
            }
            if ($CI->form_validation->numeric($params['total']) === FALSE || $params['total'] < 0) {
                $result['errors'][] = "Total  Amount should be numeric number and greater than zero";
            }

            if ($CI->form_validation->required($params['total_shipping_charges']) == False) {
                $result['errors'][] = "Total Shipping Charges Required";
            }

            if ($CI->form_validation->numeric($params['total_shipping_charges']) === FALSE || $params['total_shipping_charges'] < 0) {
                $result['errors'][] = "Total Shipping Charges should be numeric number and greater than zero";
            }

            if ($CI->form_validation->required($params['total_tax']) == False) {
                $result['errors'][] = "Total Tax Charges Required";
            }

            if (!(isset($params['coupon_code']))) {
                $result['errors'][] = "Coupon Code Required either value or blank";
            }

            if ($CI->form_validation->required($params['order_type']) == False) {
                $result['errors'][] = "Order Type Charges Required";
            }

            if ($CI->form_validation->numeric($params['total_tax']) === FALSE || $params['total_tax']) {
                $result['errors'][] = "Total Tax should be numeric number and greater than zero";
            }

            if(isset($params['delivery_date']) && $params['delivery_date'] != ''){
                if (!(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$params['delivery_date'])))
                {
                    $result['errors'][] = "Invalid Delivery date should be in YYYY-MM-DD format.";
                }

                if(strtotime($params['delivery_date']) < strtotime(date('Y-m-d')) || strtotime($params['delivery_date']) > strtotime(date('Y-m-d', strtotime(' +'.$delivery_date_limit.''))))
                {
                    $result['errors'][] = "Delivery date should be current date or within next ".$delivery_date_limit;
                }  
            }
            $product_arr = array();
            $count = count($params['product_details']);
            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    if ($CI->form_validation->required($params['product_details'][$i]['subscribed_product_id']) == False) {
                        $result['errors'][] = "Subscribed Product ID required";
                    }
                    if ($CI->form_validation->is_natural_no_zero($params['product_details'][$i]['subscribed_product_id']) === FALSE) {
                        $result['errors'][] = "Subscribed Product ID should be numeric number and greater than zero";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['base_product_id']) == False) {
                        $result['errors'][] = "Base Product ID required";
                    }
                    if ($CI->form_validation->is_natural_no_zero($params['product_details'][$i]['base_product_id']) === FALSE) {
                        $result['errors'][] = "Base Product ID should be numeric number";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['store_id']) == False) {
                        $result['errors'][] = "Store ID required";
                    }
                    if ($CI->form_validation->is_natural_no_zero($params['product_details'][$i]['store_id']) === FALSE) {
                        $result['errors'][] = "Store ID should be numeric number";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['unit_price']) == False) {
                        $result['errors'][] = "Unit Price required";
                    }
                    if ($CI->form_validation->numeric($params['product_details'][$i]['unit_price']) === FALSE || $params['product_details'][$i]['unit_price'] < 0) {
                        $result['errors'][] = "Unit Price should be numeric number and greater than zero";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['product_name']) == False) {
                        $result['errors'][] = "Product Name required";
                    }
                    
                    if (strlen($params['product_details'][$i]['product_name']) > 100 || strlen($params['product_details'][$i]['product_name']) < 2) {
                        $result['errors'][] = "Product Name must between 2 to 100 characters";
                    }

                    if ($CI->form_validation->required($params['product_details'][$i]['product_qty']) == False) {
                        $result['errors'][] = "Product Quantity required";
                    }
                    
                    if ($CI->form_validation->numeric($params['product_details'][$i]['product_qty']) == False || $params['product_details'][$i]['product_qty'] < 0) {
                        $result['errors'][] = "Product Quantity should be numeric and greater than zero";
                    }

                    if ($CI->form_validation->required($params['product_details'][$i]['tax']) == False) {
                        $result['errors'][] = "Product Tax required";
                    }
                    
                    if ($CI->form_validation->numeric($params['product_details'][$i]['tax']) == False || $params['product_details'][$i]['tax'] < 0) {
                        $result['errors'][] = "Product Tax should be numeric";
                    }
                }
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";

            } else {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['data'] = (object)array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            $result['data'] = (object)array();
            return $result;
        }
    }

    public function validate_add_to_cart($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['session_id']) == False && $CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "user id or session id required";
            }
            if ($CI->form_validation->required($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "product_id Required";
            }
            if ($CI->form_validation->numeric($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "product_id must be numeric";
            }
            if ($CI->form_validation->required($params['product_qty']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "product_qty Required";
            }
            if ($CI->form_validation->numeric($params['product_qty']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "product_qty must be numeric";
            }
//            if ($CI->form_validation->required($params['ip_address']) == False) {
//                $result['status'] = 0;
//                $result['msg'] = "Fail to save data";
//                $result['errors'][] = "IP Address required";
//            }

            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_fetch_cart_data($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['session_id']) == False && $CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "user id or session id required";
            }

            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_update_cart($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['session_id']) == False && $CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "USER ID OR SESSION ID REQUIRED";
                $result['errors'][]="USER ID OR SESSION ID REQUIRED";
            }
            if ($CI->form_validation->required($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "product_id Required";
                $result['errors'][]="product_id Required";
            }
            if ($CI->form_validation->numeric($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "product_id must be numeric";
                $result['errors'][]="product_id must be numeric";
            }
            if ($CI->form_validation->required($params['product_qty']) == False) {
                $result['status'] = 0;
                $result['msg'] = "product_qty Required";
                $result['errors'][]="product_qty Required";
            }
            if ($CI->form_validation->numeric($params['product_qty']) == False) {
                $result['status'] = 0;
                $result['msg'] = "product_qty must be numeric";
                $result['errors'][]="product_qty must be numeric";
            }

            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_add_user($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            $rex = '/^[a-z][a-z ]*$/i';
            if(isset($params['state']) && $params['state'] != '')
            {
                if (! preg_match($rex,$params['state'])) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Invalid State";
                }
            }
            
            if(isset($params['city']) && $params['city'] != '')
            {
                if (! preg_match($rex,$params['city'])) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Invalid City";
                }
            }
            
            if(isset($params['address']) && $params['address'] != '')
            {
                if (strlen($params['address']) > 64 || strlen($params['address']) < 10) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Address must between 10 to 64 characters";
                }
            }
            
            if ($CI->form_validation->required($params['name']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "name required";
            }
            
            if (! preg_match($rex,$params['name'])) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Invalid Name";
            }
            
            if (strlen($params['name']) > 32 || strlen($params['name']) < 3) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Name must between 3 to 32 characters";
            }
            
            if ($CI->form_validation->required($params['email']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "email Required";
            }
            if ($CI->form_validation->valid_email($params['email']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "invalid email Address";
            }

            if ($CI->form_validation->required($params['password']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "password Required";
            }
            
            if (preg_match('/^ /',$params['password'])){
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Space not allowed at the begining of password";
            }
            
            if (strlen($params['password']) > 32 || strlen($params['password']) < 8 ) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Password must between 8 to 32 characters.";
            }
            
            if(isset($params['mobile']))
            {
                if ($CI->form_validation->required($params['mobile']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Wrong mobile number";
                }
                if (preg_match('/[^0-9]/',$params['mobile']) || $params['mobile'] < 999999999 || $params['mobile'] > 9999999999) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Wrong mobile number";
                }
            }
            
            if ($CI->form_validation->required($params['contact_person1']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Contact Person 1 Required";
            }
            
            if (! preg_match($rex,$params['contact_person1'])) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Invalid contact person1";
            }
            
            if (strlen($params['contact_person1']) > 32 || strlen($params['contact_person1']) < 3) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "contact person1 must between 3 to 32 characters";
            }
            
            if(isset($params['contact_person2'])){
                if ($CI->form_validation->required($params['contact_person2']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Contact Person 2 is empty";
                }

                if (! preg_match($rex,$params['contact_person2'])) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Invalid contact person2";
                }

                if (strlen($params['contact_person2']) > 32 || strlen($params['contact_person2']) < 3) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "contact person2 must between 3 to 32 characters";
                }
            }
            
            if ($CI->form_validation->required($params['VAT_number']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "VAT Number Required";
            }
            
            if (preg_match('/[^a-z_\-0-9]/i',$params['VAT_number'])) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Invalid VAT Number, Accept only alphanumeric value";
            }
            
            if ($CI->form_validation->required($params['store_size']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Store Size Required";
            }
            
            if ($CI->form_validation->numeric($params['store_size']) == False || strpos($params['store_size'],'.') != '' || $params['store_size'] <= 0) {
                $result['status'] = 0;
                $result['msg'] = "product_qty must be numeric";
                $result['errors'][]="Store size must be numeric";
            }
            
            if ($CI->form_validation->required($params['key_brand_stocked']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Key brand stocked Required";
            }
            
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_update_user($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            $rex = '/^[a-z][a-z ]*$/i';
            
            if(isset($params['name']) && $params['name'] != ''){
                if (! preg_match($rex,$params['name'])) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Invalid Name";
                }
                if (strlen($params['name']) > 32 || strlen($params['name']) < 3) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Name must between 3 to 32 characters";
                }
            }

            if(isset($params['state']) && $params['state'] != '')
            {
                if (! preg_match($rex,$params['state'])) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Invalid State";
                }
            }
            
            if(isset($params['city']) && $params['city'] != '')
            {
                if (! preg_match($rex,$params['city'])) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Invalid City";
                }
            }
            
            if(isset($params['address']) && $params['address'] != '')
            {
                if (strlen($params['address']) > 64 || strlen($params['address']) < 10) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Address must between 10 to 64 characters";
                }
            }
            
            if(isset($params['mobile']) && $params['mobile'] != '')
            {
                if ($CI->form_validation->required($params['mobile']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Wrong mobile number";
                }
                if (preg_match('/[^0-9]/',$params['mobile']) || $params['mobile'] < 999999999 || $params['mobile'] > 9999999999) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][] = "Wrong mobile number";
                }
            }
            
            if(isset($params['store_size']) && $params['store_size'] != ''){
                if ($CI->form_validation->numeric($params['store_size']) == False || strpos($params['store_size'],'.') != '' || $params['store_size'] <= 0) 
                {
                    $result['status'] = 0;
                    $result['msg'] = "product_qty must be numeric";
                    $result['errors'][]="Store size must be numeric";
                }
            }
            
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_reset_pwd($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['errors'][] = "user id required";
            }

            if ($CI->form_validation->required($params['password']) == False) {
                $result['status'] = 0;
                $result['errors'][] = "password Required";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            else
            {
                $result['msg'] = "Fail to save data";
                $result['data'] = array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            $result['data'] = array();
            return $result;
        }
    }
    
    public function validate_product_details($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            
            if (is_array($params['filter']['subscribed_product_id'])) {
                foreach ($params['filter']['subscribed_product_id'] as $value) {
                    if ($CI->form_validation->numeric($value) == False) {
                        $result['status'] = 0;
                        $result['msg'] = "Unable to fetch data";
                        $result['errors'][]="subscribed_product_id must be numeric";
                    }
                }
            }else{
                if ($CI->form_validation->numeric($params['filter']['subscribed_product_id']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Unable to fetch data";
                    $result['errors'][]="subscribed_product_id must be numeric";
                }
            }
            
            if (is_array($params['filter']['subscribed_product_id'])) {
                foreach ($params['filter']['subscribed_product_id'] as $value) {
                    if ($CI->form_validation->required($value) == False) {
                        $result['status'] = 0;
                        $result['msg'] = "Unable to fetch data";
                        $result['errors'][]="subscribed_product_id required";
                    }
                }
            }else{
                if ($CI->form_validation->required($params['filter']['subscribed_product_id']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Unable to fetch data";
                    $result['errors'][]="subscribed_product_id required";
                }
            }

            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_store_details($params) {

        try {
            
            $CI = & get_instance();
            $CI->load->library('form_validation');
            
            if ($CI->form_validation->numeric($params['store_id']) == False || strpos($params['store_id'],'.') != '' || $params['store_id'] < 0) {
                $result['status'] = 0;
                $result['msg'] = "Unable to fetch data";
                $result['errors'][]="store_id must be numeric";
            }
            
            if ($CI->form_validation->required($params['store_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Unable to fetch data";
                $result['errors'][]="store_id required";
            }

            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_store_list($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if(isset($params['all_stores'])){
                if ($CI->form_validation->required($params['all_stores']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to fetch data";
                    $result['errors'][]="all_store required";
                }
                if ($CI->form_validation->numeric($params['all_stores']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to fetch data";
                    $result['errors'][]="all_store must be numeric";
                }
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_accessrequestbrand($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['brand_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Brand id required";
            }

            if ($CI->form_validation->numeric($params['brand_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Brand id must be numeric";
            }
            
            if ($CI->form_validation->required($params['comment']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Comment Required";
            }
            
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_change_pwd($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "user id required";
            }

            if ($CI->form_validation->required($params['current_password']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Current password Required";
            }
            
            if ($CI->form_validation->required($params['new_password']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "New password Required";
            }
            
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_user_login($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');


            if ($CI->form_validation->required($params['password']) == False) {
                    $result['status'] = 0;
                    $result['errors'][] = "password Required";
                }




            if (!empty($params['email'])){




                if ($CI->form_validation->required($params['email']) == False) {
                    $result['status'] = 0;
                    $result['errors'][] = "email required";
                }
                if ($CI->form_validation->valid_email($params['email']) == False) {
                    $result['status'] = 0;
                    $result['errors'][] = "invalid email Address";
                }
                
            }
            elseif (!empty($params['contact'])) {

                 if ($CI->form_validation->required($params['contact']) == False) {
                    $result['status'] = 0;
                    $result['errors'][] = "contact required";
                }
                if ($this->valid_contact($params['contact']) == False) {
                    $result['status'] = 0;
                    $result['errors'][] = "invalid contact no";
                }
                /*if ($CI->form_validation->required($params['password']) == False) {
                    $result['status'] = 0;
                    $result['errors'][] = "password Required";
                }*/


                 # code...
             } 
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            else
            {
                $result['msg'] = "Failed to login";
                $result['data'] = (object)array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            $result['data'] = (object)array();
            return $result;
        }
    }

    public function validate_forgotpasswod($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['email']) == False) {
                $result['status'] = 0;
                $result['errors'][] = "email required";
            }
            if ($CI->form_validation->valid_email($params['email']) == False) {
                $result['status'] = 0;
                $result['errors'][] = "invalid email Address";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            else
            {
                $result['msg'] = "Api Fail";
                $result['data'] = (object)array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            $result['data'] = (object)array();
            return $result;
        }
    }

    public function validate_categoryid($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['category_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "category id required";
                //$result['errors'][]="product_id Required";
            }
            if ($CI->form_validation->numeric($params['category_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "category id must be numeric";
                //$result['errors'][]="product_id must be numeric";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_order_details($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "User id required";
                $result['errors'][]="User id Required";
            }
            
            if ($CI->form_validation->required($params['order_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Order id required";
                $result['errors'][]="Order id Required";
            }
            
            if ($CI->form_validation->numeric($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "User id must be numeric";
                $result['errors'][]="User id must be numeric";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    public function validate_partial_update_order($params){
      try{
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['user_id']) == False)
            {
                $result['status'] = 0;
                $result['msg'] = "User id required";
                $result['errors'][] = "User id Required";
            }
           else if ($CI->form_validation->required($params['orderId']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "Order id required";
                    $result['errors'][]="Order id Required";
            }
           else if ($CI->form_validation->numeric($params['user_id']) == False) {
                    $result['status'] = 0;
                    $result['msg'] = "User id must be numeric";
                    $result['errors'][]="User id must be numeric";
            }
           else if ($CI->form_validation->numeric($params['orderId']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Order Id must be numeric";
                $result['errors'][] = "Order Id must be numeric";
            } 
            else if (empty($result)){
                $result['status'] = 1;
                $result['msg'] = "valid data";

            }
            return $result;
        } 
        catch(Exception $ex){
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }

    }











    
    public function validate_fetch_order($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "User id required";
                //$result['errors'][]="User id Required";
            }

            if ($CI->form_validation->numeric($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "User id must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_fetch_user_addresses($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "User id required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "User id must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_order_details_order_id($orderId){
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($orderId) == False) {
                $result['status'] = 0;
                $result['msg'] = "Order id required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($orderId) == False) {
                $result['status'] = 0;
                $result['msg'] = "Order id must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_request_callback($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Product id required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Product id must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if ($CI->form_validation->required($params['product_title']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Product title id required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['quantity']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Quantity is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($params['quantity']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Quantity must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if ($CI->form_validation->required($params['name']) == False) {
                $result['status'] = 0;
                $result['msg'] = "name is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['email']) == False) {
                $result['status'] = 0;
                $result['msg'] = "email is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['phone']) == False) {
                $result['status'] = 0;
                $result['msg'] = "phone is required";
                //$result['errors'][]="User id Required";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_request_quotation($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Product id required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($params['product_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Product id must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if ($CI->form_validation->required($params['product_title']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Product title id required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['quantity']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Quantity is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($params['quantity']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Quantity must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if ($CI->form_validation->required($params['name']) == False) {
                $result['status'] = 0;
                $result['msg'] = "name is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['email']) == False) {
                $result['status'] = 0;
                $result['msg'] = "email is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['phone']) == False) {
                $result['status'] = 0;
                $result['msg'] = "phone is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['company_name']) == False) {
                $result['status'] = 0;
                $result['msg'] = "company_name is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['industry_type']) == False) {
                $result['status'] = 0;
                $result['msg'] = "industry_type is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['city']) == False) {
                $result['city'] = 0;
                $result['msg'] = "Quantity is required";
                //$result['errors'][]="User id Required";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_delete_user_address($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['address_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "address_id is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($params['address_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "address_id must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_add_user_addresses($params) {
        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');

            if ($CI->form_validation->required($params['name']) == False) {
                $result['status'] = 0;
                $result['msg'] = "name is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['email']) == False) {
                $result['status'] = 0;
                $result['msg'] = "email is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['address']) == False) {
                $result['status'] = 0;
                $result['msg'] = "address is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['city']) == False) {
                $result['status'] = 0;
                $result['msg'] = "city is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['pincode']) == False) {
                $result['status'] = 0;
                $result['msg'] = "address_id is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->numeric($params['pincode']) == False) {
                $result['status'] = 0;
                $result['msg'] = "address_id must be numeric";
                //$result['errors'][]="User id must be numeric";
            }
            if ($CI->form_validation->required($params['state']) == False) {
                $result['status'] = 0;
                $result['msg'] = "state is required";
                //$result['errors'][]="User id Required";
            }
            if ($CI->form_validation->required($params['phone']) == False) {
                $result['status'] = 0;
                $result['msg'] = "phone is required";
                //$result['errors'][]="User id Required";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function pg_request_validation($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['order_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "order id required";
            }
            if ($CI->form_validation->required($params['request_data']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "request data Required";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function pg_response_validation($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "id required";
            }
            if ($CI->form_validation->required($params['request_data']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "request data Required";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_update_order($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['order_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "order_id Required";
            }

            if ($CI->form_validation->required($params['payment_status']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "payment_status required";
            }

            if ($CI->form_validation->required($params['total_paid_amount']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "total_paid_amount required";
            }
            if ($CI->form_validation->required($params['status']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "status required";
            }
            if ($CI->form_validation->required($params['payment_method']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "payment_method required";
            }
            if ($CI->form_validation->required($params['transaction_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "transaction_id required";
            }
            if ($CI->form_validation->required($params['payment_ref_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "payment_ref_id required";
            }
            if ($CI->form_validation->required($params['transaction_time']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "transaction_time required";
            }
            if ($CI->form_validation->required($params['bankname']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "bankname required";
            }
            if ($CI->form_validation->required($params['payment_source']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "payment_source required";
            }
            if ($CI->form_validation->required($params['payment_gateway_name']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "payment_gateway_name required";
            }
            if ($CI->form_validation->required($params['bank_transaction_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "bank_transaction_id required";
            }
            if ($CI->form_validation->required($params['payment_mod']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "payment_mod required";
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_add_remove_to_cart($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['session_id']) == False && $CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "user id or session id required";
            }
            
            if ($CI->form_validation->required($params['action']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Action required";
            }
            
            if (isset($params['delete_cart'])) {
                if ($params['delete_cart'] != 1 && $params['delete_wishlist'] != 0) {
                    $result['status'] = 0;
                    $result['msg'] = "delete_cart must be 1 or 0";
                }
            }
            
            if ($params['action'] != 1 && $params['action'] != 2) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Action must be 1 or 2";
            }
            
            if (is_array($params['product_id'])) {
                foreach ($params['product_id'] as $value) {
                    if ($CI->form_validation->numeric($value) == False) {
                        $result['status'] = 0;
                        $result['msg'] = "product_id must be numeric";
                        $result['errors'][]="product_id must be numeric";
                    }
                }
            }
            
            if ($CI->form_validation->required($params['action']) != False && $params['action'] == 1) {
                if (is_array($params['product_id'])) {
                    foreach ($params['product_id'] as $value) {
                        if ($CI->form_validation->required($value) == False) {
                            $result['status'] = 0;
                            $result['msg'] = "Fail to save data";
                            $result['errors'][] = "product_id Required";
                            }
                    }
                }
                else
                {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][]="product_id Required as array.";
                }
            }
            
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function validate_add_remove_to_wishlist($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['session_id']) == False && $CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "user id or session id required";
            }
            
            if ($CI->form_validation->required($params['action']) == False) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Action required";
            }
            
            if (isset($params['delete_wishlist'])) {
                if ($params['delete_wishlist'] != 1 && $params['delete_wishlist'] != 0) {
                    $result['status'] = 0;
                    $result['msg'] = "delete_wishlist must be 1 or 0";
                }
            }
            
            if ($params['action'] != 1 && $params['action'] != 2) {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'][] = "Action must be 1 or 2";
            }
            
            if (is_array($params['product_id'])) {
                foreach ($params['product_id'] as $value) {
                    if ($CI->form_validation->numeric($value) == False) {
                        $result['status'] = 0;
                        $result['msg'] = "product_id must be numeric";
                        $result['errors'][]="product_id must be numeric";
                    }
                }
            }
            
            if ($CI->form_validation->required($params['action']) != False && $params['action'] == 1) {
                if (is_array($params['product_id'])) {
                    foreach ($params['product_id'] as $value) {
                        if ($CI->form_validation->required($value) == False) {
                            $result['status'] = 0;
                            $result['msg'] = "Fail to save data";
                            $result['errors'][] = "product_id Required";
                            }
                    }
                }
                else
                {
                    $result['status'] = 0;
                    $result['msg'] = "Fail to save data";
                    $result['errors'][]="product_id Required as array.";
                }
            }
            
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_delete_wishlist($params) {

        try {
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if ($CI->form_validation->required($params['session_id']) == False && $CI->form_validation->required($params['user_id']) == False) {
                $result['status'] = 0;
                $result['msg'] = "USER ID OR SESSION ID REQUIRED";
                $result['errors'][]="USER ID OR SESSION ID REQUIRED";
            }

            if (is_array($params['product_id'])) {
                foreach ($params['product_id'] as $value) {
                    if ($CI->form_validation->numeric($value) == False) {
                        $result['status'] = 0;
                        $result['msg'] = "product_id must be numeric";
                        $result['errors'][]="product_id must be numeric";
                    }
                }
            }

            if (isset($params['delete_wishlist'])) {
                if ($params['delete_wishlist'] != 1 && $params['delete_wishlist'] != 0) {
                    $result['status'] = 0;
                    $result['msg'] = "delete_wishlist must be 1 or 0";
                }
            }

            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_feedback_data($params){
        try{
            $CI = & get_instance();
            $CI->load->library('form_validation');
            if($CI->form_validation->required($params['orderId']) == False){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Order Id required';
            }
            if($CI->form_validation->numeric($params['orderId']) == False){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Order Id Must Be Numeric';
            }
            if($CI->form_validation->required($params['rating']) == False){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Rating required';
            }
            if($CI->form_validation->numeric(intval($params['rating'])) == False){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Rating Must Be Numeric';
            }
            if($params['rating'] != 5 && isset($params['feedback']) && !empty($params['feedback'])){
                foreach ($params['feedback'] as $key => $value) {
                    if($CI->form_validation->required($value['feedbackId']) == False){
                        $result['status'] = 0;
                        $result['msg'] = 'Fail To Save Data';
                        $result['errors'] = 'Feedback Category Id required';
                    }
                    if($CI->form_validation->numeric($value['feedbackId']) == False){
                        $result['status'] = 0;
                        $result['msg'] = 'Fail To Save Data';
                        $result['errors'] = 'Feedback Category Id Must Be Numeric';
                    }
                    if(isset($value['feedback_id']) && $value['feedbackId'] == 6){
                        if($CI->form_validation->required($value['comment']) == False){
                            $result['status'] = 0;
                            $result['msg'] = 'Fail To Save Data';
                            $result['errors'] = 'Comment required For "Others" Option';
                        }
                    }
                }
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            }
            return $result;
        } catch(Exception $ex){
            $result['status'] = 0;
            $result['msg'] = 'Fail To Save Data';
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function validate_reatailer_leads_data($params){
        try{
            $CI = & get_instance();
            $CI->load->library('form_validation');
            $result = array();
            if($CI->form_validation->required($params['name']) == False){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Name required';
                return $result;
            }
            else if($CI->form_validation->required($params['orgName']) == False){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Organisation Name Required';
                return $result;
            }
            else if($CI->form_validation->required($params['contactNo']) == False){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Contact Number Required';
                return $result;
            }
            else if(!$CI->form_validation->numeric($params['contactNo'])){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
                $result['errors'] = 'Contact Number Must Be Numeric';
                return $result;
            }
            else {
                $result['status'] = 1;
                $result['msg'] = 'Valid Data';
            }
        } catch(Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = 'Fail To Save Data';
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

  public function valid_contact($str) {
        if (preg_match("[^0-9]", $str)){
            return False;
        }
        else{ 

            //$numbersOnly = ereg_replace("[^0-9]", "", $str);
            $numberOfDigits = strlen(trim($str));

            if ($numberOfDigits == 10) {
                //echo $numbersOnly;
                return True;
            } else {
                return False;
                //echo 'Invalid Phone Number';
            }
        }
    }

    public function validate_product_details_new($params){
        try{
            $CI = & get_instance();
            $CI->load->library('form_validation');
            $result = array();
            if($CI->form_validation->required($params['subscribed_product_id']) == False){
                die(print_r($params));
                $result['errors'] = 'Subscribed Product Id Required';
            }
            else if($CI->form_validation->numeric($params['subscribed_product_id']) == False){
                $result['errors'] = 'Subscribed Product Id should be numeric';
            }
            else if($CI->form_validation->required($params['unit_price']) == False){
                $result['errors'] = 'Unit Price Required';
            }
            else if($CI->form_validation->numeric($params['unit_price']) == False){
                $result['errors'] = 'Unit Price should be decimal';
            }
            else if($CI->form_validation->required($params['product_qty']) == False){
                $result['errors'] = 'Product Quantity Required';
            }
            else if($CI->form_validation->numeric($params['product_qty']) == False){
                $result['errors'] = 'Product Quantity shoul be decimal';
            }
            if($result['errors'] != false){
                $result['status'] = 0;
                $result['msg'] = 'Fail To Save Data';
            }
            else{
                $result['status'] = 1;
                $result['msg'] = 'Valid Data';
            }
            return $result;
        } catch(Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = 'Fail To Save Data';
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }



}
