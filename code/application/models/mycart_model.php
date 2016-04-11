<?php

class mycart_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->dbmycart=  $this->load->database('group2',true);
    }

    /**
     * Used to save user data as a new record
     * 
     * @param type $params
     * @return boolean
     */
    public function saveCartData($params) {
        try {
            $this->dbmycart->set('modified_at', 'NOW()', FALSE);
            if ($this->dbmycart->insert('mycart', $params)) {
                //return $this->dbmycart->insert_id();
                return $this->dbmycart->affected_rows();
            } else {
                $dberrorObjs = new stdClass();
                $dberrorObjs->error_code = $this->dbmycart->_error_number();
                $dberrorObjs->error_message = $this->dbmycart->_error_message();
                $dberrorObjs->error_query = $this->dbmycart->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dbmycart->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    /**
     * Used update record of a Used
     * 
     * @param Object $params
     * @return boolean
     */
    public function updateCartData($params){
       try{
            $this->dbmycart->set('modified_at', 'NOW()', FALSE);
            $this->dbmycart->where('id',  $params['id']);
            $this->dbmycart->update('mycart',  $params);
            $affected_row = $this->dbmycart->affected_rows();
           //print_r($this->dbmycart->last_query());
            if($this->dbmycart->_error_message()){
                $dberrorObjs->error_code = $this->dbmycart->_error_number();
                $dberrorObjs->error_message = $this->dbmycart->_error_message();
                $dberrorObjs->error_query = $this->dbmycart->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dbmycart->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $affected_row;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function checkUserExists($usr_id){
       try{
            $user_id_query = $this->dbmycart->query('SELECT id,cart_data FROM mycart where user_id="'.$usr_id.'"');
            $user_id_exists = $user_id_query->num_rows();
            
            if($this->dbmycart->_error_message()){
                $dberrorObjs->error_code = $this->dbmycart->_error_number();
                $dberrorObjs->error_message = $this->dbmycart->_error_message();
                $dberrorObjs->error_query = $this->dbmycart->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dbmycart->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $user_id_exists;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function checkSessionExists($session_id){
       try{
            $session_id_query = $this->dbmycart->query('SELECT id,cart_data FROM mycart where session_id="'.$session_id.'"');
            $session_id_exists = $session_id_query->num_rows();
            
            if($this->db->_error_message()){
                $dberrorObjs->error_code = $this->dbmycart->_error_number();
                $dberrorObjs->error_message = $this->dbmycart->_error_message();
                $dberrorObjs->error_query = $this->dbmycart->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dbmycart->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $session_id_exists;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function getDataByUserId($user_id){
       try{
            $user_id_query = $this->dbmycart->query('SELECT id,cart_data FROM mycart where user_id="'.$user_id.'"');
            $current_cart_data  = $user_id_query->result();

            if($this->dbmycart->_error_message()){
                $dberrorObjs->error_code = $this->dbmycart->_error_number();
                $dberrorObjs->error_message = $this->dbmycart->_error_message();
                $dberrorObjs->error_query = $this->dbmycart->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dbmycart->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $current_cart_data;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function getDataBySessionId($session_id){
       try{
            $session_id_query = $this->dbmycart->query('SELECT id,cart_data FROM mycart where session_id="'.$session_id.'"');
            $current_cart_data  = $session_id_query->result();

            if($this->dbmycart->_error_message()){
                $dberrorObjs->error_code = $this->dbmycart->_error_number();
                $dberrorObjs->error_message = $this->dbmycart->_error_message();
                $dberrorObjs->error_query = $this->dbmycart->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dbmycart->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $current_cart_data;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
     public function deleteCartByCartId($cart_id) {
        try {
            $this->dbmycart->query('DELETE FROM mycart where id="' . $cart_id . '"');
            if ($this->dbmycart->_error_message()) {
                $dberrorObjs->error_code = $this->dbmycart->_error_number();
                $dberrorObjs->error_message = $this->dbmycart->_error_message();
                $dberrorObjs->error_query = $this->dbmycart->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->dbmycart->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return TRUE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    
}
?>
