<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 * Formats a number in Indian Rupee Format
 *
 * @param Int $num  'Number to be formatted in Indian style'
 * @param Boolean $decimal_flag  Optional 'Decimal part is required or not. Default TRUE'
 * @return Int|Float
 */
if (!function_exists('formatInIndianStyle')) {

    function formatInIndianStyle($num, $decimal_flag = 1) {
        $pos = strpos((string) $num, ".");
        if ($pos === false) {
            $decimalpart = "00";
        } else {
            $decimalpart = substr($num, $pos + 1, 2);
            $num = substr($num, 0, $pos);
        }
        if (strlen($num) > 3 & strlen($num) <= 12) {
            $last3digits = substr($num, -3);
            $numexceptlastdigits = substr($num, 0, -3);
            $formatted = makeComma($numexceptlastdigits);
            if ($decimal_flag) {
                $stringtoreturn = $formatted . "," . $last3digits . "." . $decimalpart;
            } else {
                $stringtoreturn = $formatted . "," . $last3digits;
            }
            //$stringtoreturn = $formatted.",".$last3digits;
        } elseif (strlen($num) <= 3) {
            $stringtoreturn = $num . "." . $decimalpart;
            //$stringtoreturn = $num ;
        } elseif (strlen($num) > 12) {
            $stringtoreturn = number_format($num);
        }
        if (substr($stringtoreturn, 0, 2) == "-,") {
            $stringtoreturn = "-" . substr($stringtoreturn, 2);
        }

        return $stringtoreturn;
    }

}
/**
 * 
 * Used in formatInIndianStyle to conver a number in Indian Rupee Format
 *
 * @param Int $input  'Number to be formatted in Indian style'
 * @return String
 */
if (!function_exists('makeComma')) {

    function makeComma($input) {
        // This function is written by some anonymous person - I got it from Google
        if (strlen($input) <= 2) {
            return $input;
        }
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = makeComma($length) . "," . substr($input, -2);
        return $formatted_input;
    }

}
/**
 * 
 * Used to replace Special characters with Hyphen '-' in a String
 *
 * @param String $str  'String to be replaced'
 * @return String
 */
if (!function_exists('cleanString')) {

    function cleanString($str) {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', rtrim($str, ' '));
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -*?&%$#@!()]+/", '-', $clean);
        return $clean;
    }

}
/**
 * 
 * Validates a date
 *
 * @param string $date  'Date to be validated '
 * @param string $format Optional 'Format of date '
 * @return boolean
 */
if (!function_exists('validateDate')) {

    function validateDate($date, $format = 'Y-m-d H:i:s') {
        $version = explode('.', phpversion());
        if (((int) $version[0] >= 5 && (int) $version[1] >= 2 && (int) $version[2] > 17)) {
            $d = DateTime::createFromFormat($format, $date);
        } else {
            $d = new DateTime(date($format, strtotime($date)));
        }
        return $d && $d->format($format) == $date;
    }

}
/**
 * 
 * Generates Unique Lead Code
 *
 * @return String
 */
if (!function_exists('createRandomLeadCode')) {

    function createRandomLeadCode($loop) {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= $loop) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

}
/**
 * 
 * Gives difference between 2 dates
 * @param String $date1 'Date From '
 * @param String $date2 'Date To '
 * @param Char $format  'Difference in Minute/Hour,Default minute '
 * @return Int
 */
if (!function_exists('datetimeDiff')) {

    function datetimeDiff($date1 , $date2 , $format = 'm' ) {
        
        $datetime1 = strtotime($date1);
        $datetime2 = strtotime($date2);
        $interval  = abs($datetime2 - $datetime1);
        $minutes   = round($interval / 60);
        if($format != 'm'){
          $minutes = round($minutes / 60) ;  
        }
        return $minutes ;
    }

}

if(!function_exists("checkmulti")){
    function checkmulti($arr){
        if(is_array($arr)){
            return (count($array) != count($array, 1));
        }
        return NULL;
    }
}
 
