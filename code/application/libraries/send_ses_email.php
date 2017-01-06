<?php
/**
 * Created by PhpStorm.
 * User: manmohan
 * Date: 5/1/17
 * Time: 9:53 PM
 */
use Aws\Ses\SesClient;
class send_ses_email
{

    public $sesClient;
    public $ci;

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->config('aws_sdk');
        $this->ci->load->config('custom-config');
        $this->sesClient = SesClient::factory(array(
            //'version'=> 'latest',
            'region' => $this->ci->config->item('region'),
            'credentials' => array(
                'key' => $this->ci->config->item('aws_access_key'),
                'secret'  => $this->ci->config->item('aws_secret_key'),
            )

        )); //Change this to instantiate the module you want. Look at the documentation to find out what parameters you need.

    }

    public function sendMailSes($mailArray){

        $subject = $mailArray['subject'];
        //$text = $mailArray['text'];
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


        $from = $this->ci->config->item('FROM_EMIL');

        $request = array();
        $request['Source'] = $from;
        $request['Destination']['ToAddresses'] = $recepientArr;
        $request['Message']['Subject']['Data'] = $subject;
        $request['Message']['Body']['Html']['Data'] = $html;

        try {
            $result = $this->sesClient->sendEmail($request);
            $messageId = $result->get('MessageId');
            echo("Email sent! Message ID: $messageId"."\n");

        } catch (Exception $e) {
            echo("The email was not sent. Error message: ");
            echo($e->getMessage()."\n");
        }

    }
}