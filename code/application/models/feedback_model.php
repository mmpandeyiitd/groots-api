<?php

class feedback_model extends CI_Model {
    public function __construct(){
    	parent::__construct();
    	$this->legacy_db = $this->load->database('group2', true);
    }

    public function checkFeedbackStatus($params){
    	try {
            $sql = 'select order_id, feedback_status from groots_orders.order_header where user_id = '.$params['user_id'].' and status = "Delivered" order by order_id desc limit 1' ;
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
}
?>