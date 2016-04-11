<?php

class category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('default', TRUE); 
    }

    
    Public function getCategoryData($params,$cols = "",$limit = 1,$offset = 0)
    {
        
        $cond = array();
        $column = NULL ;
        foreach($params as $key=>$value){
            $cond[$key] = $value;
        }
        if(is_array($cols) || is_object($cols)){
           foreach($cols as $key=>$value){
               $column.=$value.",";
           }
           $column = trim($column,",");
        }else if(is_string($cols)){
            $column = " ".$cols." ";
        }else{
            $column = " * ";
        }
        $this->db->select($column);
        if($limit == 1){
            $query = $this->db->get_where('category', $cond, $limit, $offset);
            $data=$query->row_array();
        }else{
            if($limit == "-1"){
                $query = $this->db->get_where('category', $cond);
            }else{
                $query = $this->db->get_where('category', $cond, $limit, $offset);
            }
            $data=$query->result_array();
        }
        return $data;
    }

}

?>