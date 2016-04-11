<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pg_transaction {

    public function addPgRequest($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('pg_transaction_model');
            $CI->load->library('validation');
            $result = $CI->validation->pg_request_validation($params);
            if ($result['status'] == 1) {
                $data['order_id'] = $params['order_id'];
                $data['order_no'] = $params['order_no'];
                $data['pg_request'] = $params['request_data'];
                $res = $CI->pg_transaction_model->savePgRequest($data);
                if ($res) {
                    $result['status'] = "Success";
                    $result['msg'] = "PG request inserted successfully";
                    $result['id']=$res;
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "Failed to insert PG request ";
                }
            } else {
                $result['status'] = "Fail";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function updatePgResponse($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('pg_transaction_model');
            $CI->load->library('validation');
            $result = $CI->validation->pg_response_validation($params);
            if ($result['status'] == 1) {
                $data['id'] = $params['id'];
                $data['pg_response'] = $params['request_data'];
                $data['status'] = $params['status'];
                $data['reason'] = $params['reason'];
                $res = $CI->pg_transaction_model->updatePgRequest($data);
                if ($res) {
                    $result['status'] = "Success";
                    $result['msg'] = "PG request updated successfully";
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "Failed to updated PG response ";
                }
            } else {
                $result['status'] = "Fail";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

}
