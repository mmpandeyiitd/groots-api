<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Logging_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
	public function saveHookDetail($data)
	{
            if($this->db->insert('logging', $data)){
            return $this->db->insert_id();
        }else{
            $dberrorObjs->error_code = $this->db->_error_number();
			$dberrorObjs->error_message = $this->db->_error_message();
			$dberrorObjs->error_query = $this->db->last_query();
			$dberrorObjs->error_time = date("Y-m-d H:i:s");
			$this->db->insert('dberror', $dberrorObjs);
		    return FALSE;
        }
	}	
}