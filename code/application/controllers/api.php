<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api extends CI_Controller {
    
    protected $authToken;
    protected $verfiedAuth;

    public function index() {
        //return "hi";
    }
    
    public function checkAuth($checkAuthToken = TRUE) {
        $requestHeaders = apache_request_headers();
        if ($requestHeaders['API_KEY']) {
            $CI = & get_instance();
            $CI->load->model('apiauthcheck_model');
            $CI->load->config('custom-config');
            $result = $CI->apiauthcheck_model->appStatusVerify($requestHeaders, $checkAuthToken);
            $CI->apiauthcheck_model->setHeader('API_KEY', $requestHeaders['API_KEY']);
            $CI->apiauthcheck_model->setHeader('APP_VERSION', $requestHeaders['APP_VERSION']);
            $CI->apiauthcheck_model->setHeader('CONFIG_VERSION', $requestHeaders['CONFIG_VERSION']);
            if ($checkAuthToken == TRUE) {
                if (!$requestHeaders['API_KEY']) {
                    $result['config_status'] = -1;
                    $result['config_msg'] = "Auth Token is missing";
                    return $result;
                }
            } 
            if ($result['config_status'] == 1 && $checkAuthToken == TRUE) {
                $this->verfiedAuth = true;
                $this->authToken = $requestHeaders['AUTH_TOKEN'];
            }
        } else {
            $result['config_status'] = -1;
            $result['config_msg'] = "Header Field is missing";
        }
        return $result;
    }
    
    public function returnfunction($result) {

        if ($result['app'] == 1) {
            $result['status'] = strtolower($result['status']) == 'success' ? 1 : 0;
            if (isset($result['data'])) {
                $result['data'] = isset($result['data']) ? $result['data'] : null;
            } else if (isset($result['response'])) {
                $result['data'] = isset($result['response']) ? $result['response'] : null;
                unset($result['response']);
            } else {
                $result['data'] = null;
            }

            if (is_array($result['errors'])) {
                $result['errors'] = isset($result['errors']) ? $result['errors'] : null;
            } else {
                $result['errors'] = isset($result['errors']) ? array($result['errors']) : null;
            }
        }
        unset($result['app']);
        $json_data = json_encode($result);
        $data['response'] = $json_data;
        $this->load->view('responseData', $data);
    }

    public function addOrder() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('order');
        $result = $this->order->addOrder($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function addremovecart() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('mycart');
        $result = $this->mycart->addremovetoCart($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function getcartcount() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('mycart');
        $result = $this->mycart->getcartcount($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }
    
    public function fetchcart() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('mycart');
        $result = $this->mycart->fetchCartData($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function userRegistration() {
        $checkAuthToken = FALSE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('user');
        $result = $this->user->userRegistration($value);
        $this->returnfunction($result);
    }
    
    public function userProfileupdate() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('user');
        $result = $this->user->userProfileupdate($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function userLogin() {
        
        $checkAuthToken = FALSE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('user');
        $result = $this->user->userLogin($value);
        if(isset($result['data']['user_id']))
        {
            $CI = & get_instance();
            $CI->load->model('apiauthcheck_model');
            $this->authToken = $CI->apiauthcheck_model->saveAuthToken($result['data']['user_id']);
            $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
            
        }
        $this->returnfunction($result);
    }

    public function resetPassword() {
        $checkAuthToken = FALSE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('user');
        $result = $this->user->resetPassword($value);
        $this->returnfunction($result);
    }
    
    public function changePassword() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('user');
        $result = $this->user->changePassword($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function forgotPassword() {
        $checkAuthToken = FALSE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('user');
        $result = $this->user->forgotPassword($value);
        $this->returnfunction($result);
    }

    public function fetchorders() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('order');
        $result = $this->order->fetchorder($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }
    
    public function orderdetails() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('order');
        $result = $this->order->orderdetails($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function user_profile() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('user');
        $result = $this->user->fetchuserdetails($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }
    
    public function productList() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('Product');
        $result = $this->product->productList($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }
    
    public function storeList() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['filter']['retailer_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('Store');
        $result = $this->store->storeList($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }
    
    public function productDetails() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['filter']['retailer_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('Product');
        $result = $this->product->productDetails($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }
    
    public function storeDetails() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('Store');
        $result = $this->store->storeDetails($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function addremovewishlist() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('wishlist');
        $result = $this->wishlist->addremovetowishlist($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function fetchwishlist() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('wishlist');
        $result = $this->wishlist->fetchWishlist($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function checkAuthentication($params) {
        try {
            $this->load->library('form_validation');
            $this->load->config('custom-config');
            if ($this->form_validation->required($params['api_username']) === FALSE) {
                $arr['status'] = 0;
                $arr['msg'] = 'username is invalid';
                $arr['should_be'] = 'As Required';
                $arr['data'] = null;
                $arr['errors'] = array('username is invalid');
                return $arr;
            } elseif ($this->form_validation->required($params['api_password']) === FALSE) {
                $arr['status'] = 0;
                $arr['msg'] = 'password is invalid';
                $arr['data'] = null;
                $arr['errors'] = array('password is invalid');
                //$arr['should_be'] = 'As Required';
                return $arr;
            } else {
                if ($params['api_username'] == $this->config->item('USERNAME') && $params['api_password'] == $this->config->item('PASSWORD')) {
                    $arr['status'] = 1;
                    $arr['msg'] = 'Data Valided';
                    return $arr;
                } else {
                    $arr['status'] = 0;
                    $arr['msg'] = 'Invalid api username or password';
                    $arr['data'] = null;
                    $arr['errors'] = array('Invalid api username or password');
                    return $arr;
                }
            }
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function addreview() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('review');
            $result = $this->review->addReview($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }
    
    public function updateOrder() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('order');
            $result = $this->order->updateOrder($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }
    
    public function homeBanner() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('banner');
            $result = $this->banner->getBanner();
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function categoryBanner() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('banner');
            $result = $this->banner->getBanner($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function userExists() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('user');
            $result = $this->user->userExists($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }
    
    public function add_user_addresses() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('address');
            $result = $this->address->addaddress($value);
            $result['app'] = isset($value['app']) ? 1 : 0;
            $this->returnfunction($result);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function user_addresses() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('address');
            $result = $this->address->fetchaddress($value);
            $result['app'] = isset($value['app']) ? 1 : 0;
            $this->returnfunction($result);
            // $json_data = json_encode($result);
            // $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
        }
        // $this->load->view('responseData', $data);
    }
    
    public function delete_user_address() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('address');
            $result = $this->address->deleteaddress($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function user_request_callback() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('user');
            $result = $this->user->userRequestCallback($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function user_request_quotation() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('user');
            $result = $this->user->userRequestQuotation($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function pg_request() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('pg_transaction');
            $result = $this->pg_transaction->addPgRequest($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function pg_response() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('pg_transaction');
            $result = $this->pg_transaction->updatePgResponse($value);
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function homePageApi() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result['status'] = 1; //$this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('homepage');
            $result = $this->homepage->homepageapi($value);
            $result['app'] = isset($value['app']) ? 1 : 0;
            $this->returnfunction($result);
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
        }
    }
    
    public function searchList() {
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $result = $this->checkAuthentication($value);
        if ($result['status'] == 1) {
            $this->load->library('Product');
            $result = $this->product->searchList($value);
            if ($value['app'] == 1) {
                $result['status'] = $result['status'] == 'Success' ? 1 : 0;
                if (!empty($result['response']['grouped']['title']['doclist']['docs'])) {
                    $result['data'] = $result['response']['grouped']['title']['doclist']['docs'];
                } else {
                    $result['data'] = null;
                }
                //$result['data'] = isset($result['response']) ? array($result['response']) : array('');
                $result['errors'] = isset($result['errors']) ? array($result['errors']) : array('');
                unset($result['response']);
            }
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        } else {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
        }
        $this->load->view('responseData', $data);
    }

    public function like_app() {
        $value = array();

        $data['status'] = 1;
        $data['msg'] = "Data Save";
        $data['data'] = null;
        $data['errors'] = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        if (empty($value['user_id'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "User Id Required";
        }
        if (empty($value['subscribed_product_id'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "Subscribed Product Id Required";
        }
        if (empty($value['like_status'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "Like Status Required";
        }

        $json_data = json_encode($data);
        $data['response'] = $json_data;
        $this->load->view('responseData', $data);
    }

    public function order_cancel_app() {
        $value = array();
        $data['status'] = 1;
        $data['msg'] = "Order Cancel Successfully";
        $data['data'] = null;
        $data['errors'] = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        if (empty($value['user_id'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "User Id Required";
        }
        if (empty($value['order_id'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "Order Id Required";
        }
        $json_data = json_encode($data);
        $data['response'] = $json_data;
        $this->load->view('responseData', $data);
    }

    public function otp() {
        $value = array();
        $data['status'] = 1;
        $data['msg'] = "One time password";
        $data['data'] = array('otp' => '1234');
        $data['errors'] = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        if (empty($value['mobile'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "mobile Required";
            $data['data'] = null;
        }

        $json_data = json_encode($data);
        $data['response'] = $json_data;
        $this->load->view('responseData', $data);
    }
    
    public function orderrating() {
        $value = array();
        $data['status'] = 1;
        $data['msg'] = "Thank For Order Rating";
        $data['data'] = null;
        $data['errors'] = null;
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        if (empty($value['order_id'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "order_id Required";
            $data['data'] = null;
        }
        if (empty($value['rating'])) {
            $data['status'] = 0;
            $data['msg'] = "Data Save Fail";
            $data['errors'][] = "rating Required";
            $data['data'] = null;
        }
        if (!empty($value['rating'])) {
            if (($value['rating']) < 0 || ($value['rating']) > 6) {
                $data['status'] = 0;
                $data['msg'] = "Data Save Fail";
                $data['errors'][] = "rating must be between 0 to 5";
                $data['data'] = null;
            }
        }
        $json_data = json_encode($data);
        $data['response'] = $json_data;
        $this->load->view('responseData', $data);
    }
    
    public function ordertrackingdetails() {
        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        if ($result['config_status'] == 0) {
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('order');
        $result = $this->order->ordertrackingdetails($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }
    
}
