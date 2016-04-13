<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class order extends CI_Controller {

    function __construct() {
        parent::__construct();
        // $this->load->model("order_model", "order1");
        $this->load->model("product_model", "product_model");
    }

    public function addOrder($params_r) {
        try {
            $CI = & get_instance();
            $CI->load->model('order_model');
            $CI->load->model('user_model');
            $log['inputs'] = json_encode($params);
            $logid = $CI->order_model->saveOrderLogs($log);
            $CI->load->library('validation');
            $CI->load->config('custom-config');
            $CI->load->library('communicationengine');
            $params = $params_r['data'];
            $products = $params['product_details'];
            $result = $CI->validation->validate_order($params);
            $buyer_data =  $CI->user_model->getUserDetails($params_r['user_id']);
            $params['shipping_name'] = $buyer_data[0]->name;
            $params['shipping_email'] = $buyer_data[0]->email;
            $params['shipping_phone'] = $buyer_data[0]->mobile;
            $params['shipping_address'] = $buyer_data[0]->address;
            $params['shipping_city'] = $buyer_data[0]->city;
            $params['shipping_state'] = $buyer_data[0]->state;
            $params['shipping_pincode'] = $buyer_data[0]->pincode;
            $params['billing_name'] = $buyer_data[0]->name;
            $params['billing_email'] = $buyer_data[0]->email;
            $params['billing_phone'] = $buyer_data[0]->mobile;
            $params['billing_address'] = $buyer_data[0]->address;
            $params['billing_city'] = $buyer_data[0]->city;
            $params['billing_state'] = $buyer_data[0]->state;
            $params['billing_pincode'] = $buyer_data[0]->pincode;
            $params['buyer_email'] = $buyer_data[0]->email;
            $grandtotal = 0.0;
            $totalpayableamount = 0.0;
            $totalTax = 0.0;
            $totalShippingCharges = 0;
            if ($result['status'] == 1) {
                
                // $pincodeArr = $CI->config->item('PINCODE');
                // if (!in_array($params['shipping_pincode'], $pincodeArr, true)) {
                //     $result['status'] = "Failed";
                //     $result['msg'] = "Save to fail data";
                //     $result['errors'] = "Invalid shipping pincode";
                //     return $result;
                // }
                $count = count($products);
                $flag_exit = 0;
                for ($i = 0; $i < $count; $i++) {
                    if (!empty($products[$i]['subscribed_product_id'])) {
                        $cond['subscribed_product_id'] = $products[$i]['subscribed_product_id'];
                    } elseif (!empty($products[$i]['base_product_id']) && !empty($products[$i]['store_id'])) {
                        $cond['base_product_id'] = $products[$i]['base_product_id'];
                        $cond['store_id'] = $products[$i]['store_id'];
                    } else {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Invalid  subscribed product id,base product id or store id";
                        return $result;
                    }
                    $product_arr = $this->product_model->getProductData($cond);
                    if (empty($product_arr)) {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Invalid  subscribed product id,base product id or store id";
                        return $result;
                    }
                    if (!empty($products[$i]['base_product_id'])) {
                        if ($products[$i]['base_product_id'] != $product_arr['base_product_id']) {
                            $result['status'] = "Failed";
                            $result['msg'] = "Save to fail data";
                            $result['errors'] = "Invalid  subscribed product id,base product id or store id";
                            return $result;
                        }
                    } else {
                        $products[$i]['base_product_id'] = $product_arr['base_product_id'];
                    }
                    $params['seller_email'] = $product_arr['email'];
                    if (!empty($products[$i]['subscribed_product_id'])) {
                        if ($products[$i]['subscribed_product_id'] != $product_arr['subscribed_product_id']) {
                            $result['status'] = "Failed";
                            $result['msg'] = "Save to fail data";
                            $result['errors'] = "Invalid  subscribed product id,base product id or store id";
                            return $result;
                        }
                    } else {
                        $products[$i]['subscribed_product_id'] = $product_arr['subscribed_product_id'];
                    }
                    if (!empty($products[$i]['store_id'])) {
                        if ($products[$i]['store_id'] != $product_arr['store_id']) {
                            $result['status'] = "Failed";
                            $result['msg'] = "Save to fail data";
                            $result['errors'] = "Invalid  subscribed product id,base product id or store id";
                            return $result;
                        }
                    } else {
                        $products[$i]['store_id'] = $product_arr['store_id'];
                    }
                    if ($products[$i]['product_qty'] > $product_arr['quantity']) {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Invalid  quantity";
                        return $result;
                    }
                    $product_arr['store_offer_price'] = round($product_arr['store_offer_price']);
                    if (floatval($products[$i]['unit_price']) != floatval($product_arr['store_offer_price'])) {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Invalid Price";
                        return $result;
                    }
                    $product_arr['store_tax_per'] = '0.00';
//                    $taxdetail = $this->product_model->getTaxByBaseProductId($products[$i]['base_product_id']);
//                    if (!empty($taxdetail)) {
//                        foreach ($taxdetail as $tax) {
//                            if ($tax->cat_tax_per > 0) {
//                                $product_arr['store_tax_per'] = $tax->cat_tax_per;
//                                break;
//                            }
//                        }
//                    }
                    $total = floatval($products[$i]['product_qty']) * floatval($product_arr['store_offer_price']);

                    //$tax = (floatval($product_arr['store_offer_price']) * floatval($product_arr['store_tax_per']));
                    //$tax = round($tax / 100);
                    //$totalTax = floatval($products[$i]['product_qty']) * $tax;
                    // $totalshippingCharges = round(floatval($products[$i]['product_qty']) * floatval($product_arr['subscribe_shipping_charge']));

                    $grandtotal = $grandtotal + $total;
                    $totalpayableamount = $totalpayableamount + $total + $totalTax;
                    $products[$i]['store_name'] = $product_arr['store_name'];
                    $products[$i]['store_email'] = $product_arr['store_email'];
                    //$products[$i]['store_front_id'] = $product_arr['store_front_id'];
                    //$products[$i]['store_front_name'] = $product_arr['store_front_name'];
                    $products[$i]['seller_name'] = $product_arr['seller_name'];
                    $products[$i]['seller_phone'] = $product_arr['seller_contact_no'];
                    $products[$i]['seller_address'] = $product_arr['business_address'];
                    $products[$i]['seller_state'] = $product_arr['business_address_state'];
                    $products[$i]['seller_city'] = $product_arr['business_address_city'];
                    $products[$i]['colour'] = $product_arr['colour'];
                    $products[$i]['size'] = $product_arr['size'];
                    $products[$i]['price'] = $total;
                    $products[$i]['tax'] = $totalTax;
                    $products[$i]['shipping_charges'] = 0;
                    $quantity = intval($product_arr['quantity']) - intval($products[$i]['product_qty']);
                    $updateData[] = array(
                        'subscribed_product_id' => $products[$i]['subscribed_product_id'],
                        'quantity' => $quantity
                    );
                }
                if (round($grandtotal) != floatval($params['total'])) {
                    $result['status'] = "Failed";
                    $result['grandtotal'] = $grandtotal;
                    $result['msg'] = "Save to fail data";
                    $result['errors'] = "Invalid Total Price";
                    return $result;
                }

                if (empty($params['shipping_charges'])) {
                    $params['shipping_charges'] = 0.0;
                }
                $total_payable_amount = round($totalpayableamount - floatval($params['discount_amt']) + $totalShippingCharges);
                
                if ($total_payable_amount != floatval($params['total_payable_amount'])) {
                    $result['status'] = "Failed";
                    $result['total'] = $totalpayableamount;
                    $result['grandtotal'] = $total_payable_amount;
                    $result['msg'] = "Save to fail data";
                    $result['errors'] = "Invalid Payable Amount";
                    return $result;
                }

                $data = array();
                $seller = array();
                $maxid = $this->order_model->getMaxOrderId();


                $orderno = floatval($CI->config->item('ORD_NO')) + floatval($maxid) + 1;

                $data['order_number'] = "'" . $params['order_prefix'] . $orderno . "'";
                $data['created_date'] = "'" . date('Y-m-d H:i:s') . "'";
                $data['payment_status'] = "'" . 'Pending' . "'";
                $data['billing_name'] = "'" . $params['billing_name'] . "'";
                $data['billing_email'] = "'" . $params['billing_email'] . "'";
                $data['billing_phone'] = "'" . $params['billing_phone'] . "'";
                $data['billing_address'] = "'" . $params['billing_address'] . "'";
                $data['billing_city'] = "'" . $params['billing_city'] . "'";
                $data['billing_state'] = "'" . $params['billing_state'] . "'";
                $data['billing_pincode'] = "'" . $params['billing_pincode'] . "'";
                $data['shipping_name'] = "'" . $params['shipping_name'] . "'";
                $data['shipping_email'] = "'" . $params['shipping_email'] . "'";
                $data['shipping_phone'] = "'" . $params['shipping_phone'] . "'";
                $data['shipping_address'] = "'" . $params['shipping_address'] . "'";
                $data['shipping_state'] = "'" . $params['shipping_state'] . "'";
                $data['shipping_city'] = "'" . $params['shipping_city'] . "'";
                $data['shipping_pincode'] = "'" . $params['shipping_pincode'] . "'";
                $data['total'] = $params['total'];
                $data['total_payable_amount'] = $params['total_payable_amount'];
                $data['discount_amt'] = $params['discount_amt'];
                $data['status'] = "'" . 'pending' . "'";
                //$data['buyer_shipping_cost'] = $params['buyer_shipping_cost'];
                $data['order_type'] = "'" . $params['order_type'] . "'";
                $data['coupon_code'] = "'" . $params['coupon_code'] . "'";
                $data['shipping_charges'] = $params['total_shipping_charges'];
                $data['tax'] = $params['total_tax'];
                $data['user_id'] = $params_r['user_id'];
                $userAddress['user_id'] = $params_r['user_id'];
                $userAddress['type'] = 'B';
                $userAddress['address'] = $params['billing_name'] . '~' . $params['billing_email'] . '~' . $params['billing_phone'] . '~' . $params['billing_address'] . '~' . $params['billing_city'] . '~' . $params['billing_state'] . '~' . $params['billing_pincode'];
                $this->order_model->saveUserAddressData($userAddress);
                $userAddress['type'] = 'S';
                $userAddress['address'] = $params['shipping_name'] . '~' . $params['shipping_email'] . '~' . $params['shipping_phone'] . '~' . $params['shipping_address'] . '~' . $params['shipping_city'] . '~' . $params['shipping_state'] . '~' . $params['shipping_pincode'];
                $this->order_model->saveUserAddressData($userAddress);
                $FinalData['header'] = '(' . implode(',', $data) . ')';
                $count = count($products);
                $emailUserData = '';
                for ($i = 0; $i < $count; $i++) {
                    $pdata['order_id'] = '##ORDERID##';
                    $pdata['subscribed_product_id'] = $products[$i]['subscribed_product_id'];
                    $pdata['base_product_id'] = $products[$i]['base_product_id'];
                    $pdata['store_id'] = $products[$i]['store_id'];
                    $pdata['store_name'] = "'" . $products[$i]['store_name'] . "'";
                    $pdata['store_email'] = "'" . $products[$i]['store_email'] . "'";
                    //$pdata['store_front_id'] = "'" . $products[$i]['store_front_id'] . "'";
                    //$pdata['store_front_name'] = "'" . $products[$i]['store_front_name'] . "'";
                    $pdata['seller_name'] = "'" . $products[$i]['seller_name'] . "'";
                    $pdata['seller_phone'] = "'" . $products[$i]['seller_phone'] . "'";
                    $pdata['seller_address'] = "'" . $products[$i]['seller_address'] . "'";
                    $pdata['seller_state'] = "'" . $products[$i]['seller_state'] . "'";
                    $pdata['seller_city'] = "'" . $products[$i]['seller_city'] . "'";
                    $pdata['colour'] = "'" . $products[$i]['colour'] . "'";
                    $pdata['size'] = "'" . $products[$i]['size'] . "'";
                    $pdata['product_name'] = "'" . $products[$i]['product_name'] . "'";
                    $pdata['product_qty'] = $products[$i]['product_qty'];
                    $pdata['unit_price'] = $products[$i]['unit_price'];
                    $pdata['price'] = $products[$i]['price'];
                    $pdata['tax'] = $products[$i]['tax'];
                    $pdata['shipping_charges'] = 0; //$products[$i]['shipping_charges'];
                    $LinesData[] = '(' . implode(',', $pdata) . ')';
                    $serialno = $i + 1;
                    $seller[$i]['name'] = $products[$i]['store_name'];
                    $seller[$i]['email'] = $products[$i]['store_email'];
                    $seller[$i]['product'] = '<tr style="border-bottom: 1px solid #E6E6E6">
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $serialno . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $products[$i]['store_name'] . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $products[$i]['product_name'] . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $products[$i]['product_qty'] . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">Rs. ' . $products[$i]['price'] . '</td>
                    </tr>';
                    $emailProductData.='<tr style="border-bottom: 1px solid #E6E6E6">
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $serialno . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $products[$i]['store_name'] . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $products[$i]['product_name'] . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">' . $products[$i]['product_qty'] . '</td>
                    <td align="center" style="padding: 8px;color: #695C5C;">Rs. ' . $products[$i]['price'] . '</td>
                    </tr>';
                }
                $emailProductData.=' <tr><td align="right" colspan="4" style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Total:</td><td align="center"style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Rs.' . $params['total'] . '</td> </tr><tr><td align="right" colspan="4" style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Tax:</td><td align="center"style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Rs.' . $params['total_tax'] . '</td></tr><tr><td align="right" colspan="4" style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Shipping Charges:</td><td align="center"style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Rs.' . $totalShippingCharges . '</td></tr><tr><td align="right" colspan="4" style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Discount:</td><td align="center"style="padding: 8px;color: #695C5C; background-color: rgba(224, 224, 224, 0.31);">Rs.' . $params['discount_amt'] . '</td> </tr><tr style="  background-color: rgba(224, 224, 224, 0.31);  border: 1px solid #D8D8D8;  border-width: 1px 0;"><td align="right" colspan="4" style="padding: 8px;color: #695C5C; font-size: 22px;">Grand Total:</td><td align="center" style="padding: 8px;color: #695C5C; font-size: 22px; color: #E71B34;"><strong>Rs. ' . $params['total_payable_amount'] . '</strong></td></tr>';
                $userAddress = '<td colspan="1" align="left" valign="top" style="margin:0;padding:15px"><p style="margin:0;padding:0;color:#565656; font-weight:bold;">BILLING ADDRESS</p>
                <p style="padding:0;margin:15px 0 10px 0;font-size:17px"> ' . $params['billing_name'] . ' &nbsp;|&nbsp; ' . $params['billing_phone'] . '</p>
                <p style="line-height:18px;padding:0;margin:0;color:#565656; font-size: 12px;"> ' . $params['billing_address'] . ', ' . $params['billing_city'] . '<br/> ' . $params['billing_state'] . '-' . $params['billing_pincode'] . ' </p><br></td>
                <td colspan="1" align="left" valign="top" style="margin:0;padding:15px"><p style="margin:0;padding:0;color:#565656; font-weight:bold;">DELIVERY ADDRESS</p>
                <p style="padding:0;margin:15px 0 10px 0;font-size:17px">' . $params['shipping_name'] . ' &nbsp;|&nbsp; ' . $params['shipping_phone'] . '</p>
                <p style="line-height:18px;padding:0;margin:0;color:#565656; font-size: 12px;"> ' . $params['shipping_address'] . ', ' . $params['shipping_city'] . '<br/> ' . $params['shipping_state'] . '-' . $params['shipping_pincode'] . ' </p><br></td>';

                $FinalData['line'] = implode(",", $LinesData);
                $order_id = $this->order_model->saveOrderHeaderAndLinesData($FinalData);
                $this->order_model->deleteCartByUserIdAndCartId($params['cart_id']);
                $logs['order_id'] = $order_id;
                $logs['id'] = $logid;
                $logid = $this->order_model->updateOrderLogs($logs);
                if ($order_id) {
                    if ($updateData) {
                        $update_res = $this->product_model->updateProductQuantity($updateData);
                        $resSolrbacklog = $this->solrBackLog($updateData);
                        if ($update_res) {
                            $viewdata['name'] = $params['shipping_name'];
                            $viewdata['product'] = $emailProductData;
                            $viewdata['address'] = $userAddress;
                            $viewdata['order_number'] = $data['order_number'];
                            $message = $CI->load->view('userOrderDetail', $viewdata, TRUE);

                            $emailData['body'] = $message;
                            $emailData['subject'] = "Supplified : Your Order Has Been Placed Successfully";
                            $emailData['email'] = $params['shipping_email'];
                            $dataRes['UserEmail'] = $CI->communicationengine->emailCommunication($emailData);

                            $SMSData['SMS'] = str_replace("{ORDERNO}", $data['order_number'], $CI->config->item('PLACE_ORDER'));
                            $SMSData['mobile'] = $params['shipping_phone'];
                            //$dataRes['UserSMS'] = $CI->communicationengine->smsCommunication($SMSData);
                            if (!empty($seller)) {
                                for ($i = 0; $i <= count($seller); $i++) {
                                    $message = $CI->load->view('sellerOrderDetail', $seller[$i], TRUE);
                                    $emailData['body'] = $message;
                                    $emailData['subject'] = "Supplified : Your Product Detail";
                                    $emailData['email'] = $seller[$i]['email'];
                                    $dataRes['SellerEmail'] = $CI->communicationengine->emailCommunication($emailData);
                                }
                            }

                            $result['status'] = "Success";
                            $result['msg'] = "Data Save Successfully";
                            $result['data']['order_id'] = $order_id;
                            $result['data']['order_no'] = $params['order_prefix'] . $orderno;
                            //$result['response']['email'] = $dataRes;
                        } else {
                            $result['status'] = "Failed";
                            $result['msg'] = "Save to fail data";
                            $result['errors'] = "Update Query not execute";
                        }
                    } else {
                        $result['status'] = "Failed";
                        $result['msg'] = "Save to fail data";
                        $result['errors'] = "Update Query not execute";
                    }
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "Save to fail data";
                    $result['errors'] = "Insert Query not execute";
                }
            } else {
                $result['status'] = "Failed";
            }
            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function solrBackLog($parmas) {
        try {
            $result = $this->product_model->insertSolrBackLog($parmas);
            return $result;
        } catch (Exception $ex) {

            return False;
        }
    }

    public function updateOrder($params) {
        try {
            $CI = & get_instance();
            $CI->load->model('order_model');
            $CI->load->library('validation');
            $CI->load->config('custom-config');

            $result = $CI->validation->validate_update_order($params);
            if ($result['status'] == 1) {
                $data['order_id'] = $params['order_id'];
                $data['payment_status'] = $params['payment_status'];
                $data['total_paid_amount'] = $params['total_paid_amount'];
                $data['status'] = $params['status'];
                $data['payment_method'] = $params['payment_method'];
                $data['transaction_id'] = $params['transaction_id'];
                $data['payment_ref_id'] = $params['payment_ref_id'];
                $data['transaction_time'] = $params['transaction_time'];
                $data['bankname'] = $params['bankname'];
                $data['payment_gateway_name'] = $params['payment_gateway_name'];
                $data['payment_source'] = $params['payment_source'];
                $data['bank_transaction_id'] = $params['bank_transaction_id'];
                $data['payment_mod'] = $params['payment_mod'];
                $data['payment_type'] = $params['payment_type'];
                $res = $this->order_model->updateOrder($data);
                if ($res) {
                    $result['status'] = "Success";
                    $result['msg'] = "Data updated Successfully";
                } else {
                    $result['status'] = "Failed";
                    $result['msg'] = "Save to fail data";
                }
            } else {
                $result['status'] = "Failed";
            }

            return $result;
        } catch (Exception $ex) {
            $result['status'] = "Fail";
            $result['errors'] = $ex->getMessage();
            return $result;
        }
    }

    public function fetchorder($params) {
        $CI = & get_instance();
        $CI->load->model('order_model');
        $CI->load->library('validation');
        $CI->load->config('custom-config');
        $result = $CI->validation->validate_fetch_order($params);
        if ($result['status'] == 1) {
            $order_header_data = $CI->order_model->getDataByOrderId($params);
            $i = 0;
            $k = 0;
            $order_id = array();
            for ($j = 0; $j < count($order_header_data); $j++) {
                if (!(in_array($order_header_data[$j]->order_id, $order_id))) {
                    if ($j == 0) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                    $k++;
                    $product_img = $CI->order_model->getImgByBaseId($order_header_data[$j]->base_product_id);
                    $pro_img = $product_img[0]->thumb_url;
                    
                    if(isset($params['min']) && $params['min'] == 1)
                    {
                        $arr[$i]['order_id'] = $order_header_data[$j]->order_id;
                        $arr[$i]['order_number'] = $order_header_data[$j]->order_number;
                        $arr[$i]['total'] = $order_header_data[$j]->total;
                        $arr[$i]['total_payable_amount'] = $order_header_data[$j]->total_payable_amount;
                        $arr[$i]['total_paid_amount'] = $order_header_data[$j]->total_paid_amount;
                        $arr[$i]['discount_amt'] = $order_header_data[$j]->discount_amt;
                        $arr[$i]['coupon_code'] = $order_header_data[$j]->coupon_code;
                        $arr[$i]['transaction_id'] = $order_header_data[$j]->transaction_id;
                        $arr[$i]['bank_transaction_id'] = $order_header_data[$j]->bank_transaction_id;
                        $arr[$i]['payment_mod'] = $order_header_data[$j]->payment_mod;
                        $arr[$i]['bankname'] = $order_header_data[$j]->bankname;
                        $arr[$i]['status'] = $order_header_data[$j]->status;

                        $r['subscribed_product_id'] = $order_header_data[$j]->subscribed_product_id;
                        $r['base_product_id'] = $order_header_data[$j]->base_product_id;
                        $r['store_id'] = $order_header_data[$j]->store_id;
                        $r['store_name'] = $order_header_data[$j]->store_name;
                        $r['store_email'] = $order_header_data[$j]->store_email;
                        //$r['store_front_id'] = $order_header_data[$j]->store_front_id;
                        //$r['store_front_name'] = $order_header_data[$j]->store_front_name;
                        $r['seller_name'] = $order_header_data[$j]->seller_name;
                        $r['seller_phone'] = $order_header_data[$j]->seller_phone;
                        $r['seller_address'] = $order_header_data[$j]->seller_address;
                        $r['seller_state'] = $order_header_data[$j]->seller_city;
                        $r['colour'] = $order_header_data[$j]->colour;
                        $r['size'] = $order_header_data[$j]->size;
                        $r['product_name'] = $order_header_data[$j]->product_name;
                        $r['product_qty'] = $order_header_data[$j]->product_qty;
                        $r['unit_price'] = $order_header_data[$j]->unit_price;
                        $r['price'] = $order_header_data[$j]->price;
                        $r['thumb_img'] = $CI->config->item('PRODUCT_IMG_PATH') . $pro_img;
                        $arr[$i]['product_details'][] = $r;
                    }
                    else 
                    {
                        $arr[$i]['order_id'] = $order_header_data[$j]->order_id;
                        $arr[$i]['order_number'] = $order_header_data[$j]->order_number;
                        $arr[$i]['user_id'] = $order_header_data[$j]->user_id;
                        $arr[$i]['created_date'] = $order_header_data[$j]->created_date;
                        $arr[$i]['payment_method'] = $order_header_data[$j]->payment_method;
                        $arr[$i]['payment_status'] = $order_header_data[$j]->payment_status;
                        $arr[$i]['billing_name'] = $order_header_data[$j]->billing_name;
                        $arr[$i]['billing_phone'] = $order_header_data[$j]->billing_phone;
                        $arr[$i]['billing_email'] = $order_header_data[$j]->billing_email;
                        $arr[$i]['billing_address'] = $order_header_data[$j]->billing_address;
                        $arr[$i]['billing_state'] = $order_header_data[$j]->billing_state;
                        $arr[$i]['billing_city'] = $order_header_data[$j]->billing_city;
                        $arr[$i]['billing_pincode'] = $order_header_data[$j]->billing_pincode;
                        $arr[$i]['shipping_name'] = $order_header_data[$j]->shipping_name;
                        $arr[$i]['shipping_phone'] = $order_header_data[$j]->shipping_phone;
                        $arr[$i]['shipping_email'] = $order_header_data[$j]->shipping_email;
                        $arr[$i]['shipping_address'] = $order_header_data[$j]->shipping_address;
                        $arr[$i]['shipping_state'] = $order_header_data[$j]->shipping_state;
                        $arr[$i]['shipping_city'] = $order_header_data[$j]->shipping_city;
                        $arr[$i]['shipping_pincode'] = $order_header_data[$j]->shipping_pincode;
                        $arr[$i]['shipping_charges'] = $order_header_data[$j]->shipping_charges;
                        $arr[$i]['total'] = $order_header_data[$j]->total;
                        $arr[$i]['total_payable_amount'] = $order_header_data[$j]->total_payable_amount;
                        $arr[$i]['total_paid_amount'] = $order_header_data[$j]->total_paid_amount;
                        $arr[$i]['discount_amt'] = $order_header_data[$j]->discount_amt;
                        $arr[$i]['coupon_code'] = $order_header_data[$j]->coupon_code;
                        $arr[$i]['payment_ref_id'] = $order_header_data[$j]->payment_ref_id;
                        $arr[$i]['payment_gateway_name'] = $order_header_data[$j]->payment_gateway_name;
                        $arr[$i]['payment_source'] = $order_header_data[$j]->payment_source;
                        $arr[$i]['timestamp'] = $order_header_data[$j]->timestamp;
                        $arr[$i]['transaction_id'] = $order_header_data[$j]->transaction_id;
                        $arr[$i]['bank_transaction_id'] = $order_header_data[$j]->bank_transaction_id;
                        $arr[$i]['transaction_time'] = $order_header_data[$j]->transaction_time;
                        $arr[$i]['payment_mod'] = $order_header_data[$j]->payment_mod;
                        $arr[$i]['bankname'] = $order_header_data[$j]->bankname;
                        $arr[$i]['status'] = $order_header_data[$j]->status;
                        $arr[$i]['cron_processed_flag'] = $order_header_data[$j]->cron_processed_flag;
                        $arr[$i]['campaign_id'] = $order_header_data[$j]->campaign_id;
                        $arr[$i]['buyer_shipping_cost'] = $order_header_data[$j]->buyer_shipping_cost;
                        $arr[$i]['order_type'] = $order_header_data[$j]->order_type;

                        $r['subscribed_product_id'] = $order_header_data[$j]->subscribed_product_id;
                        $r['base_product_id'] = $order_header_data[$j]->base_product_id;
                        $r['store_id'] = $order_header_data[$j]->store_id;
                        $r['store_name'] = $order_header_data[$j]->store_name;
                        $r['store_email'] = $order_header_data[$j]->store_email;
                        //$r['store_front_id'] = $order_header_data[$j]->store_front_id;
                        //$r['store_front_name'] = $order_header_data[$j]->store_front_name;
                        $r['seller_name'] = $order_header_data[$j]->seller_name;
                        $r['seller_phone'] = $order_header_data[$j]->seller_phone;
                        $r['seller_address'] = $order_header_data[$j]->seller_address;
                        $r['seller_state'] = $order_header_data[$j]->seller_city;
                        $r['colour'] = $order_header_data[$j]->colour;
                        $r['size'] = $order_header_data[$j]->size;
                        $r['product_name'] = $order_header_data[$j]->product_name;
                        $r['product_qty'] = $order_header_data[$j]->product_qty;
                        $r['unit_price'] = $order_header_data[$j]->unit_price;
                        $r['price'] = $order_header_data[$j]->price;
                        $r['thumb_img'] = $CI->config->item('PRODUCT_IMG_PATH') . $pro_img;
                        $arr[$i]['product_details'][] = $r;
                    }
                } else {
                    $k++;
                    $product_img = $CI->order_model->getImgByBaseId($order_header_data[$j]->base_product_id);
                    $pro_img = $product_img[0]->thumb_url;

                    $r['subscribed_product_id'] = $order_header_data[$j]->subscribed_product_id;
                    $r['base_product_id'] = $order_header_data[$j]->base_product_id;
                    $r['store_id'] = $order_header_data[$j]->store_id;
                    $r['store_name'] = $order_header_data[$j]->store_name;
                    $r['store_email'] = $order_header_data[$j]->store_email;
                    //$r['store_front_id'] = $order_header_data[$j]->store_front_id;
                    //$r['store_front_name'] = $order_header_data[$j]->store_front_name;
                    $r['seller_name'] = $order_header_data[$j]->seller_name;
                    $r['seller_phone'] = $order_header_data[$j]->seller_phone;
                    $r['seller_address'] = $order_header_data[$j]->seller_address;
                    $r['seller_state'] = $order_header_data[$j]->seller_city;
                    $r['colour'] = $order_header_data[$j]->colour;
                    $r['size'] = $order_header_data[$j]->size;
                    $r['product_name'] = $order_header_data[$j]->product_name;
                    $r['product_qty'] = $order_header_data[$j]->product_qty;
                    $r['unit_price'] = $order_header_data[$j]->unit_price;
                    $r['price'] = $order_header_data[$j]->price;
                    $r['thumb_img'] = $CI->config->item('PRODUCT_IMG_PATH') . $pro_img;
                    $arr[$i]['product_details'][] = $r;
                }
                $order_id[$j] = $order_header_data[$j]->order_id;
            }
            if (isset($params['app'])) {
                $result['status'] = 'Success';
                $result['msg'] = 'User Order list.';
                $result['data']['count'] = count($arr);
                $result['data']['list'] = $arr;
            } else {
                $result['status'] = 'SUCCESS';
                $result['msg'] = 'User Order list.';
                $result['count'] = count($arr);
                $result['data'] = $arr;
            }
        } else {
            $result['status'] = "Failed";
        }
        return $result;
    }
    
    public function orderdetails($params) {
        $CI = & get_instance();
        $CI->load->model('order_model');
        $CI->load->library('validation');
        $CI->load->config('custom-config');
        $result = $CI->validation->validate_order_details($params);
        if ($result['status'] == 1) {
            $order_header_data = $CI->order_model->getDataByOrderId_details($params);
            $i = 0;
            $k = 0;
            $order_id = array();
            for ($j = 0; $j < count($order_header_data); $j++) {
                if (!(in_array($order_header_data[$j]->order_id, $order_id))) {
                    if ($j == 0) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                    $k++;
                    $product_img = $CI->order_model->getImgByBaseId($order_header_data[$j]->base_product_id);
                    $pro_img = $product_img[0]->thumb_url;

                    $arr[$i]['order_id'] = $order_header_data[$j]->order_id;
                    $arr[$i]['order_number'] = $order_header_data[$j]->order_number;
                    $arr[$i]['user_id'] = $order_header_data[$j]->user_id;
                    $arr[$i]['created_date'] = $order_header_data[$j]->created_date;
                    $arr[$i]['payment_method'] = $order_header_data[$j]->payment_method;
                    $arr[$i]['payment_status'] = $order_header_data[$j]->payment_status;
                    $arr[$i]['billing_name'] = $order_header_data[$j]->billing_name;
                    $arr[$i]['billing_phone'] = $order_header_data[$j]->billing_phone;
                    $arr[$i]['billing_email'] = $order_header_data[$j]->billing_email;
                    $arr[$i]['billing_address'] = $order_header_data[$j]->billing_address;
                    $arr[$i]['billing_state'] = $order_header_data[$j]->billing_state;
                    $arr[$i]['billing_city'] = $order_header_data[$j]->billing_city;
                    $arr[$i]['billing_pincode'] = $order_header_data[$j]->billing_pincode;
                    $arr[$i]['shipping_name'] = $order_header_data[$j]->shipping_name;
                    $arr[$i]['shipping_phone'] = $order_header_data[$j]->shipping_phone;
                    $arr[$i]['shipping_email'] = $order_header_data[$j]->shipping_email;
                    $arr[$i]['shipping_address'] = $order_header_data[$j]->shipping_address;
                    $arr[$i]['shipping_state'] = $order_header_data[$j]->shipping_state;
                    $arr[$i]['shipping_city'] = $order_header_data[$j]->shipping_city;
                    $arr[$i]['shipping_pincode'] = $order_header_data[$j]->shipping_pincode;
                    $arr[$i]['shipping_charges'] = $order_header_data[$j]->shipping_charges;
                    $arr[$i]['total'] = $order_header_data[$j]->total;
                    $arr[$i]['total_payable_amount'] = $order_header_data[$j]->total_payable_amount;
                    $arr[$i]['total_paid_amount'] = $order_header_data[$j]->total_paid_amount;
                    $arr[$i]['discount_amt'] = $order_header_data[$j]->discount_amt;
                    $arr[$i]['coupon_code'] = $order_header_data[$j]->coupon_code;
                    $arr[$i]['payment_ref_id'] = $order_header_data[$j]->payment_ref_id;
                    $arr[$i]['payment_gateway_name'] = $order_header_data[$j]->payment_gateway_name;
                    $arr[$i]['payment_source'] = $order_header_data[$j]->payment_source;
                    $arr[$i]['timestamp'] = $order_header_data[$j]->timestamp;
                    $arr[$i]['transaction_id'] = $order_header_data[$j]->transaction_id;
                    $arr[$i]['bank_transaction_id'] = $order_header_data[$j]->bank_transaction_id;
                    $arr[$i]['transaction_time'] = $order_header_data[$j]->transaction_time;
                    $arr[$i]['payment_mod'] = $order_header_data[$j]->payment_mod;
                    $arr[$i]['bankname'] = $order_header_data[$j]->bankname;
                    $arr[$i]['status'] = $order_header_data[$j]->status;
                    $arr[$i]['cron_processed_flag'] = $order_header_data[$j]->cron_processed_flag;
                    $arr[$i]['campaign_id'] = $order_header_data[$j]->campaign_id;
                    $arr[$i]['buyer_shipping_cost'] = $order_header_data[$j]->buyer_shipping_cost;
                    $arr[$i]['order_type'] = $order_header_data[$j]->order_type;
                    
                    $same_product_count = 0;
                    $product_name = $order_header_data[$j]->product_name;
                    $r['product_name'] = $order_header_data[$j]->product_name;
                    $r['data'][$same_product_count]['subscribed_product_id'] = $order_header_data[$j]->subscribed_product_id;
                    $r['data'][$same_product_count]['base_product_id'] = $order_header_data[$j]->base_product_id;
                    $r['data'][$same_product_count]['store_id'] = $order_header_data[$j]->store_id;
                    $r['data'][$same_product_count]['store_name'] = $order_header_data[$j]->store_name;
                    $r['data'][$same_product_count]['store_email'] = $order_header_data[$j]->store_email;
                    //$r['store_front_id'] = $order_header_data[$j]->store_front_id;
                    //$r['store_front_name'] = $order_header_data[$j]->store_front_name;
                    $r['data'][$same_product_count]['seller_name'] = $order_header_data[$j]->seller_name;
                    $r['data'][$same_product_count]['seller_phone'] = $order_header_data[$j]->seller_phone;
                    $r['data'][$same_product_count]['seller_address'] = $order_header_data[$j]->seller_address;
                    $r['data'][$same_product_count]['seller_state'] = $order_header_data[$j]->seller_city;
                    $r['data'][$same_product_count]['colour'] = $order_header_data[$j]->colour;
                    $r['data'][$same_product_count]['size'] = $order_header_data[$j]->size;
                    $r['data'][$same_product_count]['product_name'] = $order_header_data[$j]->product_name;
                    $r['data'][$same_product_count]['product_qty'] = $order_header_data[$j]->product_qty;
                    $r['data'][$same_product_count]['unit_price'] = $order_header_data[$j]->unit_price;
                    $r['data'][$same_product_count]['price'] = $order_header_data[$j]->price;
                    $r['data'][$same_product_count]['thumb_img'] = $CI->config->item('PRODUCT_IMG_PATH') . $pro_img;
                    $arr[$i]['product_details'][] = $r;
                    
                } else {
                    $k++;
                    $product_img = $CI->order_model->getImgByBaseId($order_header_data[$j]->base_product_id);
                    $pro_img = $product_img[0]->thumb_url;

                    if($product_name == $order_header_data[$j]->product_name){
                        $same_product_count = $same_product_count+1;
                        $r['data'][$same_product_count]['subscribed_product_id'] = $order_header_data[$j]->subscribed_product_id;
                        $r['data'][$same_product_count]['base_product_id'] = $order_header_data[$j]->base_product_id;
                        $r['data'][$same_product_count]['store_id'] = $order_header_data[$j]->store_id;
                        $r['data'][$same_product_count]['store_name'] = $order_header_data[$j]->store_name;
                        $r['data'][$same_product_count]['store_email'] = $order_header_data[$j]->store_email;
                        //$r['store_front_id'] = $order_header_data[$j]->store_front_id;
                        //$r['store_front_name'] = $order_header_data[$j]->store_front_name;
                        $r['data'][$same_product_count]['seller_name'] = $order_header_data[$j]->seller_name;
                        $r['data'][$same_product_count]['seller_phone'] = $order_header_data[$j]->seller_phone;
                        $r['data'][$same_product_count]['seller_address'] = $order_header_data[$j]->seller_address;
                        $r['data'][$same_product_count]['seller_state'] = $order_header_data[$j]->seller_city;
                        $r['data'][$same_product_count]['colour'] = $order_header_data[$j]->colour;
                        $r['data'][$same_product_count]['size'] = $order_header_data[$j]->size;
                        $r['data'][$same_product_count]['product_name'] = $order_header_data[$j]->product_name;
                        $r['data'][$same_product_count]['product_qty'] = $order_header_data[$j]->product_qty;
                        $r['data'][$same_product_count]['price'] = $order_header_data[$j]->price;
                        $r['data'][$same_product_count]['thumb_img'] = $CI->config->item('PRODUCT_IMG_PATH') . $pro_img;
                    }else{
                        $same_product_count == 0;
                        $r['data'][$same_product_count]['subscribed_product_id'] = $order_header_data[$j]->subscribed_product_id;
                        $r['data'][$same_product_count]['base_product_id'] = $order_header_data[$j]->base_product_id;
                        $r['data'][$same_product_count]['store_id'] = $order_header_data[$j]->store_id;
                        $r['data'][$same_product_count]['store_name'] = $order_header_data[$j]->store_name;
                        $r['data'][$same_product_count]['store_email'] = $order_header_data[$j]->store_email;
                        //$r['store_front_id'] = $order_header_data[$j]->store_front_id;
                        //$r['store_front_name'] = $order_header_data[$j]->store_front_name;
                        $r['data'][$same_product_count]['seller_name'] = $order_header_data[$j]->seller_name;
                        $r['data'][$same_product_count]['seller_phone'] = $order_header_data[$j]->seller_phone;
                        $r['data'][$same_product_count]['seller_address'] = $order_header_data[$j]->seller_address;
                        $r['data'][$same_product_count]['seller_state'] = $order_header_data[$j]->seller_city;
                        $r['data'][$same_product_count]['colour'] = $order_header_data[$j]->colour;
                        $r['data'][$same_product_count]['size'] = $order_header_data[$j]->size;
                        $r['data'][$same_product_count]['product_name'] = $order_header_data[$j]->product_name;
                        $r['data'][$same_product_count]['product_qty'] = $order_header_data[$j]->product_qty;
                        $r['data'][$same_product_count]['price'] = $order_header_data[$j]->price;
                        $r['data'][$same_product_count]['thumb_img'] = $CI->config->item('PRODUCT_IMG_PATH') . $pro_img;
                    }
                    $arr[$i]['product_details'][] = $r;
                }
                $order_id[$j] = $order_header_data[$j]->order_id;
            }
            if (isset($params['app'])) {
                $result['status'] = 'Success';
                $result['msg'] = 'User Order Details.';
                $result['data']['count'] = count($arr);
                $result['data']['list'] = $arr;
            } else {
                $result['status'] = 'SUCCESS';
                $result['msg'] = 'User Order Details.';
                $result['count'] = count($arr);
                $result['data'] = $arr;
            }
        } else {
            $result['status'] = "Failed";
        }
        return $result;
    }
    
    public function ordertrackingdetails($params) {
        $CI = & get_instance();
        $CI->load->model('order_model');
        $CI->load->library('validation');
        $CI->load->config('custom-config');
        $result = $CI->validation->validate_order_details($params);
        if ($result['status'] == 1) {
            $order_header_data = $CI->order_model->getDataByOrderId_trackingdetails($params);
            if($order_header_data){
               $data = array();
               $data['order_id'] = $order_header_data[0]->order_id;
               $data['order_date'] = $order_header_data[0]->timestamp;
               $data['order_status'] = $order_header_data[0]->status;
               $data['brand_id'] = $order_header_data[0]->store_id;
               $data['brand_name'] = $order_header_data[0]->store_name;
               $products = array();
               foreach ($order_header_data as $key => $val) {
                   $products[$key]['product_id'] = $val->subscribed_product_id;
                   $products[$key]['product_qty'] = $val->product_qty;
                   $products[$key]['dispatch_qty'] = $val->qty;
                   $products[$key]['remaining_qty'] = $val->product_qty - $val->qty;
                   $products[$key]['tracking_id'] = $val->track_id;
                   $products[$key]['status'] = $val->status;
                   $products[$key]['product_id'] = $val->subscribed_product_id;
               }
               $data['dispatched_item_list'] = $products;
               $result['status'] = 'Success';
               $result['msg'] = 'Order tracking Details.';
               $result['data'] = $data;
            }else{
                $result['status'] = 'Fail';
                $result['msg'] = 'Unable to fetch tracking.';
                $result['errors'] = 'No data found';
            }
        } else {
            $result['status'] = "Failed";
        }
        return $result;
    }
}
