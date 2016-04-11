<?php

class pg_transaction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->dborder = $this->load->database('group1', true);
    }

    /**
     * Used to save user data as a new record
     * 
     * @param type $params
     * @return boolean
     */
    public function savePgRequest($params) {
        try {
            if ($this->dborder->insert('pg_transactions', $params)) {
                return $this->dborder->insert_id();
            } else {
                $dberrorObjs = new stdClass();
                $dberrorObjs->error_code = $this->dborder->_error_number();
                $dberrorObjs->error_message = $this->dborder->_error_message();
                $dberrorObjs->error_query = $this->dborder->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dborder->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function updatePgRequest($params) {
        try {
            $this->dborder->where('id', $params['id']);
            $this->dborder->update('pg_transactions', $params);
            if ($this->dborder->_error_message()) {
                $dberrorObjs->error_code = $this->dborder->_error_number();
                $dberrorObjs->error_message = $this->dborder->_error_message();
                $dberrorObjs->error_query = $this->dborder->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dborder->insert('dberror', $dberrorObjs);
                return FALSE;
            }
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }

}

?>