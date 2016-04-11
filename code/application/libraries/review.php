<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class review {

    public function addReview($params) {

        try {
            $CI = & get_instance();
            $CI->load->model('review_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_review($params);
            if ($result['status']==1) {
                $data['name'] = $params['name'];
                $data['title_of_review'] = $params['title_of_review'];
                $data['review'] = $params['review'];
                $data['ip_address'] = $params['ip_address'];
                $data['status'] = 1;
                $res = $CI->review_model->saveReviewData($data);
                if ($res > 0) {
                    $result['status'] = "Success";
                    $result['msg'] = "Review has been inserted successfully";
                } else {
                    $result['status'] = "Fail";
                    $result['msg'] = "Fail to save data, Please try again later";
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
