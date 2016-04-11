<?php

class addresses_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //$this->load->database('group1',true);
        $this->legacy_db    = $this->load->database('group1', true);
    }

    /**
     * Used to fetch user addresses
     * 
     * @param type $params
     * @return boolean
     */
    public function getUserAddresses($params) {
       try{
            $user_addresses_query = $this->legacy_db->query('SELECT DISTINCT(address),id FROM user_address where user_id="'.$params['user_id'].'"');
            $user_addresses  = $user_addresses_query->result();

            if($this->legacy_db->_error_message()){
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $user_addresses;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function removeUserAddress($params){
       try{
            $this->legacy_db->delete('user_address', array('id' => $params['address_id']));
            $flag = $this->legacy_db->affected_rows();

            if($this->legacy_db->_error_message()){
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $flag;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function addUserAddresses($params){
       try{
            //$user_addresses_query = $this->legacy_db->query('SELECT id FROM user_address where address="'.$params['address'].'"');
            //$user_addresses  = $user_addresses_query->result();
            
            $query = $this->legacy_db->query('SELECT id FROM user_address where address="'.$params['address'].'" AND user_id="'.$params['user_id'].'"');
            if ($query->num_rows() > 0)
            {
                $res['status']      = 'Failed';
                $res['msg']         = 'This address already exists';
            }
            else
            {
                $this->legacy_db->insert('user_address', $params);
                $flag = $this->legacy_db->affected_rows();
                if($flag == '1')
                {
                    $res['status']      = 'SUCCESS';
                    $res['msg']         = '';
                }
                else
                {
                    $res['status']      = 'Failed';
                    $res['msg']         = 'Delete query not executed.';
                }
            }
            

            if($this->legacy_db->_error_message()){
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $res;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
}
?>
