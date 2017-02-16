<?php

class user_model extends CI_Model {

    public function __construct() {
        parent::__construct();
       $this->db2= $this->load->database('group1', true);
    }

    /**
     * Used to save user data as a new record
     * 
     * @param type $params
     * @return boolean
     */
    public function saveUserData($params) {
        try {
            if ($this->db2->insert('retailer', $params)) {
                return $this->db2->insert_id();
            } else {
                $dberrorObjs = new stdClass();
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    public function saveaccessrequest($params) {
        try {
            if ($this->db2->insert('retailer_request', $params)) {
                return $this->db2->insert_id();
            } else {
                $dberrorObjs = new stdClass();
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function updateUserRecord($data) {
        try {
            $this->db2->where('id', $data['id']);
            $this->db2->update('retailer', $data);
            return True;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function userLogin($data) {
        try {

             if (!empty($data['email'])){

                $user_query = $this->db2->query("SELECT id,email,name,contact_person1,registration_status FROM retailer where email='" . $data['email'] . "' and password='".$data['password']."' AND (status = 1 or (status=0 and registration_status != 'OTPVerificationPending'))  ");


                 
            }
            elseif (!empty($data['contact'])){

                $user_query = $this->db2->query("SELECT id,email,name,contact_person1,registration_status FROM retailer where mobile='" . $data['contact'] . "' and password='".$data['password']."' AND (status = 1 or (status=0 and registration_status != 'OTPVerificationPending')) ");

            }





          /* $user_query = $this->db2->query("SELECT id,name,contact_person1 FROM retailer where email='" . $data['email'] . "' and password='".$data['password']."' AND status = 1");*/
            if ($user_query->num_rows() > 0) {
                $row = $user_query->row_array();
                return $row;
            } else {
                return $user_exists;
            }
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $user_exists;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    public function accessrequestexist($data) {
        try {
           $user_query = $this->db2->query("SELECT id FROM retailer_request where retailer_id='" . $data['retailer_id'] . "' and store_id='".$data['brand_id']."'");
            if ($user_query->num_rows() > 0) {
                $row = $user_query->row_array();
                return $row;
            } else {
                return $user_exists;
            }
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $user_exists;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    public function userLogin_check($data) {
        try {
           $user_query = $this->db2->query("SELECT id,name FROM retailer where id='" . $data['id'] . "' and password='".$data['current_password']."'");
            if ($user_query->num_rows() > 0) {
                $row = $user_query->row_array();
                return $row;
            } else {
                return $user_exists;
            }
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $user_exists;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function userExists($data) {
        try {

            if (!empty($data['email'])){

                $user_query = $this->db2->query("SELECT id,email,name,contact_person1 FROM retailer where email='" . $data['email'] . "' AND (status = 1 or (status=0 and registration_status != 'OTPVerificationPending'))");


                 
            }
            elseif (!empty($data['contact'])){

                $user_query = $this->db2->query("SELECT id,email,name,contact_person1 FROM retailer where mobile='" . $data['contact'] . "' AND (status = 1 or (status=0 and registration_status != 'OTPVerificationPending'))");

            }
           
           
            if ($user_query->num_rows() > 0) {
                $row = $user_query->row_array();
                return $row;
            }
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    Public function getUserData($params,$cols = "",$limit = 1,$offset = 0)
    {
        $cond = array();
        $column = NULL ;
        foreach($params as $key=>$value){
            $cond[$key] = $value;
        }
        if(is_array($cols) || is_object($cols)){
           foreach($cols as $key=>$value){
               $column.=$value.",";
           }
           $column = trim($column,",");
        }else if(is_string($cols)){
            $column = " ".$cols." ";
        }else{
            $column = " * ";
        }
        $this->db2->select($column);
        if($limit == 1){
            $query = $this->db2->get_where('login', $cond, $limit, $offset);
            $data=$query->row_array();
        }else{
            if($limit == "-1"){
                $query = $this->db2->get_where('login', $cond);
            }else{
                $query = $this->db2->get_where('login', $cond, $limit, $offset);
            }
            $data=$query->result_array();
        }
        return $data;
    }
    
    public function getUserDetails($params) {
       try{
            $query = 'SELECT r.id, r.name as retailerName,r.name as orgName,r.email as email, r.mobile as contact,r.address as address,r.alternate_email as alternateEmail,r.city as city,r.state as state,r.website as website,r.tan_no as tanNo,r.pan_no as panNo,r.payment_mode as paymentMode,r.collection_frequency as paymentFreq,r.min_order_price as minOrderPrice, r.due_date as outstandingDate, r.total_payable_amount as outstandingAmount, c.name as collectionRepName, ge.name as salesRepName  FROM retailer as r left join collection_agent as c on r.collection_agent_id = c.id left join groots_employee as ge on r.sales_rep_id = ge.id where r.id="'.$params['user_id'].'"';
            //echo $query; die;
            $user_details_query = $this->db2->query($query);
            $user_details  = $user_details_query->result();
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $user_details;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }

    public function getUserPayments($params){
        try{
            $this->db1= $this->load->database('group2', true);
            $query = 'select * from retailer_payments where retailer_id = '.$params['user_id'].' order by date desc';
            $user_payments = $this->db1->query($query);
            if($this->db1->_error_message()){
                $dberrorObjs->error_code = $this->db1->_error_number();
                $dberrorObjs->error_message = $this->db1->_error_message();
                $dberrorObjs->error_query = $this->db1->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db1->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            }
            else{
                $user_payments = $user_payments->result();
                return $user_payments;
            }
        } catch (Exception $e){
            return false;
        }
    }

    public function getUserTotalOrderAmount($params){
        try{
            $this->db1 = $this->load->database('group2', true);
            $orderSql= 'select total_payable_amount from order_header where user_id = '.$params['user_id'].' and delivery_date >= "2016-09-01" and status = "Delivered"';
            $order_amount = $this->db1->query($orderSql);
            if($this->db1->_error_message()){
                $dberrorObjs->error_code = $this->db1->_error_number();
                $dberrorObjs->error_message = $this->db1->_error_message();
                $dberrorObjs->error_query = $this->db1->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db1->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            }
            else{
                $order_amount = $order_amount->result();
                return $order_amount;
            }
        } catch (Exception $e){
            return false;
        }
    }

    public function getUserTotalPaymentAmount($params){
        try{
            $this->db1= $this->load->database('group2', true);
            $paymentSql = 'select paid_amount from retailer_payments where retailer_id = '.$params['user_id'].' and date >= "2016-09-01" and status != 0';
            $paymentAmount = $this->db1->query($paymentSql);
            if($this->db1->_error_message()){
                $dberrorObjs->error_code = $this->db1->_error_number();
                $dberrorObjs->error_message = $this->db1->_error_message();
                $dberrorObjs->error_query = $this->db1->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db1->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            }
            else{
                $paymentAmount = $paymentAmount->result();
                return $paymentAmount;
            }
        } catch (Exception $e){
            return false;
        }
    }

    public function getUserDetailsAll($params) {
       try{
            $query = 'SELECT * from retailer where id = '.$params['id'];
            //echo $query; die;
            $user_details_query = $this->db2->query($query);
            $user_details  = $user_details_query->result();
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $user_details;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }

    public function insertRetailerLeads($params){
        try{
            $params = '(' . implode(',', $params) . ')';
            $sql = 'insert into retailer_leads (name, organisation_name, designation, contact_number, email, created_at, updated_by) values'.$params;
            //die($sql);
            $query = $this->db2->query($sql);
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return new Exception($dberrorObjs->error_message);
            }
            else return array();
        } catch (Exception $e){
            return $e;
        }
    }


     public function insertRetailerDetailsLeads($params,$dat){
        try{
          //  $params = '(' . implode(',', $params) . ')';


                          




            $sql = 'update retailer SET name ='.$params['personName'].', address = '.$params['address'].', tan_no = '.$params['tanNo'].', collection_frequency = '.$params['paymentFreq'].', payment_mode = '.$params['paymentMode'].', pan_no = '.$params['pan_no'].', website = '.$params['website'].', alternate_email = '.$params['alternate_email'].', city = '.$params['city'].', state = '.$params['state'].', pincode = '.$params['pincode'].', retailer_grade_type = '.$params['retailer_grade_type'].' where id = '.$dat['id'] ; 

           /* $sql = 'insert into retailer (name,address, tan_no,collection_frequency, payment_mode,pan_no,website,alternate_email,city,state,pincode) values'.$params;*/
            //die($sql);
            $query = $this->db2->query($sql);
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return new Exception($dberrorObjs->error_message);
            }
            else return array();
        } catch (Exception $e){
            return $e;
        }
    }


    public function checkAppUpdate($params){
        try{
            $result = array();
            $recommended = false;
            $forceUpdate = false;
            $appVersion = $params['APP_VERSION'];
            $apiKey = $params['API_KEY'];
            $configVersion = $params['CONFIG_VERSION'];
            $platformQuery = 'select id from api_platforms where api_key = "'.$apiKey.'"';
            $query = $this->db2->query($platformQuery);
            if($this->db2->_error_message()){
               return $this->setDb2ErrorObject(); 
            }
            $query = $query->result();
            $platformId = $query[0]->id;
            if(is_numeric($platformId)){
                $appVersionQuery = 'select * from app_versions where platform_id = '.$platformId.' order by id desc limit 1';
                $appVersionNew = $this->db2->query($appVersionQuery);
                if($this->db2->_error_message()){
                    return $this->setDb2ErrorObject(); 
                }
                $appVersionNew = $appVersionNew->result();
                $appVersionNew = $appVersionNew[0];
                $appVersionQuery = 'select * from app_versions where platform_id = '.$platformId.' and app_version = "'.$appVersion.'" order by id desc limit 1';
                $appVersionCurrent = $this->db2->query($appVersionQuery);
                if($this->db2->_error_message()){
                    return $this->setDb2ErrorObject(); 
                }
                $appVersionCurrent = $appVersionCurrent->result();
                $appVersionCurrent = $appVersionCurrent[0];
                if(round($appVersionCurrent->app_version, 2) < round($appVersionNew->app_version , 2)){
                    $recommended = true;
                    if(!is_null($appVersionCurrent->expiry_date) && (strtotime($appVersionCurrent->expiry_date) < strtotime(date('Y-m-d H:i:s')))){
                        $forceUpdate = true;
                        $recommended = false;

                    }
                }
                $result['forceUpdate'] = $forceUpdate;
                $result['recommended'] = $recommended;
                $result['latestAppVersion'] = $appVersionNew->app_version;
                return $result;
            }
            else{
                return new Exception('Api Key Mismatch');
            }

        } catch (Exception $e){
            return $e;
        }
    }

    public function setDb2ErrorObject(){
        $dberrorObjs->error_code = $this->db2->_error_number();
        $dberrorObjs->error_message = $this->db2->_error_message();
        $dberrorObjs->error_query = $this->db2->last_query();
        $dberrorObjs->error_time = date("Y-m-d H:i:s");
        $this->db2->insert('dberror', $dberrorObjs);
        return new Exception($dberrorObjs->error_message);
    }

}

?>