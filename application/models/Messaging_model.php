<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Messaging_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function send_otp_sms($randomNumber = '', $mobile_number = '',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($randomNumber)) {
            $SMS_BODY = SMS_CONTENT;
            if(isset($SMS_BODY['otp']) && !empty($SMS_BODY['otp'][$lang])){
                $sms_body_content = $SMS_BODY['otp'][$lang];
                $sms_body_content = str_replace('$$OTP$$', $randomNumber, $sms_body_content);
                $mobile_number_array[] = $mobile_number;
                $this->do_sms($sms_body_content, $mobile_number_array);
            }
        }
    }

   function user_registeration_successful( $mobile_number = '',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number)) {
            $SMS_BODY = SMS_CONTENT;
            if(isset($SMS_BODY['registration']) && !empty($SMS_BODY['registration'][$lang])){
                $sms_body_content = $SMS_BODY['registration'][$lang];
                $mobileLast9Digit = $mobile_number;
                if(strlen($mobile_number) > 9 ){
                    $mobileLast9Digit = substr($mobile_number, -9);
                }
                $mobile_no_with_code = '249'.$mobileLast9Digit;
                
                $mobile_number_array[] = $mobile_no_with_code;
                $this->do_sms($sms_body_content, $mobile_number_array);
            }
        }
    }
    
    function sms_delivery_pickup($sale_code,$shipping_coordinates='',$mobile_number = '', $dateTimeslots = '',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($shipping_coordinates) && !empty($sale_code) && !empty($dateTimeslots)) {
            $SMS_BODY = SMS_CONTENT;
            if(isset($SMS_BODY['assign_delivery']) && !empty($SMS_BODY['assign_delivery'][$lang])){
                $sms_body_content = $SMS_BODY['assign_delivery'][$lang];
                $sms_body_content = str_replace('$$order_no$$', $sale_code, $sms_body_content);
                $shipping_coordinates = explode(',',$shipping_coordinates);
                $shipping_coordinates = implode(',',array_reverse($shipping_coordinates));
                $sms_body_content = str_replace('$$address$$', $shipping_coordinates, $sms_body_content);
                $sms_body_content = str_replace('$$dateTimeslot$$', $dateTimeslots, $sms_body_content);
                $mobile_number_array[] = $mobile_number;
                $this->do_sms($sms_body_content, $mobile_number_array);
            }
        }
    }
        
    function sms_delivery_status($sale_code,$mobile_number = '',$sms_type='',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($sms_type) && !empty($sale_code)) {
            $SMS_BODY = SMS_CONTENT;
            if(isset($SMS_BODY[$sms_type]) && !empty($SMS_BODY[$sms_type][$lang])){
                $sms_body_content = $SMS_BODY[$sms_type][$lang];
                $sms_body_content = str_replace('$$order_no$$', $sale_code, $sms_body_content);
                $mobile_number_array[] = $mobile_number;
                $this->do_sms($sms_body_content, $mobile_number_array);
            }
        }
    }

    function sms_delivery_code($sale_code,$verification_code,$mobile_number = '',$sms_type='',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($sms_type) && !empty($sale_code)) {
            $SMS_BODY = SMS_CONTENT;
            if(isset($SMS_BODY[$sms_type]) && !empty($SMS_BODY[$sms_type][$lang])){
                $sms_body_content = $SMS_BODY[$sms_type][$lang];
                $sms_body_content = str_replace('$$order_no$$', $sale_code, $sms_body_content);
                $sms_body_content = str_replace('$$verification_code$$', $verification_code, $sms_body_content);
                $mobile_number_array[] = $mobile_number;
                $this->do_sms($sms_body_content, $mobile_number_array);
            }
        }
    }
    
    function sms_order_placed($sale_code,$mobile_number = '',$verification_code='',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($sale_code)) {
            $SMS_BODY = SMS_CONTENT;
            if(isset($SMS_BODY['order_placed']) && !empty($SMS_BODY['order_placed'][$lang])){
                $sms_body_content = $SMS_BODY['order_placed'][$lang];
                $sms_body_content = str_replace('$$order_no$$', $sale_code, $sms_body_content);
                $sms_body_content = str_replace('$$varification_code$$', $verification_code, $sms_body_content);
               
                $mobileLast9Digit = $mobile_number;
                if(strlen($mobile_number) > 9 ){
                    $mobileLast9Digit = substr($mobile_number, -9);
                }
                $mobile_no_with_code = '249'.$mobileLast9Digit;
                $mobile_number_array[] = $mobile_no_with_code;
                $this->do_sms($sms_body_content, $mobile_number_array);
            }
        }
    }
    
    function sms_order_cancelled($sale_code,$mobile_number = '',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($sale_code)) {
            $SMS_BODY = SMS_CONTENT;
            if(isset($SMS_BODY['order_cancel']) && !empty($SMS_BODY['order_cancel'][$lang])){
                $sms_body_content = $SMS_BODY['order_cancel'][$lang];
                $sms_body_content = str_replace('$$order_no$$', $sale_code, $sms_body_content);
                $mobileLast9Digit = $mobile_number;
                if(strlen($mobile_number) > 9 ){
                    $mobileLast9Digit = substr($mobile_number, -9);
                }
                $mobile_no_with_code = '249'.$mobileLast9Digit;
                $mobile_number_array[] = $mobile_no_with_code;
                $this->do_sms($sms_body_content, $mobile_number_array);
            }
        }
    }
    
    function do_sms($sms_body = '', $mobile_number = array()) {
        $this->load->library('mypcotSMS');
        $this->mypcotsms->triggerOTPSMS($sms_body, $mobile_number);
    }

    
    
    public function sendFcmNotification($fcm_details = array()){
        if(is_array($fcm_details) && !empty($fcm_details[0])){
            $customer_auth_token = array(
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
            
            $delivery_auth_token = array(
                    'Authorization: key=' . DELIVERY_API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
                
            foreach($fcm_details as $key => $val){
                
                if($val['for'] == 'delivery'){
                    $auth_token = $delivery_auth_token;
                }else{
                    $auth_token = $customer_auth_token;
                }

                
                //FCM MSG DATA
                $data_array['title']        = $val['title'];
                $data_array['body']         = $val['body'];
                $data_array['sound']        = "default";
                if($val['for'] == 'custom'){
                    $data_array['click_action'] ="FCM_PLUGIN_ACTIVITY";
                }
                //FCM ID 
                $device_array = $val['fcm_id'];
                //IRIS No
                $sale_id = array('sale_id' => $val['id']);

                $fields = array(
                    'to'  =>   $device_array,
                    'notification'      => $data_array,
                    'data'              => $sale_id,
                );
                
                echo "<pre>";
                print_r($fields);
               // exit;
          
               $url = 'https://fcm.googleapis.com/fcm/send'; 
               $is_post = true;
               $postdata= json_encode( $fields ) ;

               $result =  $this->callingToCurl($auth_token,$is_post,$postdata);
               $data_res = json_decode($result, TRUE);
               
               $send_array = array(
                    'success'	=> $data_res['success'],
                    'failure'	=> $data_res['failure']
                );
              echo json_encode($send_array);
                
               
            }
        }
    }
    //ADDED BY SAGAR : END 6-12
    
    
    function callingToCurl( $auth_token, $is_post = false, $post_data=array()){
        $url="https://fcm.googleapis.com/fcm/send";
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL,$url );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        if(is_array($auth_token)){
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $auth_token );
        }
        if($is_post){
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, $post_data );
        }
        $result = curl_exec($ch );
        curl_close( $ch );
        
        return $result;
        
    }


}
