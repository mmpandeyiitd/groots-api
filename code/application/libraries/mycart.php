<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mycart {

    public function addremovetoCart($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('mycart_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_add_remove_to_cart($params);
            if ($result['status'] == 1) {
                if($params['action'] == 1){
                    if ($params['user_id'] == '' && $params['session_id'] == '') {
                        $result = 'User id / Session id not found';
                    } else {
                        $data = array();
                        $data['user_id'] = $params['user_id'];
                        $data['session_id'] = $params['session_id'];
                        $data['ip_address'] = $params['ip_address'];

                        if ($params['user_id'] != '' && $params['user_id'] != '0') {
                            $user_id_exists = $CI->mycart_model->checkUserExists($params['user_id']);
                        } else {
                            $user_id_exists = '0';
                        }

                        if ($params['session_id'] != '' && $params['session_id'] != '0') {
                            $session_id_exists = $CI->mycart_model->checkSessionExists($params['session_id']);
                        } else {
                            $session_id_exists = '0';
                        }

                        if ($user_id_exists == 0 && $session_id_exists == 0) {
                            $data['cart_data'] = $this->getCartData($params);
                            if ($data['cart_data'] == FALSE) {
                                $result['status'] = 'Failed';
                                $result['data'] = '';
                                $result['msg'] = 'Duplicate Products exists in your Cart';
                            }else{
                                $res = $CI->mycart_model->saveCartData($data);
                                if ($res > 0) {
                                    $data_shown = array('count' => count($params['product_id']));
                                    $result['status'] = "Success";
                                    $result['data'] = $data_shown;
                                    $result['msg'] = "Product inserted successfully";
                                } else {
                                    $data_shown = array('count' => '0');
                                    $result['status'] = "Failed";
                                    $result['data'] = $data_shown;
                                    $result['msg'] = "Failed to insert Product in cart";
                                }
                            }
                        } else {
                            if ($user_id_exists == '1') {
                                $current_cart_data = $CI->mycart_model->getDataByUserId($params['user_id']);
                            } elseif ($session_id_exists == '1') {
                                $current_cart_data = $CI->mycart_model->getDataBySessionId($params['session_id']);
                            }

                            $updated_data = $this->updCartData($params, $current_cart_data);
                            if ($updated_data['status'] == 'DUPLICATE') {
                                $result['status'] = 'Failed';
                                $result['data'] = array("count" => $updated_data['count']);
                                $result['msg'] = 'Duplicate Products exists in your Cart';
                            } else {
                                $data['cart_data'] = $updated_data['encoded_cart_data'];
                                $data['id'] = $updated_data['id'];
                                $res = $CI->mycart_model->updateCartData($data);
                                if ($res > 0) {
                                    $result['status'] = "Success";
                                    $result['data'] = array("count" => COUNT(json_decode($data['cart_data'], true)));
                                    $result['msg'] = "Product inserted successfully";
                                } else {
                                    $result['status'] = "Failed";
                                    $result['msg'] = "Failed to insert Product in your Cart";
                                }
                            }
                        }
                    }
                }else if($params['action'] == 2){
                    $user_id_exists = $CI->mycart_model->checkUserExists($params['user_id']);

                    $session_id_exists = $CI->mycart_model->checkSessionExists($params['session_id']);

                    if ($user_id_exists == 0 && $session_id_exists == 0) {
                        $result['status'] = "Failed";
                        $result['msg'] = 'Cart is Empty, No Record Found';
                        $result['data'] = array("count" => "0");
                    } else {
                        if ($user_id_exists == '1') {
                            $current_cart_data = $CI->mycart_model->getDataByUserId($params['user_id']);
                        } elseif ($session_id_exists == '1') {
                            $current_cart_data = $CI->mycart_model->getDataBySessionId($params['session_id']);
                        }

                        $updated_data = $this->updateDeleteCart($params, $current_cart_data);

                        if ($updated_data['status'] == 'Success') {
                            $data['cart_data'] = $updated_data['encoded_cart_data'];
                            $data['id'] = $updated_data['id'];

                            if ($data['cart_data'] != '') {
                                $count = COUNT(json_decode($data['cart_data'], true));
                                $res = $CI->mycart_model->updateCartData($data);
                            } else {
                                $count = 0;
                                $res = $CI->mycart_model->deleteCartByCartId($data['id']);
                            }
                            $result['status'] = "Success";
                            $result['data'] = array("count" => $count);
                            $result['msg'] = "Cart updated Successfully.";
                        } else {
                            $result['status'] = "Fail";
                            $result['msg'] = "Product is not valid.";
                        }
                    }
                }
            } else {
                $result['status'] = "Fail";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }
    
    public function getcartcount($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('mycart_model');
            $data = array();
            $data['user_id'] = $params['user_id'];
            $data['session_id'] = $params['session_id'];
            if ($params['user_id'] != '' && $params['user_id'] != '0') {
                $user_id_exists = $CI->mycart_model->checkUserExists($params['user_id']);
            } else {
                $user_id_exists = '0';
            }

            if ($params['session_id'] != '' && $params['session_id'] != '0') {
                $session_id_exists = $CI->mycart_model->checkSessionExists($params['session_id']);
            } else {
                $session_id_exists = '0';
            }

            if ($user_id_exists == 0 && $session_id_exists == 0) {
                $result['status'] = "Success";
                $result['msg'] = "Cart is empty";
                $result['data'] = 0;
            } else {
                if ($user_id_exists == '1') {
                    $current_cart_data = $CI->mycart_model->getDataByUserId($params['user_id']);
                } elseif ($session_id_exists == '1') {
                    $current_cart_data = $CI->mycart_model->getDataBySessionId($params['session_id']);
                }
                $existing_products = json_decode($current_cart_data[0]->cart_data, true);
                $result['status'] = "Success";
                $result['msg'] = "Count fetch successfully";
                $result['data'] = count($existing_products);
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function getCartData($all_params) {
        $product = array();
        foreach($all_params['product_id'] as $prod_id)
        {
            if (array_key_exists($prod_id, $product)) {
                return FALSE;
            }else{
                $product[$prod_id] = 1;
            }
        }
        return json_encode($product);
        
    }

    public function updCartData($all_params, $cur_cart_dt) {
        $existing_products = json_decode($cur_cart_dt[0]->cart_data, true);

        $duplicate = '0';
        foreach($all_params['product_id'] as $prod_id)
        {
            if (array_key_exists($prod_id, $existing_products)) {
                $duplicate = '1';
            }

            if ($duplicate == '0') {
                $existing_products[$prod_id] =1;
            } else {
                $send_data['status'] = 'DUPLICATE';
                $send_data['count'] = count($existing_products);
                break;
            }
        }
        
        if ($duplicate == '0') {
            $send_data['encoded_cart_data'] = json_encode($existing_products);
            $send_data['id'] = $cur_cart_dt[0]->id;
            $send_data['status'] = 'SUCCESS';
            return $send_data;
        } else {
            $send_data['status'] = 'DUPLICATE';
            $send_data['count'] = count($existing_products);
            return $send_data;
        }
    }

    public function fetchCartData($params) {      
        try {
            $CI = & get_instance();
            $CI->load->model('mycart_model');
            $CI->load->config('custom-config');
            $CI->load->library('validation');
            $CI->load->library('Product');
            $result = $CI->validation->validate_fetch_cart_data($params);
            if ($result['status'] == 1) {
                $data = array();
                $data['user_id'] = $params['user_id'];
                $data['session_id'] = $params['session_id'];

                if ($params['user_id'] != '0' && $params['user_id'] != '') {
                    $user_id_exists = $CI->mycart_model->checkUserExists($params['user_id']);
                } else {
                    $user_id_exists = '0';
                }

                if ($params['session_id'] != '0' && $params['session_id'] != '') {
                    $session_id_exists = $CI->mycart_model->checkSessionExists($params['session_id']);
                } else {
                    $session_id_exists = '0';
                }

                if ($user_id_exists <= 0 && $session_id_exists <= 0) {
                    $count = array("count" => "0");
                    $result['status'] = "SUCCESS";
                    $result['msg'] = 'Cart is Empty, No Record Found';
                    $result['data'] = Null;//array($count);
                } else {
                    if ($user_id_exists == '1') {
                        $current_cart_data = $CI->mycart_model->getDataByUserId($params['user_id']);
                    } elseif ($session_id_exists == '1') {
                        $current_cart_data = $CI->mycart_model->getDataBySessionId($params['session_id']);
                    }

                    $cart_dt = $current_cart_data[0]->cart_data;
                    $this_cart_id['cart_id'] = $current_cart_data[0]->id;

                    ////////////////////////////////////////////////////////

                    $decoded_cart_data = json_decode($cart_dt, true);
                    $filters = array();
                    $i = 0;
                    foreach ($decoded_cart_data as $key => $value) {
                        $filters['filter']['subscribed_product_id'][$i] = $key;
                        $i++;
                    }
                    if (!empty($filters)) {
                        $data = $CI->product->productList($filters);

                        for ($i = 0; $i < count($data['response']->response->docs); $i++) {
                            $fetch_api_data[$i]['product_id'] = $data['response']->response->docs[$i]->subscribed_product_id;
                            $fetch_api_data[$i]['base_product_id'] = $data['response']->response->docs[$i]->base_product_id;
                            $fetch_api_data[$i]['product_name'] = $data['response']->response->docs[$i]->title;
                            $fetch_api_data[$i]['store_price'] = $data['response']->response->docs[$i]->store_price;
                            $fetch_api_data[$i]['store_offer_price'] = $data['response']->response->docs[$i]->store_offer_price;
                            $fetch_api_data[$i]['color_index'] = $data['response']->response->docs[$i]->color_index;
                            $fetch_api_data[$i]['season'] = $data['response']->response->docs[$i]->season;
                            $fetch_api_data[$i]['sku'] = $data['response']->response->docs[$i]->sku;
                        }
                        ////////////////////////////////////////////////////////
                        $result['status'] = 'Success';
                        $result['msg'] = 'Products found in cart';
                        if ($params['app'] == 1) {  
                            $result['data']['cart_id'] = $this_cart_id['cart_id'];
                            $result['data']["count"] = $data['response']['response']['numFound'];
                            $result['data']['cart_data'] = $fetch_api_data;
                          
                        } else {
                            $total_product = array("count" => $data['response']->response->numFound);
                            $final_cart_data = array("cart_data" => $fetch_api_data);
                            $result['data'] = array($total_product, $this_cart_id, $final_cart_data);
                        }
                    } else {
                        $total_product = array("count" => 0);
                        $final_cart_data = array("cart_data" => '');
                        $result['data'] = '';
                        $result['status'] = 'Success';
                        $result['msg'] = 'Products not found in cart';
                    }
                }
            } else {
                $result['status'] = "Fail";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function updateData($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('mycart_model');
            $CI->load->library('validation');
            $result = $CI->validation->validate_update_cart($params);
            if ($result['status'] == 1) {
                if ($params['product_qty'] == '0') {
                    $result['status'] = "Failed";
                    $result['msg'] = 'QUANTITY MUST NOT BE ZERO';
                } else {
                    $user_id_exists = $CI->mycart_model->checkUserExists($params['user_id']);

                    $session_id_exists = $CI->mycart_model->checkSessionExists($params['session_id']);

                    if ($user_id_exists == 0 && $session_id_exists == 0) {
                        $result['status'] = "Failed";
                        $result['msg'] = 'Cart is Empty, No Record Found';
                        $result['data'] = array("count" => "0");
                    } else {
                        if ($user_id_exists == '1') {
                            $current_cart_data = $CI->mycart_model->getDataByUserId($params['user_id']);
                        } elseif ($session_id_exists == '1') {
                            $current_cart_data = $CI->mycart_model->getDataBySessionId($params['session_id']);
                        }

                        $updated_data = $this->updateDeleteCart($params, $current_cart_data);

                        if ($updated_data['status'] == 'Success') {
                            $data['cart_data'] = $updated_data['encoded_cart_data'];
                            $data['id'] = $updated_data['id'];

                            if ($data['cart_data'] != '') {
                                $count = COUNT(json_decode($data['cart_data'], true));
                                $res = $CI->mycart_model->updateCartData($data);
                            } else {
                                $count = 0;
                                $res = $CI->mycart_model->deleteCartByCartId($data['id']);
                            }

                            if ($count > 0) {
                                $result['status'] = "Success";
                                $result['data'] = array("count" => $count);
                                $result['msg'] = "Cart updated Successfully.";
                            } else {
                                $result['status'] = "Success";
                                $result['data'] = array("count" => $count);
                                $result['msg'] = "Product removed form cart";
                            }
                        } else {
                            $result['status'] = "Fail";
                            $result['msg'] = "Product Quantity is not valid.";
                        }
                    }
                }
            } else {
                $result['status'] = "Fail";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function updateDeleteCart($all_params, $cur_cart_dt) {
        $existing_products = json_decode($cur_cart_dt[0]->cart_data, true);
        if ($all_params['delete_cart'] == 1) {
            $send_data['encoded_cart_data'] = '';
        } else {
            foreach ($all_params['product_id'] as $product_id) {
                unset($existing_products[$product_id]);
            }
            if (count($existing_products) > 0) {
                $send_data['encoded_cart_data'] = json_encode($existing_products);
            } else {
                $send_data['encoded_cart_data'] = '';
            }
        }
        $send_data['id'] = $cur_cart_dt[0]->id;
        $send_data['status'] = 'Success';
        return $send_data;
    }

}
