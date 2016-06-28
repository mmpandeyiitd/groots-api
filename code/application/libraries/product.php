<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class product {

    public function getallproductsUrl() 
    {
        $CI = & get_instance();
        $CI->load->config('custom-config');
        $url = $CI->config->item('SOLR_URL');
        return $url.'product/productList' ;
    }

    public function getallproducts($params)
    {
        $CI = & get_instance();
        $CI->load->config('custom-config');
        $params['api_key'] = $CI->config->item('API_KEY');
        $params['api_password'] = $CI->config->item('API_PASSWORD');
        $url = $this->getallproductsUrl();
        $headers = array();
        $method  = 'POST';
        $obj  = $this->callCurl($url, $headers, $params, $method);
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

    public function callCurl($baseUrl, $headers, $values, $method){
        $CI = & get_instance();
        $CI->load->config('custom-config');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch,CURLOPT_HEADER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $postFields = http_build_query($values);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $CI->config->item('CONNECTION_TIMEOUT'));
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $CI->config->item('RESPONSE_TIMEOUT'));
        $http_result = curl_exec($ch);
        $err_msg = curl_error($ch);
        $err_num = curl_errno($ch);
        curl_close($ch);
        list($headers, $content) = explode("\r\n\r\n",$http_result,2);
        foreach (explode("\r\n",$headers) as $hdr){
            $arr=explode(':', $hdr);
            if($arr[0]=='AUTH_TOKEN'){
               $authToken  = isset($arr[1])?$arr[1]:'';
               if($baseUrl == CONFIG::CURLURL.'userLogin'){
                    $_SESSION['authToken'] = $authToken;
               }
            }
        }

        if ($http_result === false) {        
            $http_result = '{"error" : "1", "msg": "' . $err_num."--".$err_msg . '"}';
        }
        
        return $content;
    }
    
    
    public function productList($params) { 
        try {
            $data = $this->getallproducts($params);
            if($data->status =='Success' & $data->response !=null)
            {
                $result['status'] = 1;
                $result['msg'] = "Product Listing";
                $result['errors'] = array();
                $result['data'] = $data->response;
            }
            else
            {
                $result['status'] = 0;
                $result['msg'] = "unable to fetch data";    
                $result['errors'][] = "Product listing fail , Please try again";
                $result['data'] = (object)array();
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['errors'] = $ex->getMessage();
            $result['msg'] = "unable to fetch data";    
            $result['data'] = (object)array();
            return $result;
        }
    }
}
