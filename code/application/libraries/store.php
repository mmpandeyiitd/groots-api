<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class store {

    public function getallstoresUrl() 
    {
        $CI = & get_instance();
        $CI->load->config('custom-config');
        $url = $CI->config->item('SOLR_URL');
        return $url.'store/storeList' ;
    }

    public function getallstores($params)
    {
        $CI = & get_instance();
        $CI->load->config('custom-config');
        $params['api_key'] = $CI->config->item('API_KEY');
        $params['api_password'] = $CI->config->item('API_PASSWORD');
        $url = $this->getallstoresUrl();
        $obj = $this->rest_helper($url,$params) ;
        if($obj)
        {
            $data = json_decode($obj);
            return $data;
        }
        else
        {
            return false ;
        }
    }
    
    public function rest_helper($url, $params = null,$cacheControl=0, $verb = 'POST', $format = 'json')
    {
        $cparams = array(
        'http' => array(
        'method' => $verb,
        'ignore_errors' => true
        )
        );

        if ($params !== null) {
            $params = http_build_query($params,null,'&');

            if ($verb == 'POST') {
                $cparams['http']['content'] = $params;
            } else {
                $url .= '?' . $params;
            }
        }
        $context = stream_context_create($cparams);
        $fp = fopen($url, 'rb', false, $context);
        if (!$fp) {
            $res = false;
        } else {

            $res = stream_get_contents($fp);
        }

        if ($res === false) {
            throw new Exception("$verb $url failed: $php_errormsg");
        }

        switch ($format) {
            case 'json':
                $r = $res;
                if ($r === null) {
                    throw new Exception("failed to decode $res as json");
                }
                return $r;

            case 'xml':
                $r = simplexml_load_string($res);
                if ($r === null) {
                    throw new Exception("failed to decode $res as xml");
                }
                return $r;
        }
        return $res;
    }
    
    
     public function storeDetails($params) { 
        try {
            
            $CI = & get_instance();
            $CI->load->model('store_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_store_details($params);
            if ($result['status'] == 1) {
               $data = $CI->store_model->getStorebasicDetails($params);
               if($data) {
                   $f_data['store_name'] = $data[0]->store_name;
                   $f_data['store_details'] = $data[0]->store_details;
                   $f_data['tags'] = $data[0]->tags;
                   $f_data['logo'] = $data[0]->logo;
                   $f_data['banner_image'] = $data[0]->banner_image;
                   $store_product_details = $CI->store_model->getStoreproductDetails($params);
                   $prod_arr = array();
                   $lookbook_arr = array();
                   $press_arr = array();
                   $image_arr = array();
                   if($store_product_details){
                       foreach($store_product_details as $key => $val){
                           $prod_arr[$key]['subscribed_product_id'] = $val->subscribed_product_id;
                           $prod_arr[$key]['image'] = $val->subscribed_product_id;
                           $prod_arr[$key]['category_id'] = $val->subscribed_product_id;
                       }
                   }
                   $f_data['collection'] = $prod_arr;
                   $store_lookbook_details = $CI->store_model->getStorelookbookDetails($params);
                   if($store_lookbook_details){
                       foreach($store_lookbook_details as $key => $val){
                           $lookbook_arr[$key]['image_main_url'] = $val->image_main_url;
                           $lookbook_arr[$key]['pdf_url'] = $val->pdf_url;
                           $lookbook_arr[$key]['desciption'] = $val->desciption;
                       }
                   }
                   $f_data['lookbook'] = $lookbook_arr;
                   $store_image_details = $CI->store_model->getStoreimageDetails($params);
                   if($store_image_details){
                       foreach($store_image_details as $val){
                           $image_arr['image_main_url'] = $val->image_main_url;
                           $image_arr['desciption'] = $val->desciption;
                       }
                   }
                   $f_data['gallery'] = $image_arr;
                   $store_press_details = $CI->store_model->getStorepressDetails($params);
                   if($store_press_details){
                       foreach($store_press_details as $val){
                           $press_arr['image_main_url'] = $val->image_main_url;
                           $press_arr['desciption'] = $val->desciption;
                       }
                   }
                   $f_data['press'] = $press_arr;
                   $result['status'] = "Success";
                   $result['msg'] = "Store Details";
                   $result['data'] = $f_data;
                   
                   
               }else{
                   $result['status'] = "Fail";
                   $result['msg'] = "unable to fetch data";    
                   $result['errors'] = array("Store listing fail , Please try again");
                   $result['data'] = array();
               }
            }else{
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function storeList($params) { 
        try {
            $CI = & get_instance();
            $CI->load->library('validation');
            $result = $CI->validation->validate_store_list($params);
            if ($result['status'] == 1) {
                if(isset($params['all_stores']) && $params['all_stores'] == 1){
                    unset($_REQUEST['filter']['retailer_id']);
                }
                $data = $this->getallstores($params);
                if($data->status =='Success')
                {
                    $result['status'] = "Success";
                    $result['msg'] = "Store Listing";
                    $result['data'] = $data->response;
                }
                else
                {
                    $result['status'] = "Fail";
                    $result['msg'] = "unable to fetch data";    
                    $result['errors'] = array("Store listing fail , Please try again");
                    $result['data'] = array();
                }
            }else{
                $result['status'] = "Failed";
            }    
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
}
