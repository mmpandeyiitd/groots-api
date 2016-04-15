<?php

class apiauthcheck_model extends CI_Model {

    protected $authToken_new;
    public function __construct() {
        parent::__construct();
        //$this->load->database('group1',true);
        $this->legacy_db = $this->load->database('group1', true);
    }

    protected function checkAuthToken($tokenval = '') {
        if (empty($tokenval) || $tokenval == NULL) {
            return false;
        }
        $token_array = $this->legacy_db->query("SELECT * FROM auth_tokens WHERE token = '" . $tokenval . "'");
        $token = $token_array->result();
        if (count($token)) {
            foreach ($token as $value) {
                $token = $value->token;
                $expiry_min_array = $this->legacy_db->query("SELECT auth_expirytime FROM api_configs WHERE status = 1");
                $expiry_min = $expiry_min_array->result();
                $expiry_min = $expiry_min[0]->auth_expirytime;
                $CI = & get_instance();
                $CI->load->helper('common_helper');
                $time_diff = datetimeDiff($value->last_updated_on, date("Y-m-d H:i:s"));
                if ($time_diff < $expiry_min) {
                    $token = $this->saveAuthToken($value->user_id);
                    return $token;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    protected function getAuthToken() {
        return isset($this->headers['AUTH_TOKEN']) ? $this->headers['AUTH_TOKEN'] : false;
    }

    protected function resetAuthToken() {
        return md5(time());
        //return "4a6b1e610e81fa19c76a557049e9fa19";
    }

    function setHeader($header = '', $value = '') {
        if (empty($header) || $header == NULL) {
            return false;
        }
        if ($header === 'AUTH_TOKEN') {
            if ($value == '') {
                $this->authToken_new = $this->resetAuthToken();
                $this->output->set_header($header.':'.$this->authToken_new);
            } else {
                $this->output->set_header($header.':'.$value);
            }
        } else {
            $this->output->set_header($header.':'.$value);
        }
        return true;
    }

    protected function getHeader($header = '') {
        if (!array_key_exists($header, $this->input->request_headers())) {
            return false;
        }
        $req_header = $this->input->request_headers();
        return $req_header[$header];
    }

    protected function getAppCheck() {
        $headers = $_SERVER;
        echo json_encode($headers);
        exit;
    }

    public function saveAuthToken($user_id) {
        $auth_token = '';
        $token_array = $this->legacy_db->query("SELECT * FROM auth_tokens WHERE user_id = '" . $user_id . "'");
        $token = $token_array->result(); 
        $token = $token;
        if (count($token)) {
            foreach ($token as $value) {
                $auth_token = $value->token;
                $this->legacy_db->query("UPDATE auth_tokens SET last_updated_on = '".date("Y-m-d H:i:s")."' WHERE user_id = " . $user_id . "");
            }
        } else {
            $this->setHeader('AUTH_TOKEN');
            $added_on = date("Y-m-d H:i:s");
            $last_updated_on = date("Y-m-d H:i:s");
            $token = $this->authToken_new;
            $user_id = $user_id;
            $this->legacy_db->query("INSERT INTO auth_tokens (token, user_id, added_on, last_updated_on) VALUES ('".$token."','".$user_id."','".$added_on."','".$last_updated_on."')");
            
            $auth_token = $token;
        }
        return $auth_token;
    }

    public function getUserIdbyToken($tokenval = '') {
        if (empty($tokenval) || $tokenval == NULL) {
            return false;
        }
        $user_id_array = $this->legacy_db->query("SELECT user_id FROM auth_tokens WHERE token = '" . $tokenval . "'");
        $user_id = $user_id_array->result(); 
        $user_id = $user_id[0]->user_id;
        if ($user_id) {
            return $user_id;
        } else {
            return false;
        }
    }

    /**
     * @author : Sunil Tanwar
     * @created :  27 jan 2015
     * @description : This method used to verify Api key, App Version, config version and Auth Token
     * @access protected
     * @param array headerFiled
     * @param bool checkAuthToken (To Check Auth token)
     * @return array 
     */
    public function appStatusVerify($headerFiled, $checkAuthToken = TRUE) {
        if ($headerFiled) {
            $arr = array();
            $api_id_query = $this->legacy_db->query("SELECT id FROM api_platforms WHERE api_key = '".$headerFiled['API_KEY']."'");
            $api_id = $api_id_query->result();
            $api_id = $api_id[0]->id;
            if ($api_id) {
                if ($headerFiled['API_KEY'] != 'webapikey') {
                    
                    $app_version_new_array = $this->legacy_db->query("SELECT * FROM app_versions WHERE platform_id = " . $api_id . " order by id desc limit 0,1");
                    $app_version_new = $app_version_new_array->result();
                    $app_version_new = $app_version_new[0];
                    $app_version_array = $this->legacy_db->query("SELECT * FROM app_versions WHERE platform_id = " . $api_id . " and app_version = " . $headerFiled['APP_VERSION'] . " order by id desc limit 0,1");
                    $app_version = $app_version_array->result();
                    $app_version = $app_version[0];
                    if ($headerFiled['APP_VERSION'] == $app_version_new->app_version) {
                        $appversionflag = 1;
                    } else {
                        $flag = 0;
                        if ($app_version) {
                            if (strtotime(date("Y-m-d", strtotime($app_version_new->force_update_date))) > strtotime(date("Y-m-d"))) {
                                
                                $arr['config_status'] = 1;
                                $arr['config_msg'] = "Update App version";
                                $arr['response']['app_version'] = $app_version_new->app_version;
                                $arr['response']['release_date'] = $app_version_new->release_date;
                                $arr['response']['force_update_date'] = $app_version_new->force_update_date;
                                $appversionflag = 0;
                                $flag = 1;
                            }
                        }

                        if ($flag == 0) {
                            $arr['status'] = 0;
                            $arr['msg'] = "Authentication fail";
                            $arr['errors'][] = "App version Mismatch";
                            $arr['data']['app_version'] = $app_version_new->app_version;
                            $arr['data']['release_date'] = $app_version_new->release_date;
                            $arr['data']['force_update_date'] = $app_version_newforce_update_date;
                            $appversionflag = -1;
                        }
                    }
                    if ($appversionflag == 1 || $appversionflag == 0) {
                        $config_version_new_array = $this->legacy_db->query("SELECT * FROM api_configs WHERE app_version_id = " . $app_version->id . " order by id desc limit 0,1");
                        $config_version_new = $config_version_new_array->result();
                        $config_version_new = $config_version_new[0];
                                
                        if ($config_version_new->api_config_version == $headerFiled['CONFIG_VERSION']) {
                            if ($appversionflag == 1) {
                                $arr['config_status'] = 1;
                                $arr['config_msg'] = "app status success";
                            }
                        } else {
                            $arr['status'] = 0;
                            $arr['msg'] = "Authentication fail";
                            $arr['errors'][] = "Config Version Mismatch";
                            $arr['data']['config_version'] = $config_version_new->api_config_version;

                        }
                    }
                } else {
                    $arr['config_status'] = 1;
                    $arr['config_msg'] = "app status success";
                }
            } else {
                $arr['status'] = 0;
                $arr['msg'] = "Authentication fail";
                $arr['errors'][] = "Api key Mismatch";
                $arr['data'] = (object)array();
            }
            if ($arr['config_status'] == 1 && $checkAuthToken == TRUE) {
                $auth_token = $this->checkAuthToken($headerFiled['AUTH_TOKEN']);
                if (!$auth_token) {
                    $arr['status'] = 0;
                    $arr['msg'] = "Authentication fail";
                    $arr['errors'][] = "Invalid User or session expired. Please login again.";
                    $arr['data'] = (object)array();
                }
            }
            return $arr;
        } else {
            return false;
        }
    }

    public function getError($errors) {
        $errorDetail = array();
        if (!is_array($errors)) {
            $errors[] = 1000;
        } else {
            $errorDetail = APIError::whereIn('error_code', $errors)->get();
        }
        return $errorDetail;
    }

}

?>
