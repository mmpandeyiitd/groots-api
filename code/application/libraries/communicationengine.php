<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class communicationengine {

   /**
     * This is handle email communication
     * @param unknown $params
     * @return multitype:string
	 */
    public function emailCommunication($data) { // SendGrid API - Mail Service
        //die(print_r($data));
        $CI = & get_instance();
        $CI->load->config('custom-config');
        $from_email = $CI->config->item('FROM_EMAIL');
        $from_name = $CI->config->item('FROM_NAME');
        $no_reply = $CI->config->item('NO_REPLY');
        //$filename_with_path=$data['path'];
        //$file_name="doc.pdf";
        $mailArray = array(
            'to' => array(
                '0' => array('email' => $data['email'])
            ),
            'from' => $from_email,
            'fromname' => $from_name,
            'subject' => $data['subject'],
            'html' => $data['body'],
            'text' => '',
            'replyto' => $no_reply  //,
            //'files' => array('0'=>array('name' =>$file_name,'path' =>$filename_with_path)),
        );

        $this->sendMailSes($mailArray);
        
        /*$url = $CI->config->item('SENDGRID_URL');
        $parameter =$CI->config->item('SENDGRID_PARAMETER');
        $user = $CI->config->item('SENDGRID_API_USERNAME');
        $pass = $CI->config->item('SENDGRID_API_PASSWORD');
       
        $params = array();
        $params['api_user'] = $user;
        $params['api_key'] = $pass;
        $i = 0;
        $json_string = array();
        foreach ($mailArray['to'] as $to) {
            if ($i == 0) {
                $params['to'] = $to['email'];
                $params['toname'] = $to['name'];
                $json_string['to'][] = $to['email'];
            } else {
                $json_string['to'][] = $to['email'];
            }
            $i++;
        }


        $params['from'] = $mailArray['from'];

        if ($mailArray['fromname'] && $mailArray['fromname'] != '') {
            $params['fromname'] = $mailArray['fromname'];
        }

        $params['subject'] = $mailArray['subject'];

        if ($mailArray['html'] && $mailArray['html'] != '') {
            $params['html'] = $mailArray['html'];
        }

        if ($mailArray['text'] && $mailArray['text'] != '') {
            $params['text'] = $mailArray['text'];
        }

        if ($mailArray['replyto'] && $mailArray['replyto'] != '') {
            $params['replyto'] = $mailArray['replyto'];
        }

        if (isset($mailArray['files'])) {
            foreach ($mailArray['files'] as $file) {
                $params['files[' . $file['name'] . ']'] = '@' . $file['path'];
            }
        }

        $params['x-smtpapi'] = json_encode($json_string);
        $request = $url . 'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);

        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);

        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);

        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        // obtain response
        $response = curl_exec($session);
        curl_close($session);

        // print everything out
        return $response;*/
    }

    /**
     * This is handel sms communication
     * @param unknown $params
     * @return multitype:string
     */
    public function smsCommunication($params) {
		$CI = & get_instance();
		$CI->load->config('custom-config');
		$url = $CI->config->item('SMSAPI_URL');
		
		$responseData = NULL ;
		$commaSeparatedMob = $params['mobile'];

		$queryString = $CI->config->item('SMSAPI_QUERYSRING');
		$queryString = str_replace("{MOBILE}",$commaSeparatedMob,$queryString);
		$queryString = str_replace("{MESSAGE}",urlencode($params['SMS']),$queryString);
                $url=$url.$queryString;
                //print_r($url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
		$result = curl_exec($ch);
		//print_r($result);
		if(curl_errno($ch)){
			$msg=$CI->config->item('SMS_CURL_ERROR');
			$msg=str_replace("XX",curl_error($ch),$msg);
			$responseData = array('status' => 0, 'message' => $msg, "mobile" => $commaSeparatedMob);
			//$responseData = array('status' => 0, 'message' => "SMS sent error [" . ' Curl error: ' . curl_error($ch).' ].', "mobile" => $param_mobile);
		} else {	
			$responseData = array('status' => 1, 'message' => "SMS Send Successfully", "mobile" => $commaSeparatedMob);
			
		}
		curl_close($ch);
		return $responseData;
    }

    public function sendMailSes($mailArray){
        //require_once( dirname(__FILE__) . '/../extensions/aws/aws-autoloader.php');
        $CI->load->library('email');
        $CI->load->config('custom-config');

        //$from = $mailArray['from'];
        //$replyto = $mailArray['replyto'];
        $subject = $mailArray['subject'];
        $text = $mailArray['text'];
        $html = $mailArray['html'];

        $i = 0;
        $recepientArr = array();
        foreach ($mailArray['to'] as $to)
        {
            if($to['email']=="grootsadmin@gmail.com")
            {
                continue;
            }
            if ($i == 0)
            {
                $params['to']        = $to['email'];
                //   $params['toname']    = $to['name'];
                $recepientArr[] = $to['email'];
            }
            else
            {
                $recepientArr[] = $to['email'];
            }
            $i++;
        }

        $region = $CI->config->item('SES-REGION');
        $key = $CI->config->item('AWS_KEY');
        $secret = $CI->config->item('AWS_SECRET_KEY');
        $from = $CI->config->item('FROM_EMIL');

        $client = SesClient::factory(array(
            'version'=> 'latest',
            'region' => $region,
            'credentials' => array(
                'key' => $key,
                'secret'  => $secret,
            )

        ));


        /*foreach ($recepientArr as $to){
            $client->verifyEmailIdentity(['EmailAddress'=>$to]);
        }*/

        $request = array();
        $request['Source'] = $from;
        $request['Destination']['ToAddresses'] = $recepientArr;
        $request['Message']['Subject']['Data'] = $subject;
        $request['Message']['Body']['Html']['Data'] = $html;

        try {
            $result = $client->sendEmail($request);
            $messageId = $result->get('MessageId');
            echo("Email sent! Message ID: $messageId"."\n");

        } catch (Exception $e) {
            echo("The email was not sent. Error message: ");
            echo($e->getMessage()."\n");
        }

    }
}