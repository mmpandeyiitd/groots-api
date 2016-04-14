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
    
    
    public function productList($params) { 
        try {
            $data = $this->getallproducts($params);
            if($data->status =='Success' & $data->response !=null)
            {
                $result['status'] = 1;
                $result['msg'] = "Product Listing";
                $result['errors'][] = array();
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
