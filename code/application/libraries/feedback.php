<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class feedback extends CI_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('feedback_model', 'feedback_model');
    }

	public function checkFeedbackStatus($params){
		$CI = & get_instance();
        $CI->load->library('validation');
        $CI->load->config('custom-config');
        $e = $CI->feedback_model->checkFeedbackStatus($params);
        $result = array();
        if($e == false || is_a($e, 'Exception') || !isset($e) || empty($e) || $e == null){
        	$result['status'] = 0;
            $result['msg'] = 'Failed To Find Data. Please Try Again';
            $result['error'] = is_a($e, 'Exception') ? $e->getMessage() : 'Cannot Find Error';
            return $result;
        }
        $order_feedback = array();
        $order_feedback['order_id'] = $e[0]->order_id;
        if($e[0]->feedback_status == 'Submitted'){
        $order_feedback['feedback_status'] = false;
        }
        else
            $order_feedback['feedback_status'] = true;
        $result['status'] = 1;
        $result['msg'] = 'Order Feedback';
        $responseHeader = array();
        $responseHeader['status'] = 0;
        $responseHeader['QTime'] = null;
        $responseHeader['params'] = null;
        $response = array();
        $response['numFound'] = count($order_feedback);
        $response['start'] = intval($params['page']);
        $response['docs'] = $order_feedback;
        $result['responseHeader'] = $responseHeader;
        $result['response'] = $response;
        return $result; 
	}

}
?>