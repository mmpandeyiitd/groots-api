<?php

class order_model extends CI_Model {

    public $logger;
    public function __construct() {
        parent::__construct();
        // $this->load->database('group1', TRUE);
        $this->legacy_db = $this->load->database('group2', true);
        Logger::configure( dirname(__FILE__) . '/../third_party/log4php.xml');
        $logger = Logger::getLogger("main");
    }

    /**
     * Used to save user data as a new record
     * 
     * @param type $params
     * @return boolean
     */
    public function saveOrderHeaderAndLinesData($data) {
        try {
            // $logger = Logger::getLogger("main");
            // $logger->warn(print_r($data));
            $this->legacy_db->trans_begin();
            $sql = 'INSERT INTO order_header (order_number,created_date,payment_status,billing_name,billing_email,billing_phone,billing_address,billing_city,billing_state,billing_pincode,shipping_name,shipping_email,shipping_phone,shipping_address,shipping_state,shipping_city,shipping_pincode,total,total_payable_amount,discount_amt,status,order_type,coupon_code,shipping_charges,tax,timestamp,user_id,delivery_date,user_comment,invoice_number, warehouse_id) VALUES ' . $data['header'];
            //die($sql);
            $this->legacy_db->query($sql);
            $id = $this->legacy_db->insert_id();
            $data['line'] = str_replace("##ORDERID##", $id, $data['line']);
            $sql = 'INSERT INTO order_line (order_id,subscribed_product_id,base_product_id,store_id,store_name,store_email,seller_name,seller_phone,seller_address,seller_state,seller_city,colour,size,grade,pack_size,pack_unit,diameter,product_name,product_qty,delivered_qty,unit_price,price,tax,shipping_charges,created_date,weight,weight_unit,length,length_unit,category_name) VALUES ' . $data['line'];
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

    public function deleteCartByUserIdAndCartId($cartid) {
        try {
            $this->legacy_db->query('DELETE FROM mycart where id="' . $cartid . '"');
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return TRUE;
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
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return True;
            }
            //return True;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function updateOrder($data) {
        try {
            $this->legacy_db->where('order_id', $data['order_id']);
            $this->legacy_db->update('order_header', $data);
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return True;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function updateorderheader($data) {
        try {
            $this->legacy_db->where('order_id', $data['order_id']);
            $this->legacy_db->update('order_header', $data);
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return True;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function checkUserAddressExist($data) {
        try {
            $user_query = $this->legacy_db->query("SELECT id FROM user_address where address='" . $data['address'] . "' and user_id=" . $data['user_id']);
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
            return True;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function getDataByOrderId($getparams) {
        try {
            $get_order_list_query = $this->legacy_db->query('SELECT oh.*,ol.* FROM order_header oh INNER JOIN order_line ol ON ol.order_id = oh.order_id WHERE oh.user_id ="' . $getparams['user_id'] . '" order by id desc');
            $get_order_list = $get_order_list_query->result();

            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $get_order_list;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function getDataByOrderId_details($getparams) {
        try {
            $get_order_list_query = $this->legacy_db->query('SELECT oh.*,ol.* FROM order_header oh INNER JOIN order_line ol ON ol.order_id = oh.order_id WHERE oh.user_id ="' . $getparams['user_id'] . '" AND oh.order_id = "' . $getparams['order_id'] . '"');
            $get_order_list = $get_order_list_query->result();

            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $get_order_list;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function getDataByOrderId_trackingdetails($getparams) {
        try {
            $get_order_list_query = $this->legacy_db->query('SELECT oh.order_id,oh.timestamp,oh.status, ol.store_id, ol.store_name, ol.product_name, ol.subscribed_product_id, ol.product_qty, ps.qty, ps.track_id, ps.status FROM order_header oh INNER JOIN order_line ol ON ol.order_id = oh.order_id INNER JOIN partial_shipment ps on ps.order_id = oh.order_id WHERE oh.user_id ="' . $getparams['user_id'] . '" AND oh.order_id = "' . $getparams['order_id'] . '"');
            $get_order_list = $get_order_list_query->result();

            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $get_order_list;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function getImgByBaseId($base_id) {
        try {
            $this->db = $this->load->database('default', true);
            $get_product_img_query = $this->db->query('SELECT thumb_url from media WHERE base_product_id ="' . $base_id . '" order by media_id limit 0,1');

            $get_product_img = $get_product_img_query->result();
            //print_r($get_order_list);

            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $get_product_img;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

///////////// vikas end //////////

    public function getOrderPaymentData($params){
        try{
            $sql = 'select * from order_payment_view where retailer_id = '.$params['user_id'].' order by date desc , order_id desc limit '.$params['start'].', '.$params['rows'];
            //echo $sql; die;
            $order_payment = $this->legacy_db->query($sql);
            $order_payment = $order_payment->result();
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            } else {
                return $order_payment;
            }
        } catch (Exception $e){
            return False;
        }
    }

    // public function getLimitedDataByOrderId($orderIds) {
    //     try {
    //         if(!isset($orderIds) && empty($orderIds))
    //             return 1;
    //         $ids = implode(',', $orderIds);
    //         $sql = 'select order_id as orderId, order_number as orderNumber, order_type as orderType, status, payment_status as paymentStatus, delivery_date as deliveryDate, total_payable_amount as totalPayableAmount from order_header where order_id in ('.$ids.') order by delivery_date desc, order_id desc';
    //         $get_order_list_query = $this->legacy_db->query($sql);
    //         $get_order_list = $get_order_list_query->result();
    //         if ($this->db->_error_message()) {
    //             $dberrorObjs->error_code = $this->db->_error_number();
    //             $dberrorObjs->error_message = $this->db->_error_message();
    //             $dberrorObjs->error_query = $this->db->last_query();
    //             $dberrorObjs->error_time = date("Y-m-d H:i:s");
    //             $this->db->insert('dberror', $dberrorObjs);
    //             return FALSE;
    //         } else {
    //             return $get_order_list;
    //         }
    //     } catch (Exception $e) {
    //         return FALSE;
    //     }
    // }

    // public function getRetailerPayements($paymentIds){
    //     try{
    //         if(empty($paymentIds))
    //             return 1;
    //         else{
    //             $ids = implode(',', $paymentIds);
    //             $sql = 'select id ,paid_amount as amountPaid, payment_type as modeOfPayment, cheque_status as chequeStatus, cheque_no as referenceNo, status , date from retailer_payments where id in ('.$ids.') order by date desc';
    //             echo $sql;die;
    //             $sql = $this->legacy_db->query($sql);
    //             $result = $sql->result();
    //             if ($this->legacy_db->_error_message()) {
    //                 $dberrorObjs->error_code = $this->legacy_db->_error_number();
    //                 $dberrorObjs->error_message = $this->legcy_db->_error_message();
    //                 $dberrorObjs->error_query = $this->legacy_db->last_query();
    //                 $dberrorObjs->error_time = date("Y-m-d H:i:s");
    //                 $this->db->insert('dberror', $dberrorObjs);
    //                 return new Exception('Found Error : ' . $dberrorObjs->error_message);
    //             } else {
    //                 return $result;
    //             }
    //         }

    //     } catch (Exception $e) {
    //         return FALSE;
    //     }

    // }

    public function getOrderDetails($params) {
        try {
            $sql = 'select oh.order_id , oh.created_date, oh.payment_method, oh.shipping_charges, oh.total, oh.total_payable_amount, oh.status, oh.delivery_date, oh.user_comment, oh.warehouse_id, ol.subscribed_product_id, ol.base_product_id, ol.product_qty, ol.delivered_qty, ol.price, ol.unit_price, bp.pack_size, bp.pack_unit, bp.title, bp.pack_size_in_gm, wa.name from groots_orders.order_header as oh inner join groots_orders.order_line as ol on ol.order_id = oh.order_id left join cb_dev_groots.base_product as bp on ol.base_product_id = bp.base_product_id left join cb_dev_groots.warehouses as wa on oh.warehouse_id = wa.id where oh.user_id = ' . $params['user_id'] . ' and oh.order_id = ' . $params['order_id'] . ' order by oh.order_id limit ' . $params['start'] . ', ' . $params['rows'];
            $getOrderByOrderId = $this->legacy_db->query($sql);
            $getCurrentOrderList = $getOrderByOrderId->result();
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return FALSE;
            } else {
                return $getCurrentOrderList;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function getProductIds($orderId) {
        try {
            $sql = 'select subscribed_product_id from groots_orders.order_line where order_id = ' . $orderId;
            $query = $this->legacy_db->query($sql);
            $productId = $query->result();
            if ($this->db->_error_message()) {
                $dberrorObjs->error_code = $this->db->_error_number();
                $dberrorObjs->error_message = $this->db->_error_message();
                $dberrorObjs->error_query = $this->db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            } else {
                return $productId;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function updateOrderLine($where, $data) {
        try {
            $this->legacy_db->where($where);
            $this->legacy_db->update('order_line', $data);
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            } else {
                return True;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function insertOrderLine($data) {
        try {
            $this->legacy_db->insert('order_line', $data);
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            } else {
                return True;
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function deleteOrderLine($orderId, $productIds, $count) {
        try {
            if ($count != 1) {
                $sql = 'delete from  groots_orders.order_line where order_id = ' . $orderId . ' and subscribed_product_id in (' . $productIds . ')';
            } else {
                $sql = 'delete from  groots_orders.order_line where order_id = ' . $orderId . ' and subscribed_product_id = ' . $productIds;
            }
            $query = $this->legacy_db->query($sql);
            if ($this->legacy_db->_error_message()) {
                $dberrorObjs->error_code = $this->legacy_db->_error_number();
                $dberrorObjs->error_message = $this->legacy_db->_error_message();
                $dberrorObjs->error_query = $this->legacy_db->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->legacy_db->insert('dberror', $dberrorObjs);
                return new Exception('Found Error : ' . $dberrorObjs->error_message);
            } else {
                return True;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }

}


?>
