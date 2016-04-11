<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class homepage {

    public function homepageapi($params = null) {
        try {
            $CI = & get_instance();
            
            $result['status'] = 1;
            $result['msg'] = "Api Response";
            $result['errors'] = array('');
            //Tab Code Start//
            $CI->load->model('category_model');
            $cond['status'] = 1;
            $cond['is_mega_category'] = 1;
            $cols = array("category_id", "category_name");
            $tab = $CI->category_model->getCategoryData($cond, $cols, -1);
            $count = count($tab);
            for ($i = 0; $i < $count; $i++) {
                $result['data']['tabs']['tabsitems'][$i]['name'] = $tab[$i]['category_name'];
                $result['data']['tabs']['tabsitems'][$i]['category'] = intval($tab[$i]['category_id']);
                $result['data']['tabs']['tabsitems'][$i]['search'] = $tab[$i]['category_name'];
                $result['data']['tabs']['tabsitems'][$i]['storefront'] = null;
                $result['data']['tabs']['tabsitems'][$i]['product'] = null;
            }
            //Tab Code End///
            //Main Banner Code Start//
            $CI->load->model('banner_model');
            $cond = array();
            $cond['type'] = "main_banner";
            //$cond['cat_id'] = 0;
            $res = $CI->banner_model->getBanner($cond, '', -1);
            $bannerArr = array();
            if ($res) {
                $arr_count = count($res);
                for ($i = 0; $i < $arr_count; $i++) {
                    $bannerArr[$i]['category'] = intval($res[$i]['cat_id']);
                    $bannerArr[$i]['image'] = $CI->config->item('BANNER_IMG_PATH') . $res[$i]['image_url'];
                    // $bannerArr[$i]['link'] = $res[$i]['link'];
                    $bannerArr[$i]['name'] = $res[$i]['title'];
                    $bannerArr[$i]['search'] = $res[$i]['title'];
                    $bannerArr[$i]['storefront'] = null;
                    $bannerArr[$i]['product'] = null;
                }
                $result['data']['banner']['bannertitle'] = "banner title comes here";
                $result['data']['banner']['banneritems'] = $bannerArr;
            }
            //Main Banner Code End//
            //Slider Banner Code Start//
            $CI->load->model('banner_model');
            $cond = array();
            $cond['type'] = "side_banner";
            //$cond['cat_id'] = 0;
            $res = $CI->banner_model->getBanner($cond, '', -1);
            $bannerArr = array();
            if ($res) {
                $arr_count = count($res);
                for ($i = 0; $i < $arr_count; $i++) {
                    $bannerArr[$i]['category'] = intval($res[$i]['cat_id']);
                    $bannerArr[$i]['image'] = $CI->config->item('BANNER_IMG_PATH') . $res[$i]['image_url'];
                    //$bannerArr[$i]['link'] = $res[$i]['link'];
                    $bannerArr[$i]['name'] = $res[$i]['title'];
                    $bannerArr[$i]['search'] = $res[$i]['title'];
                    $bannerArr[$i]['storefront'] = null;
                    $bannerArr[$i]['product'] = null;
                }
                $result['data']['slider']['slidertitle'] = "Slider title comes here";
                $result['data']['slider']['slideritems'] = $bannerArr;
            }
            //Slider Banner Code End//

            $category = $this->categoryList();
            $result['data']['category']['categorytitle'] = "category title comes here";
            $result['data']['category']['categoryitems'] = array();
            if ($category['status'] == 1) {

                $result['data']['category']['categoryitems'] = $category['response'];
            }
            $product = $this->productList();
            $result['data']['list']['listtitle'] = "List title comes here";
            $result['data']['list']['listitems'] = array();
            if ($category['status'] == 1) {
                $list = array();
                if (!empty($product['response']['response']['docs'])) {
                    $product_list = $product['response']['response']['docs'];
                    $count = count($product['response']['response']['docs']);
                    for ($i = 0; $i < $count; $i++) {
                        $d['type'] = "product";
                        $d['product'] = intval($product_list[$i]['subscribed_product_id']);
                        $d['storefront'] = intval($product_list[$i]['store_id']);
                        $d['name'] = $product_list[$i]['title'];
                        $d['price'] = $product_list[$i]['store_price'];
                        $d['offer_price'] = $product_list[$i]['store_offer_price'];
                        $d['image'] = 'http://139.162.24.97:83/images/media/product/thumbnails/150x150/5/6/564ab6cfb2d51.jpg';//$product_list[$i]['default_thumb_url'];
                        $result['data']['list']['listitems'][] = $d;
                    }
                }
                for ($i = 0; $i < $arr_count; $i++) {
                    $j = $count + $i;
                    $d['type'] = "banner";
                    $d['category'] = intval($res[$i]['cat_id']);
                    $d['image'] = $CI->config->item('BANNER_IMG_PATH') . $res[$i]['image_url'];
                    //$result['data']['list'][$j]['link'] = $res[$i]['link'];
                    $d['name'] = $res[$i]['title'];
                    $d['search'] = $res[$i]['title'];
                    $d['storefront'] = null;
                    $d['product'] = null;
                    $result['data']['list']['listitems'][] = $d;
                }
            }
            if ($params['type'] == 'tab1') {
                unset($result['data']['banner']);
            }
            if ($params['type'] == 'tab2') {
                unset($result['data']['slider']);
            }
            if ($params['type'] == 'tab3') {
                unset($result['data']['category']);
            }
            if ($params['type'] == 'tab4') {
                unset($result['data']['list']);
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['msg'] = "Server Error";
            $result['errors'] = array($ex->getMessage());
            $result['data'] = array('');
            return $result;
        }
    }

    public function categoryList() { //Used in the booking Engine
        try {
            $arr = array();
            $CI = & get_instance();
            $CI->load->config('custom-config');
            $url = $CI->config->item('CAT_SOLR_URL');

            $query_condition = "*:*";
            $parameters = $CI->config->item('CAT_PARAMS');
            $start = 0;
            $rows = 500;
            $sort_fileds = 'category_name asc';
            //Complete Solr Url With Parameter
            $url = $url . "select?q=" . urlencode($query_condition) . "&start=" . $start . "&rows=" . $rows . "&fl=" . $parameters . "&sort=" . urlencode($sort_fileds) . "&wt=json&indent=true";
            $product_list = $this->httpGet($url);
            $result['status'] = "Success";
            $result['msg'] = "Product List";
            $result['response'] = json_decode($product_list, true);
            $count = count($result['response']['response']['docs']);
            if ($count > 0) {
                $category = $result['response']['response']['docs'];
                for ($i = 0; $i < $count; $i++) {
                    $arr[$i] = $this->getTree($category[$i]['category_id']);
                }
            }
            $result['status'] = 1;
            $result['msg'] = 'Category List';
            $result['response'] = $arr;
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public static function httpGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    function getTree($categoryId) {
        $CI = & get_instance();
        $res = array();
        $parameters = $CI->config->item('CAT_PARAMS');
        if (!empty($categoryId)) {
            $url = $CI->config->item('CAT_SOLR_URL') . "select?q=" . urlencode("category_id : " . $categoryId) . "&fl=" . $parameters . "&sort=" . urlencode('category_name asc') . "&wt=json&indent=true";
//           print_r($url);
            $cat_list = $this->httpGet($url);

            $result1['response'] = json_decode($cat_list, true);
            $count = count($result1['response']['response']['docs']);
            if ($count > 0) {
                $category = $result1['response']['response']['docs'];
                $res['name'] = $category[0]['category_name'];
                $res['category'] = intval($category[0]['category_id']);
                $res['search'] = $category[0]['category_name'];
                $res['storefront'] = null;
                // $res = $category[0];
                $url1 = $CI->config->item('CAT_SOLR_URL') . "select?q=" . urlencode("parent_category_id : " . $categoryId) . "&fl=" . $parameters . "&sort=" . urlencode('category_name asc') . "&wt=json&indent=true";
//           print_r($url);
                $cat_list1 = $this->httpGet($url1);
                $result2['response'] = json_decode($cat_list1, true);
                $count1 = count($result2['response']['response']['docs']);
                if ($count1 > 0) {
                    $category2 = $result2['response']['response']['docs'];
                    for ($j = 0; $j < $count1; $j++) {
                        $res['child'][] = $this->getTree($category2[$j]['category_id']);
                    }
                }
            }
        }
        return $res;
    }

    public function productList($params) { //Used in the booking Engine
        try {
            $CI = & get_instance();
            $CI->load->config('custom-config');
            $url = $CI->config->item('SOLR_URL');

            $query_condition = "*:*";
            $facets_condition = "*:*";
            $parameters = "*";
            $start = 0;
            $rows = 10;


            $parameters = $CI->config->item('SOLR_MICRO_PARAM');

            $sort_fileds = 'subscribed_product_id asc';

            //Complete Solr Url With Parameter
            $url = $url . "select?q=" . urlencode($query_condition) . "&fq=" . urlencode($facets_condition) . "&start=" . $start . "&rows=" . $rows . "&fl=" . $parameters . "&sort=" . urlencode($sort_fileds) . "&wt=json&indent=true";

            $product_list = $this->httpGet($url);
            $result['status'] = 1;
            $result['msg'] = "Product List";
            $result['response'] = json_decode($product_list, true);
            return $result;
        } catch (Exception $ex) {
            $result['status'] = 0;
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

}
