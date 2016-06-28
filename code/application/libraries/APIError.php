<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class APIError extends Exception  {
	/**
	 * The error codes
	 * @var Array
	 */
	private $errorCodes;

	/**
	 * Constructor
	 */
	public function __construct($errorCodes = 0)
	{
		parent::__construct();
		$this->errorCodes = $errorCodes;
	}

	/**
	 * Returns the Error Messages
	 * @return Array of Error Messages
	 */
	public function getError() {
		$CI =& get_instance();
		$CI->db->select('error_code, error_message');
		$CI->db->from('error_codes');
		$CI->db->where_in('error_code', $this->errorCodes);
		$query = $CI->db->get();
		$error = array();
		$errorObject = new stdClass();
		foreach ($query->result() as $row) {
			//$error[$row->error_code] = $row->error_message;
			$errorObject->error_code = $row->error_code;
			$errorObject->error_message = $row->error_message;
		}
        //echo $CI->db->last_query();
		return $errorObject;
	}
}