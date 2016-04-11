<?php

class order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->database('group1', TRUE);
        $this->legacy_db    = $this->load->database('group1', true);
    }

    /**
     * Used to save user data as a new record
     * 
     * @param type $params
     * @return boolean
     */
    public function saveOrderHeaderAndLinesData($data) {
        try {
            $this->legacy_db->trans_begin();
            $sql = 'INSERT INTO order_header (order_number,created_date,payment_status,source_url,billing_name,billing_email,billing_phone,billing_address,billing_city,billing_state,billing_pincode,shipping_name,shipping_email,shipping_phone,shipping_address,shipping_state,shipping_city,shipping_pincode,total,total_payable_amount,discount_amt,status,order_source,source_type,source_id,source_name,order_type,utm_source,coupon_code,shipping_charges,user_id) VALUES ' . $data['header'];
            $this->legacy_db->query($sql);
            $id = $this->legacy_db->insert_id();
            $data['line'] = str_replace("##ORDERID##", $id, $data['line']);
            $sql = 'INSERT INTO order_line (order_id,subscribed_product_id,base_product_id,store_id,store_name,store_email,store_front_id,store_front_name,seller_name,seller_phone,seller_address,seller_state,seller_city,colour,size,product_name,product_qty,unit_price,price) VALUES ' . $data['line'];
            $this->legacy_db->query($sql);
            if ($this->legacy_db->trans_status() === FALSE) {
                $this->legacy_db->trans_rollback();
                return False;
            } else {
                $this->legacy_db->trans_commit();
                return $id;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     * Used to save user data as a new record
     * 
     * @param type $params
     * @return boolean
     */
    public function saveOrderProductData($params) {
        try {
            if ($this->legacy_db->insert('order_line', $params)) {
                return $this->legacy_db->insert_id();
            } else {
                $dberrorObjs = new stdClass();
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $thislegacy_dbdb->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function getMaxOrderId() {
        try {
            $maxid = 0;
            $row = $this->legacy_db->select_max('order_id')->from("order_header")->get()->row();
            if ($row) {
                $maxid = $row->order_id;
            }
            return $maxid;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function deleteCartByUserIdAndCartId($user_id, $cartid) {
        try {
            $this->legacy_db->query('DELETE FROM mycart where user_id="' . $user_id . '" and  id="' . $cartid . '"');
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $current_cart_data;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function saveOrderLogs($params) {
        try {
            if ($this->legacy_db->insert('order_logs', $params)) {
                return $this->legacy_db->insert_id();
            } else {
                $dberrorObjs = new stdClass();
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function updateOrderLogs($data) {
        try {
            $this->legacy_db->where('id', $data['id']);
            $this->legacy_db->update('order_logs', $data);

            return True;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function checkUserAddressExist($data) {
        try {
            $user_query = $this->legacy_db->query("SELECT id FROM user_address where type='" . $data['type'] . "' and address='" . $data['address'] . "' and user_id=" . $data['user_id']);
            if ($user_query->num_rows() > 0) {
                $row = $user_query->row_array();
                return $row;
            } else {
                return FALSE;
            }
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function saveUserAddressData($params) {
        try {
            $addressexist = $this->checkUserAddressExist($params);
            if (!$addressexist) {
                if ($this->legacy_db->insert('user_address', $params)) {
                    return $this->legacy_db->insert_id();
                } else {
                    $dberrorObjs = new stdClass();
                    $dberrorObjs->error_code = $this->legacy_db->_error_number();
                    $dberrorObjs->error_message = $this->legacy_db->_error_message();
                    $dberrorObjs->error_query = $thislegacy_dbdb->last_query();
                    $dberrorObjs->error_time = date("Y-m-d H:i:s");
                    $this->legacy_db->insert('dberror', $dberrorObjs);
                    return FALSE;
                }
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

///////////// vikas //////////
<<<<<<< HEAD
    public function getDataByOrderId($data){
       try{
            $get_order_list_query = $this->legacy_db->query('SELECT oh.*,ol.* FROM order_header oh INNER JOIN order_line ol ON ol.order_id = oh.order_id WHERE oh.user_id ="'.$data['user_id'].'" order by id desc');

            $get_order_list  = $get_order_list_query->result();
            //print_r($get_order_list);

            if($this->legacy_db->_error_message()){
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $get_order_list;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function getImgByBaseId($base_id){
       try{
            $this->db = $this->load->database('default', true);
            $get_product_img_query = $this->db->query('SELECT thumb_url from media WHERE base_product_id ="'.$base_id.'" order by media_id limit 0,1');

            $get_product_img  = $get_product_img_query->result();
            //print_r($get_order_list);
=======
    public function getDataByOrderId($data) {
        try {
            $get_order_list_query = $this->db->query('');
            $get_order_list = $get_order_list_query->result();
>>>>>>> 2258fa3ed3408e3a17c8ba3724ccedb3ae4b4b13

            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return FALSE;
<<<<<<< HEAD
            } else{
                return $get_product_img;
=======
            } else {
                return $current_cart_data;
>>>>>>> 2258fa3ed3408e3a17c8ba3724ccedb3ae4b4b13
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

///////////// vikas end //////////
}

?>