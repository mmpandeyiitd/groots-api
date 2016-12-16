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
           $user_query = $this->db2->query("SELECT id,name,contact_person1 FROM retailer where email='" . $data['email'] . "' and password='".$data['password']."' AND status = 1");
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
           
            $user_query = $this->db2->query("SELECT id,email,name,contact_person1 FROM retailer where email='" . $data['email'] . "' AND STATUS = 1");
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
            $query = 'SELECT r.id, r.name as retailerName, r.due_date as outstandingDate, r.total_payable_amount as outstandingAmount, c.name as collectionRepName, ge.employee_name as salesRepName  FROM retailer as r left join collection_agent as c on r.collection_agent_id = c.id left join groots_employee as ge on r.sales_rep_id = ge.employee_id where r.id="'.$params['user_id'].'"';
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

}

?>