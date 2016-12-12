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
            $result['errors'] = is_a($e, 'Exception') ? $e->getMessage() : 'Cannot Find Error';
            return $result;
        }
        $order_feedback = array();
        $order_feedback['orderId'] = $e[0]->order_id;
        $order_feedback['deliveryDate'] = $e[0]->delivery_date;
        if($e[0]->feedback_status == 'Submitted'){
            $order_feedback['feedbackStatus'] = false;
        }
        else{
            $order_feedback['feedbackStatus'] = true;
        }
        $result['status'] = 1;
        $result['msg'] = 'Order Feedback';
        $result['data'] = array();
        $responseHeader = array();
        $responseHeader['status'] = 0;
        $responseHeader['QTime'] = null;
        $responseHeader['params'] = null;
        $result['data']['responseHeader'] = $responseHeader;
        $response = array();
        $response['numFound'] = count($order_feedback);
        $response['start'] = intval($params['page']);
        $response['docs'] = array();
        array_push($response['docs'], $order_feedback);
        $result['data']['response'] = $response;
        return $result; 
	}

    public function submitFeedback($params, $logger){
        try{
            $CI = & get_instance();
            $CI->load->library('validation');
            $CI->load->config('custom-config');
            $result = $CI->validation->validate_feedback_data($params);
            if($result['status'] == 1){
                $e = $CI->feedback_model->setFeedbackStatus($params);
                if($e == false || is_a($e, 'Exception')) {
                    $result['status'] = 0;
                    $result['msg'] = 'Failed To Update Data. Please Try Again';
                    $result['errors'] = is_a($e, 'Exception') ? $e->getMessage() : 'Cannot Find Error';
                    return $result;
                }
                else{
                    $data = array();
                    if($params['rating'] == 5 && (!isset($params['feedback']) || empty($params['feedback']))){
                        $data = array(
                                'order_id' => $params['orderId'],
                                'rating' => $params['rating'],
                                'created_at' => date('Y-m-d'),
                                'updated_by' => $params['user_id']);
                    }
                    else{

                        foreach ($params['feedback'] as $key => $value) {
                            $temp = array();
                            $temp = array(
                                    'order_id' => $params['orderId'],
                                    'feedback_id' => $value['feedbackId'],
                                    'rating' => $params['rating'],
                                    'created_at' => date('Y-m-d'),
                                    'updated_by' => $params['user_id']);
                            array_push($data, $temp);
                        }

                    }
                    $logger->warn(var_dump($data));
                    //die('here');
                    $e = $this->feedback_model->insertFeedbackData($data, $params, $logger);
                    if($e == false || is_a($e, 'Exception')) {
                        $result['status'] = 0;
                        $result['msg'] = 'Failed To Insert Data. Please Try Again';
                        $result['errors'] = is_a($e, 'Exception') ? $e->getMessage() : 'Cannot Find Error';
                        return $result;
                    }
                }
                $result['status'] = 1;
                $result['msg'] = 'Feedback Submitted Successfully!';
                return $result;
            }
            else{
                return $result;
            }
        } catch(Exception $e){
            $result['status'] = 0;
            $result['msg'] = 'Failed To Insert Data. Please Try Again';
            $result['errors'] = $e->getMessage();
            return $result;
        }
    }

}
?>