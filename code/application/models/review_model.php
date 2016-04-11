<?php

class review_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('default',true);
    }

    /**
     * Used to save user data as a new record
     * 
     * @param type $params
     * @return boolean
     */
    public function saveReviewData($params) {
        try {
            if ($this->db->insert('reviews', $params)) {
                return $this->db->insert_id();
            } else {
                $dberrorObjs = new stdClass();
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    

}

?>