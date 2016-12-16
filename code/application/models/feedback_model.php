<?php

class feedback_model extends CI_Model {

    public $logger;
    public function __construct(){
    	parent::__construct();
    	$this->legacy_db = $this->load->database('group2', true);
        $this->load->library("log4php");
        Logger::configure( dirname(__FILE__) . '/../third_party/log4php.xml');
    }

    public function checkFeedbackStatus($params){
    	try {
            $sql = 'select order_id, feedback_status, delivery_date from groots_orders.order_header where user_id = '.$params['user_id'].' and status = "Delivered" order by order_id desc limit 1' ;
            $query = $this->legacy_db->query($sql);
            $order_feedback = $query->result();
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            } else {
                return $order_feedback;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function setFeedbackStatus($params){
        try{
            $sql = 'update order_header set feedback_status = "Submitted" where order_id = '.$params['orderId'];
            $orderHeaderQuery = $this->legacy_db->query($sql);
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            } else {
                return true;
            }
        } catch (Ecxeption $e){
            return false;
        }
    }

    public function insertFeedbackData($data, $params){
        try{
            if(count($data) != count($data, COUNT_RECURSIVE)){
                //var_dump($data);
                $this->legacy_db->insert_batch('feedbacks', $data);
                $sql = 'update order_header set user_comment = CONCAT(user_comment," feedback_comment = '.$params['comment'].'"), order_rating = '.$params['rating'].' where order_id = '.$params['orderId'];
                $query = $this->legacy_db->query($sql);
            }
            else{
               $this->legacy_db->insert('feedbacks', $data);
               $sql = 'update order_header set order_rating = "5" where order_id = '.$params['orderId'];
               $query = $this->legacy_db->query($sql);
            }
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            }
            return true; 
        } catch (Exception $e){
            return false;
        }
    }
}
?>