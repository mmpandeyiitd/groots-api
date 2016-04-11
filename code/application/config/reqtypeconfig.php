<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Define an array to call the libraries according to the 
* response type
* 
*/

$config['reqTypeArray']   = array();
$arrIndex       = 0;


$config['reqTypeArray'][$arrIndex]['req_type']         = ($arrIndex+1);
$config['reqTypeArray'][$arrIndex]['libEngineName']    = 'MyEngine';
$config['reqTypeArray'][$arrIndex]['libMethodName']    = 'someFunction';

$arrIndex++;

$config['reqTypeArray'][$arrIndex]['req_type']         = ($arrIndex+1);
$config['reqTypeArray'][$arrIndex]['libEngineName']    = 'abc2';
$config['reqTypeArray'][$arrIndex]['libMethodName']    = 'pqr1';

$arrIndex++;

$config['reqTypeArray'][$arrIndex]['req_type']         = ($arrIndex+1);
$config['reqTypeArray'][$arrIndex]['libEngineName']    = 'abc3';
$config['reqTypeArray'][$arrIndex]['libMethodName']    = 'pqr1';

$arrIndex++;

$config['reqTypeArray'][$arrIndex]['req_type']         = ($arrIndex+1);
$config['reqTypeArray'][$arrIndex]['libEngineName']    = 'abc4';
$config['reqTypeArray'][$arrIndex]['libMethodName']    = 'pqr1';

$arrIndex++;

$config['reqTypeArray'][$arrIndex]['req_type']         = ($arrIndex+1);
$config['reqTypeArray'][$arrIndex]['libEngineName']    = 'abc5';
$config['reqTypeArray'][$arrIndex]['libMethodName']    = 'pqr1';

/* End of file reqtypeconfig.php */
/* Location: ./application/config/reqtypeconfig.php */