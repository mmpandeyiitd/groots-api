<?php
 if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class log4php {
 public function __construct() { 
 	require_once APPPATH.'third_party/log4php/Logger.php'; 
 } 
}