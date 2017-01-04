<?php
 if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class email {
 public function __construct() { 
 	require_once APPPATH.'third_party/aws/aws-autoloader.php'; 
 } 
}