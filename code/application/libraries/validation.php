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
            $rex = '/^[a-z][a-z ]*$/i';
            if ($CI->form_validation->required($params['source_url']) == False) {
                $result['errors'][] = "Source url required";
            }
            if ($CI->form_validation->required($params['billing_name']) == False) {
                $result['errors'][] = "Billing Name required";
            }
            
            if (! preg_match($rex,$params['billing_name'])) {
                $result['errors'][] = "Invalid Billing Name";
            }
            
            if (strlen($params['billing_name']) > 32 || strlen($params['billing_name']) < 4) {
                $result['errors'][] = "Billing Name must between 4 to 32 characters";
            }
            
            if ($CI->form_validation->required($params['billing_email']) == False) {
                $result['errors'][] = "Billing Email required";
            }
            if ($CI->form_validation->valid_email($params['billing_email']) == False) {
                $result['errors'][] = "Invalid Billing Email Address";
            }
            if ($CI->form_validation->required($params['billing_phone']) == False) {
                $result['errors'][] = "Billing Phone no required";
            }
            
            if (preg_match('/[^0-9]/',$params['billing_phone']) || $params['billing_phone'] < 999999999 || $params['billing_phone'] > 9999999999) {
                $result['errors'][] = "Wrong billing phone";
            }

            if ($CI->form_validation->required($params['billing_address']) == False) {
                $result['errors'][] = "Billing Address required";
            }
            
            if (strlen($params['billing_address']) > 64 || strlen($params['billing_address']) < 10) {
                $result['errors'][] = "Billing Address must between 10 to 64 characters";
            }
                
            if ($CI->form_validation->required($params['billing_city']) == False) {
                $result['errors'][] = "Billing City required";
            }
            
            if (! preg_match($rex,$params['billing_city'])) {
                $result['errors'][] = "Invalid Billing City";
            }
            
            if ($CI->form_validation->required($params['billing_state']) == False) {
                $result['errors'][] = "Billing State required";
            }
            
            if (! preg_match($rex,$params['billing_state'])) {
                $result['errors'][] = "Invalid Billing State";
            }
            
            if ($CI->form_validation->required($params['billing_pincode']) == False) {
                $result['errors'][] = "Billing Pincode required";
            }
            
            if ($CI->form_validation->is_natural_no_zero($params['billing_pincode']) == False) {
                $result['errors'][] = "Invalid Billing Pincode";
            }
            
            if ($params['billing_pincode'] < 100000 || $params['billing_pincode'] > 999999) {
                $result['errors'][] = "Invalid Billing Pincode";
            }

            if ($CI->form_validation->required($params['shipping_name']) == False) {
                $result['errors'][] = "Shipping Name required";
            }
            
            if (! preg_match($rex,$params['shipping_name'])) {
                $result['errors'][] = "Invalid Shipping Name";
            }
            
            if (strlen($params['shipping_name']) > 32 || strlen($params['shipping_name']) < 4) {
                $result['errors'][] = "Shipping Name must between 4 to 32 characters";
            }
            
            if ($CI->form_validation->required($params['shipping_email']) == False) {
                $result['errors'][] = "Shipping Email required";
            }
            if ($CI->form_validation->valid_email($params['shipping_email']) == False) {
                $result['errors'][] = "Invalid Shipping Email Address";
            }

            if ($CI->form_validation->required($params['shipping_phone']) == False) {
                $result['errors'][] = "Shipping Phone no required";
            }
            
            if (preg_match('/[^0-9]/',$params['shipping_phone']) || $params['shipping_phone'] < 999999999 || $params['shipping_phone'] > 9999999999) {
                $result['errors'][] = "Wrong Shipping phone";
            }
            
            if ($CI->form_validation->required($params['shipping_address']) == False) {
                $result['errors'][] = "Shipping Address required";
            }
            
            if (strlen($params['shipping_address']) > 64 || strlen($params['shipping_address']) < 10) {
                $result['errors'][] = "Shipping Address must between 10 to 64 characters";
            }
            
            if ($CI->form_validation->required($params['shipping_city']) == False) {
                $result['errors'][] = "Shipping City required";
            }
            
            if (! preg_match($rex,$params['shipping_city'])) {
                $result['errors'][] = "Invalid Shipping City";
            }
            
            if ($CI->form_validation->required($params['shipping_state']) == False) {
                $result['errors'][] = "Shipping State required";
            }
            
            if (! preg_match($rex,$params['shipping_state'])) {
                $result['errors'][] = "Invalid Shipping State";
            }
            
            if ($CI->form_validation->required($params['shipping_pincode']) == False) {
                $result['errors'][] = "Shipping Pincode required";
            }
            
            if ($CI->form_validation->is_natural_no_zero($params['shipping_pincode']) == False) {
                $result['errors'][] = "Invalid Shipping Pincode";
            }
            
            if ($params['shipping_pincode'] < 100000 || $params['shipping_pincode'] > 999999) {
                $result['errors'][] = "Invalid Shipping Pincode";
            }
            
            if ($CI->form_validation->required($params['order_prefix']) == False) {
                $result['errors'][] = "Order Prefix required";
            }
            
            if ($CI->form_validation->alpha($params['order_prefix']) == False) {
                $result['errors'][] = "Order Prefix accept only alphabets";
            }
            
            if ($CI->form_validation->required($params['order_source']) == False) {
                $result['errors'][] = "Order Source required";
            }
            
            if ($CI->form_validation->alpha($params['order_source']) == False) {
                $result['errors'][] = "Order source accept only alphabets";
            }
            
            if ($CI->form_validation->required($params['source_type']) == False) {
                $result['errors'][] = "Source Type required";
            }
            
            if ($CI->form_validation->alpha($params['source_type']) == False) {
                $result['errors'][] = "Source type accept only alphabets";
            }
            
            if ($CI->form_validation->required($params['source_id']) == False) {
                $result['errors'][] = "Source ID required";
            }
            if ($CI->form_validation->is_natural_no_zero($params['source_id']) === FALSE) {
                $result['errors'][] = "Source ID should be numeric number";
            }
            if ($CI->form_validation->required($params['source_name']) == False) {
                $result['errors'][] = "Source Name required";
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

            if ($CI->form_validation->required($params['utm_source']) == False) {
                $result['errors'][] = "UTM Source required";
            }

            if ($CI->form_validation->required($params['user_id']) == False) {
                $result['errors'][] = "User ID required";
            }
            if ($CI->form_validation->numeric($params['user_id']) === FALSE) {
                $result['errors'][] = "User ID should be numeric number";
            }
            if ($CI->form_validation->required($params['cart_id']) == False) {
                $result['errors'][] = "Cart ID required";
            }
            if ($CI->form_validation->is_natural_no_zero($params['cart_id']) === FALSE) {
                $result['errors'][] = "Cart ID should be numeric number";
            }
            if ($CI->form_validation->numeric($params['total_shipping_charges']) === FALSE || $params['total_shipping_charges'] < 0) {
                $result['errors'][] = "Total Shipping Charges should be numeric number and greater than zero";
            }
            if ($CI->form_validation->numeric($params['total_tax']) === FALSE || $params['total_tax']) {
                $result['errors'][] = "Total Tax should be numeric number and greater than zero";
            }

            $product_arr = array();
            $count = count($params['product_details']);
            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    if ($CI->form_validation->required($params['product_details'][$i]['subscribed_product_id']) == False) {
                        $product_arr['live']['errors'][] = "Subscribed Product ID required";
                    }
                    if ($CI->form_validation->is_natural_no_zero($params['product_details'][$i]['subscribed_product_id']) === FALSE) {
                        $product_arr['live']['errors'][] = "Subscribed Product ID should be numeric number and greater than zero";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['base_product_id']) == False) {
                        $product_arr['live']['errors'][] = "Base Product ID required";
                    }
                    if ($CI->form_validation->is_natural_no_zero($params['product_details'][$i]['base_product_id']) === FALSE) {
                        $product_arr['live']['errors'][] = "Base Product ID should be numeric number";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['store_id']) == False) {
                        $product_arr['live']['errors'][] = "Store ID required";
                    }
                    if ($CI->form_validation->is_natural_no_zero($params['product_details'][$i]['store_id']) === FALSE) {
                        $product_arr['live']['errors'][] = "Store ID should be numeric number";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['unit_price']) == False) {
                        $product_arr['live']['errors'][] = "Unit Price required";
                    }
                    if ($CI->form_validation->numeric($params['product_details'][$i]['unit_price']) === FALSE || $params['product_details'][$i]['unit_price'] < 0) {
                        $product_arr['live']['errors'][] = "Unit Price should be numeric number and greater than zero";
                    }
                    if ($CI->form_validation->required($params['product_details'][$i]['product_name']) == False) {
                        $product_arr['live']['errors'][] = "Product Name required";
                    }
                    
                    if (strlen($params['product_details'][$i]['product_name']) > 100 || strlen($params['product_details'][$i]['product_name']) < 2) {
                        $result['errors'][] = "Product Name must between 2 to 100 characters";
                    }

                    if ($CI->form_validation->required($params['product_details'][$i]['product_qty']) == False) {
                        $product_arr['live']['errors'][] = "Product Quantity required";
                    }
                    
                    if ($CI->form_validation->is_natural_no_zero($params['product_details'][$i]['product_qty']) == False || $params['product_details'][$i]['product_qty'] < 0) {
                        $product_arr['live']['errors'][] = "Product Quantity should be numeric and greater than zero";
                    }
                }
                if (!empty($product_arr)) {
                    $result['errors'][] = $product_arr;
                }
            }
            if (empty($result)) {
                $result['status'] = 1;
                $result['msg'] = "valid data";
            } else {
                $result['status'] = 0;
                $result['msg'] = "Fail to save data";
                $result['errors'] = $result['errors'];
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
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
            if ($CI->form_validation->required($params['email']) == False) {
                $result['status'] = 0;
                $result['errors'][] = "email required";
            }
            if ($CI->form_validation->valid_email($params['email']) == False) {
                $result['status'] = 0;
                $result['errors'][] = "invalid email Address";
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
                $result['msg'] = "Failed to login";
                $result['data'] = array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
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
                $result['data'] = array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "0";
            $result['msg'] = "Fail to save data";
            $result['errors'] = $ex->getMessage();
            $result['data'] = array();
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

}
