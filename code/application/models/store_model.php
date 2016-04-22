<?php

class store_model extends CI_Model {

    public function __construct() {
        parent::__construct();
       $this->db2= $this->load->database('group1', true);
    }

    public function getStoreproductDetails($params) {
       try{
            $store_product_query = $this->db2->query('SELECT sp.subscribed_product_id, m.media_url AS image, pcm.category_id FROM subscribed_product AS sp LEFT JOIN media AS m ON sp.base_product_id = m.base_product_id LEFT JOIN product_category_mapping AS pcm ON pcm.base_product_id = sp.base_product_id WHERE sp.store_id = "'.$params['store_id']. '" GROUP BY sp.subscribed_product_id ORDER BY sp.subscribed_product_id ASC');

            $store_product = $store_product_query->result();
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $store_product;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function getStorelookbookDetails($params) {
       try{
            $store_lookbook_query = $this->db2->query("SELECT image_main_url, pdf_url, desciption FROM lookbook WHERE TYPE = 'lookbook' AND store_id =".$params['store_id']."");
            $store_lookbook = $store_lookbook_query->result();
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $store_lookbook;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function getStoreimageDetails($params) {
       try{
            $store_image_query = $this->db2->query("SELECT image_main_url, desciption FROM lookbook WHERE type = 'photo' AND store_id =".$params['store_id']."");
            $store_image = $store_image_query->result();
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $store_image;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function getStorepressDetails($params) {
       try{
            $store_press_query = $this->db2->query("SELECT image_main_url, description FROM press_release WHERE brand_id ='".$params['store_id']."'");
            $store_press = $store_press_query->result();
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $store_press;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }
    
    public function getStorebasicDetails($params) {
       try{
            $store_details_query = $this->db2->query('SELECT store_name,store_details,tags,store_logo_url as logo,image_url as banner_image FROM store WHERE store_id ="'.$params['store_id'].'"');
            $store_details  = $store_details_query->result();
            if($this->db2->_error_message()){
                $dberrorObjs->error_code = $this->db2->_error_number();
                $dberrorObjs->error_message = $this->db2->_error_message();
                $dberrorObjs->error_query = $this->db2->last_query();
                $dberrorObjs->error_time = date("Y-m-d H:i:s");
                $this->db2->insert('dberror', $dberrorObjs);
                return FALSE;
            } else{
                return $store_details;
            }
        }  catch (Exception $e){
           return FALSE;
        }
    }

}

?>