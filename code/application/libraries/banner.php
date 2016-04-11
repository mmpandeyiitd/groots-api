<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class banner {

    public function getBanner($params = null) {
        try {
            $CI = & get_instance();
            $CI->load->model('banner_model');
            $cond['status'] = 1;
            if (!empty($params['category_id'])) {
                $CI->load->library('validation');
                $result = $CI->validation->validate_categoryid($params);
                $cond['cat_id'] = $params['category_id'];
            } else {
                 $cond['cat_id'] = 0;
            }
            $result['status'] = "Success";
            $result['msg'] = "Banner List";
            $res = $CI->banner_model->getBanner($cond, '', -1);
            $CI->load->config('custom-config');
            $bannerArr = array();
            if ($res) {
                $arr_count = count($res);
                $data = array();
                for ($i = 0; $i < $arr_count; $i++) {
                    $data[$i] = $res[$i]['type'];
                }
                $data = array_values(array_unique($data));
                for ($j = 0; $j < count($data); $j++) {
                    for ($i = 0; $i < $arr_count; $i++) {
                        $finalData['id']=$res[$i]['id'];
                        $finalData['image_url']=$CI->config->item('BANNER_IMG_PATH') . $res[$i]['image_url'];
                        $finalData['link'] = $res[$i]['link'];
                        $finalData['title'] = $res[$i]['title'];

                        if ($data[$j] == $res[$i]['type']) {
                            $cat = $data[$j];
                            $bannerArr[$j][$cat][] = $finalData;
                        }
                    }
                }
            }
            $result['response'] = $bannerArr;
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

}
