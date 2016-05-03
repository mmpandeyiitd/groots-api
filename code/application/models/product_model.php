<?php

class product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('default', TRUE);
    }

    /**
     *
     * @param array $params
     * @param type $cols
     * @param int $limit 'optional'
     * @param int $offset 'optional'
     * @return type
     */
    Public function getProductData($params, $cols = "", $limit = 1, $offset = 0) {
        $this->load->database('default', TRUE);
        $cond = array();
        $column = NULL;
        foreach ($params as $key => $value) {
            $cond[$key] = $value;
        }
        if (is_array($cols) || is_object($cols)) {
            foreach ($cols as $key => $value) {
                $column.=$value . ",";
            }
            $column = trim($column, ",");
        } else if (is_string($cols)) {
            $column = " " . $cols . " ";
        } else {
            $column = " * ";
        }
        $this->db->select($column);
        if ($limit == 1) {
            $query = $this->db->get_where('view_getproductdetail', $cond, $limit, $offset);
            $data = $query->row_array();
        } else {
            if ($limit == "-1") {
                $query = $this->db->get_where('view_getproductdetail', $cond);
            } else {
                $query = $this->db->get_where('view_getproductdetail', $cond, $limit, $offset);
            }
            $data = $query->result_array();
        }
        return $data;
    }

    public function updateProductQuantity($data) {
        try {
            $this->load->database('default', TRUE);
            $this->db->update_batch('subscribed_product', $data, 'subscribed_product_id');
            return True;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function insertSolrBackLog($params) {
        try {
            $this->load->database('default', TRUE);
            foreach ($params as $data) {
                if ($data['quantity'] <= 0) {
                    $query = $this->db->query('SELECT subscribed_product_id  FROM solr_back_log where subscribed_product_id="' . $data['subscribed_product_id'] . '"');
                    $data_exists = $query->num_rows();
                    if ($data_exists <= 0) {
                        $solrdata['subscribed_product_id'] = $data['subscribed_product_id'];
                        if ($this->db->insert('solr_back_log', $solrdata)) {
                            //print_r($this->db->last_query());
                            //return True;
                        } else {
                            $dberrorObjs = new stdClass();
                            $dberrorObjs->error_code = $this->db->_error_number();
                            $dberrorObjs->error_message = $this->db->_error_message();
                            $dberrorObjs->error_query = $this->db->last_query();
                            $dberrorObjs->error_time = date("Y-m-d H:i:s");
                            $this->db->insert('dberror', $dberrorObjs);
                        }
                    }
                }
            }
            return True;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function getTaxByBaseProductId($id) {
        try {
            $user_query = $this->db->query("SELECT pcm.base_product_id,pcm.category_id,c.is_mega_category,c.category_name,c.cat_tax_per,c.level FROM product_tax_master_mapping pcm INNER JOIN tax_master c ON pcm.category_id = c.category_id WHERE pcm.base_product_id=" . $id . " order by level desc");
            if ($user_query->num_rows() > 0) {
                $row = $user_query->result();
                return $row;
            } else {
                return FALSE;
            }
            if ($this->db->_error_message()) {
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
    
    Public function getPriceData($params) {
        try {
            $user_query = $this->db->query("SELECT effective_price, discount_per FROM retailer_product_quotation WHERE subscribed_product_id=" . $params['subscribed_product_id'] . " AND retailer_id = ". $params['retailer_id']);
            if ($user_query->num_rows() > 0) {
                $row = $user_query->result();
                return $row;
            } else {
                $user_query = $this->db->query("SELECT effective_price, discount_per FROM retailer_product_quotation WHERE retailer_id = ". $params['retailer_id']);
                    if ($user_query->num_rows() > 0) 
                    {
                        $data['status'] = 0;
                        return $data;
                    }
                    else
                    {
                        return FALSE;
                    }
            }
            if ($this->db->_error_message()) {
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