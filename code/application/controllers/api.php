<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class api extends CI_Controller {

    protected $authToken;
    protected $verfiedAuth;
    public $logger;

    function __construct() {
        parent::__construct();
        // $this->load->model("order_model", "order1");
        $this->load->library("log4php");
        Logger::configure( dirname(__FILE__) . '/../third_party/log4php.xml');
        $logger = Logger::getLogger("main");
    }


    public function index() {
        //return "hi";
    }
    
    public function checkAuth($checkAuthToken = TRUE) {
        // $checkAuthToken is false only for non logged in apis
        date_default_timezone_set('Asia/Calcutta');
        $requestHeaders = apache_request_headers();
        $result = array();
        $result['config_status'] = 0; // auth check status
        $result['status'] = 0;  // final api response status

        if ($requestHeaders['API_KEY']) {
            $CI = & get_instance();
            $CI->load->model('apiauthcheck_model');
            $CI->load->config('custom-config');
            $result = $CI->apiauthcheck_model->appStatusVerify($requestHeaders, $checkAuthToken);
            $CI->apiauthcheck_model->setHeader('API_KEY', $requestHeaders['API_KEY']);
            $CI->apiauthcheck_model->setHeader('APP_VERSION', $requestHeaders['APP_VERSION']);
            $CI->apiauthcheck_model->setHeader('CONFIG_VERSION', $requestHeaders['CONFIG_VERSION']);

            if ($result['config_status'] == 1 && $checkAuthToken == TRUE) {
                $this->verfiedAuth = true;
                $this->authToken = $requestHeaders['AUTH_TOKEN'];
            }
        } else {

            $result['config_msg'] = "Header Field is missing";
            $result['status'] = 0;
            $result['msg'] = "Authentication Fail";
            $result['errors'][] = "Header Field is missing";
            $result['data'] = (object)array();
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
        if ($result['status'] == 0 && $result['config_status'] != 1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_REQUEST['data']['user_id'] = $user_id;
        $value = array();
        if (isset($_REQUEST)) {
            $value = $_REQUEST;
        }
        $this->load->library('order');
        $result = $this->order->addOrder($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function userLogin() {
        // $logger->warn("Environment = ". var_dump($_POST));
        //return "here";
        //die('here');
        //print($_POST);die;
        $checkAuthToken = FALSE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['status'] == 0 && $result['config_status'] != 1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        $value = array();
        if (isset($_POST)) {
            $value = $_POST;
        }
        $this->load->library('user');
        $result = $this->user->userLogin($value);
        if($result['status'] == 1)
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
        if ($result['status'] == 0 && $result['config_status'] != 1) {
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
        if ($result['status'] == 0 && $result['config_status'] != 1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $value = array();
        if (isset($_POST)) {
            $value = $_POST;
        }
        $this->load->library('user');
        $result = $this->user->forgotPassword($value);
        $this->returnfunction($result);
    }

    public function orders() {
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
        $_GET['user_id'] = $user_id;
        $value = array();
        if (isset($_GET)) {
            $value = $_GET;
        }
        $this->load->library('order');
        $result = $this->order->fetchordersonly($value);
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
        $_GET['user_id'] = $user_id;
        $value = array();
        if (isset($_GET)) {
            $value = $_GET;
        }
        $this->load->library('order');
        $result = $this->order->getOrderDetail($value);
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);
    }

    public function updateOrder(){
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
    $_POST['data']['user_id'] = $user_id;
    $value = array();
    if (isset($_POST)) {
        $value = $_POST;
    }
        //print_r($value);die;
    $this->load->library('order');
    $result = $this->order->updateCurrentOrder($value);
    $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
    $this->returnfunction($result);   
}

public function partialUpdateOrder(){

        $checkAuthToken = TRUE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['config_status'] == -1){
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData',$data);
            return;

        }
        if ($result['config_status'] == 0){
            $this->output->set_header("RESPONSE_CODE:0");
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData',$data);
            return;
        }
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
        $_POST['user_id'] = $user_id;
        $value = array();
        if (isset ($_POST)){
            $value = $_POST;
        } 
        $this->load->library('order');
            

        $result = $this->order->partialupdateorder($value);
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
    $value = array();
    $value['user_id'] = $user_id;
    $this->load->library('user');
    $result = $this->user->fetchuserdetails($value);
    $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
    $this->returnfunction($result);
}

public function allproducts(){
       $checkAuthToken = TRUE;
       $result = $this->checkAuth($checkAuthToken);
       if ($result ['status'] == 0 && $resuklt[config_status] != 1){
          $json_data = json_encode($result);
          $data['response'] = $json_data;
          $this->load->view('responseData',$data);
          return;

       }

       $CI = & get_instance();
       $CI ->load ->model('apiauthcheck_model');
       $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
       $value = array();
       $value['user_id'] = $user_id;
       $this->load->library('');
             



    }

public function productList() {
    $checkAuthToken = TRUE;
    $result = $this->checkAuth($checkAuthToken);
    if ($result['status'] == 0 && $result['config_status'] != 1) {
        $json_data = json_encode($result);
        $data['response'] = $json_data;
        $this->load->view('responseData', $data);
        return;
    }

    $CI = & get_instance();
    $CI->load->model('apiauthcheck_model');
    $user_id = $CI->apiauthcheck_model->getUserIdbyToken($this->authToken);
    $value = array();
    $_REQUEST['filter']['retailer_id'] = $user_id;
    if (isset($_REQUEST)) {
        $value = $_REQUEST;
    }
    $this->load->library('Product');
    $result = $this->product->productList($value);
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

public function serverDatetime() {
    $checkAuthToken = TRUE;
    $result = $this->checkAuth($checkAuthToken);
    if ($result['status'] == 0 && $result['config_status'] != 1) {
        $json_data = json_encode($result);
        $data['response'] = $json_data;
        $this->load->view('responseData', $data);
        return;
    }
    $headers = apache_request_headers();
    $appVersion = (round($headers['APP_VERSION'], 2));
        //print_r($appVersion);die;
    if($appVersion > 1.2){
        $date = date("Y-m-d H:i:s");
        $date = date('Y-m-d H:i:s', strtotime($date.' - 1 hour'));
        $date = date('Y-m-d H:i:s', strtotime($date.' + 1 day'));   
    }
    else {
        $date = date("Y-m-d H:i:s");
    } 
    $result = array();
    date_default_timezone_set('Asia/Calcutta');
    $result['status'] = 1;
    $result['msg'] = "Server Date Time";
    $result['errors'] = array();

    $result['data']['current_date_time'] = $date;
    $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
    $this->returnfunction($result);
}

public function checkFeedback(){
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
    $_GET['user_id'] = $user_id;
    $value = array();
    if (isset($_GET)) {
        $value = $_GET;
    }
    $this->load->library('feedback');
    $result = $this->feedback->checkFeedbackStatus($value);
    $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
    $this->returnfunction($result);   
}

public function submitFeedback(){
        //$logger = Logger::getLogger("main");
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
    if (isset($_POST)) {
        $value = $_POST;
    }
    $value['user_id'] = $user_id;
    $this->load->library('feedback');
    $result = $this->feedback->submitFeedback($value);
    $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
    $this->returnfunction($result);
}

public function userPayments(){
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
    $_GET['user_id'] = 129;
    $value = array();
    if (isset($_GET)) {
        $value = $_GET;
    }
    $this->load->library('user');
    $result = $this->user->getUserPayments($value);
    $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
    $this->returnfunction($result);
}

public function signUp(){
    $value = array();
    if (isset($_POST)) {
        $value = $_POST;
    }
    $this->load->library('user');
    $result = $this->user->insertRetailerLeads($value);
    $this->returnfunction($result);
}


public function fillRetailerDetails(){
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
     if (isset($_POST)) {
        $value = $_POST;
    }
   // $this->load->library('user');
   // $result =  $this->user->
    $value['user_id'] = $user_id;
    $this->load->library('user');
    $result = $this->user->insertRetailerDetails($value);
    $this->returnfunction($result);

}


public function makeRetailerActive(){
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
     if (isset($_POST)) {
        $value = $_POST;
    }
     $value['user_id'] = $user_id;
    $this->load->library('user');
    $result = $this->user->updateRetailerMakeActive($value);
    $this->returnfunction($result);




}



/*public function emailInvoice(){
    $checkAuthToken = TRUE;
    $result = $this->checkAuth($checkAuthToken);
    if ($result['config_status'] == -1){
        $json_data = json_encode($result);
        $data['response'] = $json_data;
        $this->load->view('responseData',$data);
        return;
    }
    if ($result['config_status'] == 0){
        $this->output -> set_header("RESPONSE_CODE:0");
        $json_data = json_encode($result);
        $data['response'] = $json_data;
        $this->load->view('responseData',$data);
        return;
    }

    $CI = & get_instance();
    $CI->load->model('apiauthcheck_model');
    $user_id = $CI ->apiauthcheck_model ->getUserIdbyToken($this->authToken);
    $_GET['user_id'] = $user_id;
    $value = array();
    $headers = getallheaders();
    if (isset($_GET)) {
        $value = $_GET;
    }
    $value['headers'] = $headers;
    $this ->load -> library('user');
    $result = $this ->user->sendInvoiceEmail($value);
    $this ->returnfunction($result);






}
*/

public function checkAppUpdate(){
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
    $_GET['user_id'] = $user_id;
    $headers = getallheaders();
    $value = array();
    if (isset($_GET)) {
        $value = $_GET;
    }
    $value['headers'] = $headers;
    $this->load->library('user');
    $result = $this->user->checkAppUpdate($value);
    $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
    $this->returnfunction($result);
} 

    public function getcontactnumbers (){
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

        $CI->load->config('custom-config');

       
        $result['order_support'] = $CI->config->item('contact_order_support');
        $result['customer_support'] = $CI->config->item('contact_cust_support');

        if ($result['order_support'] == false || $result['customer_support'] == false){
            $result['status'] = 0 ;
             $result['data'] = (object)array();
            $result['msg'] = 'customer support or order support missing';
        }
        else {
            $result['status'] = 1;
            //$result['msg'] = 'successful'; 
            $result['msg']          = 'contact no found in Database';
            $x = array();
                $x[0] = (object)$result;
                $result['data']['response'] = $this->returnResponse($x);
                $result['data']['responseHeader'] = $this->returnResponseHeader();
               // $result['data']['response'] = $this->returnResponse($result);

        }
          
       $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
       $this->returnfunction($result);

    }

    public function salesContactNo() {
        $checkAuthToken = FALSE;
        $result = $this->checkAuth($checkAuthToken);
        if ($result['status'] == 0 && $result['config_status'] != 1) {
            $json_data = json_encode($result);
            $data['response'] = $json_data;
            $this->load->view('responseData', $data);
            return;
        }
        
        $CI = & get_instance();
        $CI->load->model('apiauthcheck_model');
        $value = array();
        $CI->load->config('custom-config');
        $value['sales_contact'] = $CI->config->item('contact_sales_support');


        if ($value['sales_contact'] == false){
            $result['status'] = 0 ;
             $result['data'] = (object)array();
            $result['msg'] = ' sales support missing';
        }
        else{
            $result['status'] = 1;
            $result['msg'] = "sales no found in database";
            $x = array();
            $x[0] = (object)$value;
            $result['data']['response'] = $this ->returnResponse($x);
            $result['data']['responseHeader'] = $this->returnResponseHeader();


        } 
        $this->output->set_header('AUTH_TOKEN:'.$this->authToken);
        $this->returnfunction($result);

    }



    public function returnResponseHeader() {
        $responseHeader = array();
        $responseHeader['status'] = 0;
        $responseHeader['QTime'] = null;
        $responseHeader['params'] = null;
        return $responseHeader;
    }
    public function returnResponse($data) {
        $response = array();
        if (isset($data) && !empty($data)) {
            $response['numFound'] = count($data);
           // $response['start'] = intval($params['page']);
            $response['docs'] = $data;
        } else {
            $response['numFound'] = null;
            $response['start'] = null;
            $response['docs'] = null;
        }
        return $response;
    }


    




}
