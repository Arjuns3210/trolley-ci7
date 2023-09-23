<?php

class Delivery_boy_apis extends CI_Controller {

    public $mobile_check = '/^[0-9]{10}+$/';
//    public $mobile_check = '/^(\+|\d)[0-9]{5,15}$/';

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization, x-access-token');
        header('Access-Control-Allow-Origin: *');
        $this->load->library('form_validation');
        $this->load->library('jwttoken');
        $this->load->library('mypcotmaak');
        $this->load->model('apis_models1');
        $this->load->model('messaging_model');
    }



    private function read_header() {
        $header_data = $_SERVER;
        if (is_array($header_data) && isset($header_data['PHP_AUTH_USER']) && isset($header_data['PHP_AUTH_PW']) && $header_data['PHP_AUTH_USER'] == DELIVERY_AUTH_USER && $header_data['PHP_AUTH_PW'] == DELIVERY_AUTH_PW) {
            return true;
        } else {
            return false;
        }
    }

    private function successMessage($msg_name = '', $data = array(), $lang = "ar", $other_data = array(),$replaceText = "") {
        //get the data from DB
        $field = ($lang == 'en') ? 'display_value_en' : 'display_value_ar';
        $msg = $this->db->get_where('display_msg', array('display_name' => $msg_name))->row()->$field;
        $return_array = array();
        $return_array['success'] = '1';
        if( $replaceText !== ""){
            $msg =  str_replace( '$$text$$', $replaceText, $msg );
        }
        $return_array['message'] = $msg;
       
        if (isset($data) && count($data) > 0)
            $return_array['data'] = $data;
        if (isset($other_data) && !empty($other_data)) {
            foreach ($other_data as $key => $val)
                $return_array[$key] = $val;
        }

        echo json_encode($return_array);
        exit();
    }

    private function errorMessage($msg_name = '', $data = array(), $lang = "ar",$other_data = array(),$replaceText = "",$expireSessionCode="") {
        //get the data from db
        $field = ($lang == 'en') ? 'display_value_en' : 'display_value_ar';
        $msg = $this->db->get_where('display_msg', array('display_name' => $msg_name))->row()->$field;
        $return_array = array();
        $return_array['success'] = '0';
        if(!empty($expireSessionCode)){
            $return_array['success'] =  $expireSessionCode;
        }
        
        if( $msg_name== "invalid_verification_code"){
            $msg =  str_replace( '$$wrongAttempts$$', $replaceText, $msg );
            $msg =  str_replace( '$$maxAttempts$$', WRONG_VERIFICATION_COUNTS, $msg );
        }
        $return_array['message'] = $msg;

        if (isset($data) && count($data) > 0)
            $return_array['data'] = $data;
        
        if (isset($other_data) && !empty($other_data)) {
            foreach ($other_data as $key => $val)
                $return_array[$key] = $val;
        }

        echo json_encode($return_array);
        exit();
    }

    private function read_header_token($userLanguage='ar') {
        $header_data = getallheaders();
        $msg_data = array();
        
        if (is_array($header_data) && isset($header_data['X-Access-Token']) && !empty($header_data['X-Access-Token'])) {
            $token = $this->jwttoken->validateToken($header_data['X-Access-Token']);

            if (is_array($token)) {
                $user_id = $token['uid'];
                $access_token = $header_data['X-Access-Token'];
                $check_condition = ' admin_id = ' . $this->db->escape($user_id);
                $check_condition .= ' AND access_token = ' . $this->db->escape($access_token);
                $merchantExist = $this->apis_models1->getData('admin_id', 'admin', $check_condition);
                
                if (!is_array($merchantExist)) {
                    $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage ,'','',4);
                    exit();
                }
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //used for login of delivery boy
    public function processLogin(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = "ar";
            // $language_id      = '002';
            $msg_data = $city_array_data = $area_array_data = $ts_array_data = array();
            if($this->read_header()){
            $username = null;
            $password     = null;
            $userLanguage = 'ar';
            $fcm_id = '';
            $version_allowed = TRUE;
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
                $userLanguage = $_POST['userLanguage'];
            }
            
            if ( isset( $_POST['username'] ) && ! empty( $_POST['username'] ) ) {
                $username = $_POST['username'];
                if (( preg_match($this->mobile_check, $_POST['username']))) {
                        $username = $_POST['username'];
                 } else {
                     $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                 }
            } else {
                $this->errorMessage('please_enter_username', $msg_data, $userLanguage);
            }
            
            if ( isset( $_POST['password'] ) && ! empty( $_POST['password'] ) ) {
                    $password =  sha1($_POST['password']);
            } else {
                $this->errorMessage('please_enter_password', $msg_data, $userLanguage);
            }
            
            if (isset($_POST['fcm_token']) && ! empty($_POST['fcm_token'])) {
                $fcm_id = $_POST['fcm_token'];
            }
            
            if (isset($_POST['app_version']) && ! empty($_POST['app_version'])) {
                $app_version = $_POST['app_version'];

                $allowed_version = $this->db->get_where('general_settings',array('type' => 'delivery_andriod_allowed'))->row()->value;
                $allowed_version = json_decode($allowed_version,TRUE);
                $version_allowed = in_array($app_version, $allowed_version);
            }
            
            $login_user = $this->apis_models1->deliveryBoyValidate( $username, $password);
            
            
            
            if ( is_array( $login_user ) ) {
                    $user_id = $login_user[0]['admin_id'];
                    $status = $login_user[0]['status'];
                    if($status != 'Active'){
                        $this->errorMessage('incorrect_username_or_password', $msg_data, $userLanguage);
                    }else{
                        $users = array();
                        $arrayKey = 0;
                        foreach ( $login_user as $key => $val ) {
                                $users[ $key ]['name']       = $val['name'];
                                $users[ $key ]['phone']      = $val['phone'];
                                $users[ $key ]['address']    = $val['address'];
                                $users[ $key ]['email']      = $val['email'];
                                $users[ $key ]['city_id']    = $val['city_id'];
                                $users[ $key ]['area_id']    = $val['area_ids'];
                                $arrayKey = $key;
                        }
                        $city_id = $login_user[0]['city_id'];
                        $area_id = $login_user[0]['area_ids'];
                        $city_array_data = $this->crud_model->getDeliveryBoyCityData($city_id);
                        
                        if(!empty($area_id) && isset($area_id['0'])){
                            $area_condition_db = json_decode($area_id,TRUE);
                            $area_condition_id = implode(",", $area_condition_db);
                            $area_array_data = $this->crud_model->getDeliveryBoyAreaData($area_condition_id);
                            
                        }
                        
                        $users[$arrayKey]['cities'] = $city_array_data;
                        $users[$arrayKey]['areas'] = $area_array_data;
                        $users[$arrayKey]['allowed_version'] = $version_allowed;
                              
                        
                        //getting the timeslots for the user start
//                        $ts_condition = $this->getTimeslotsDates(1,$user_id);
//                        $distinct_timeslots = $this->apis_models1->getDeliveryBoyDistinctTimeslots($ts_condition);
//                        if(is_array($distinct_timeslots) && isset($distinct_timeslots)){
//                            foreach($distinct_timeslots as $tskey=>$timeslotval){
//                                $delivery_date_timeslot = $timeslotval['delivery_date_timeslot'];
//                                $assigned_count = $timeslotval['assigned_count'];
//                                
//                                $delivery_date_timeslot_json = json_decode($delivery_date_timeslot,TRUE);
//                                
//                                $ts_array_data[$tskey]['delivery_count']=$assigned_count;
//                                $ts_array_data[$tskey]['delivery_date']=$delivery_date_timeslot_json['0']['date'];
//                                $ts_array_data[$tskey]['delivery_timeslot']=$delivery_date_timeslot_json['0']['timeslot'];
//                                $ts_array_data[$tskey]['date_timeslot']=$delivery_date_timeslot_json['0']['date'].' '.$delivery_date_timeslot_json['0']['timeslot'];
//                            }
//                        }
//                        $users[$arrayKey]['timeslots'] = $ts_array_data;
                        //getting the timeslots for the user end
                        
                        //PASSING JWT TOKEN ON LOGIN USER
                        $token = $this->jwttoken->createToken($user_id);
                        $users[$arrayKey]['token'] = $update_array['access_token'] =  $token;
                        $update_array['last_login_time'] =  date('Y-m-d H:i:s');
                        if(!empty($fcm_id))
                            $update_array['fcm_id'] =  $fcm_id;
                        $token_condition = 'admin_id = ' . $this->db->escape( $user_id );
                        $this->apis_models1->updateRecord( 'admin', $update_array, $token_condition );
                        
                        $all_delivered = $this->getOrderCount($user_id,-1,'delivered');
                        $all_pending = $this->getOrderCount($user_id,-1,'process');
                        $users[$arrayKey]['orderCount']['all_delivered'] = $all_delivered;
                        $users[$arrayKey]['orderCount']['all_pending'] = $all_pending;
                        $users[$arrayKey]['orderCount']['total_orders'] = $all_pending + $all_delivered;
                        
                        $this->successMessage('logged_in_successfully', $users, $userLanguage);
                    }
                    
            } else {
                    //some error ocuured during login - pin or mob No is wrong
                    $this->errorMessage('incorrect_username_or_password', $msg_data, $userLanguage);
            }
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    public function viewProfile(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $msg_data = array();
        $check_condition = " 1=1 And admin.status='Active'";
        $token_array = $this->read_header_token($userLanguage);
        if ($this->read_header() && is_array($token_array)) {
            $check_condition .= ' AND admin.admin_id = '.$this->db->escape($token_array['uid']);
            $admin_details = $this->apis_models1->getProfile('name,phone,address,email,city.city_name_en,city.city_name_ar,area.area_name_en,area.area_name_ar', 'admin', $check_condition);
            
            if(!is_array($admin_details[0])){
                 $this->errorMessage('user_not_found', $msg_data, $userLanguage);
             }
            $order_count = $this->getTotalOrderCount($token_array['uid'],'processed','delivered');
 
            $data = array();
            if(is_array($admin_details[0])){
                $data['name'] = $admin_details[0]['name'];
                $data['phone'] = $admin_details[0]['phone'];
                $data['address'] = $admin_details[0]['address'];
                $data['email'] = $admin_details[0]['email'];
                $data['city_name'] = $admin_details[0]['city_name_'.$userLanguage];
                $data['area_name'] = $admin_details[0]['area_name_'.$userLanguage];
                $data['total_completed_order'] = $order_count;
            }
                    
            $responseData =  array(
                'data'=>$data,
            );
            $this->successMessage('data_fetched_successfully',array(), $userLanguage,$responseData);
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    
    
    //used for chnage password of delivery boy after login
    public function processChangePassword() {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
            $_POST = $data;
        }

        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }

        $token_array = $this->read_header_token($userLanguage);
        $msg_data = array();
        $new_password  = $old_password = '';
        $mobile_no     = '';
        $check_condition = " 1=1 And status='Active' ";
            if($this->read_header()  && is_array($token_array)){
                $check_condition .= ' AND admin_id = '.$this->db->escape($token_array['uid']);
                
                
                

                //cehck if user entered mobNo,password and Device details are correct
                if ( isset( $_POST['old_password'] ) && ! empty( $_POST['old_password'] ) ) {
                    $old_password =  $_POST['old_password'];
                    
                } else {
                    $this->errorMessage('please_enter_old_password', $msg_data, $userLanguage);
                }
        
                //cehck if user entered mobNo,password and Device details are correct
                $data = $this->apis_models1->getData('i.admin_id,i.password,i.phone','admin',$check_condition);
                if(!is_array($data[0])){
                   //Invalid details - so pls try gain later
                    $this->errorMessage('incorrect_authentication_password', $msg_data, $userLanguage);
                }



                $mobile_no = $data['0']['phone'];
                $password_db = $data['0']['password'];
                $old_password = sha1($old_password);

                if($old_password != $password_db){
                    $this->errorMessage('password_not_match', $msg_data, $userLanguage);
                }
                
                if ( isset( $_POST['new_password'] ) && ! empty( $_POST['new_password'] ) ) {
                    $new_password =  $_POST['new_password'];
                    $password = sha1($new_password);
                } else {
                    $this->errorMessage('please_enter_new_password', $msg_data, $userLanguage);
                }
                
                $update_array = array('password'=>$password);
                $otp_id = $this->apis_models1->updateRecord('admin',$update_array,$check_condition);
                if(!empty($otp_id) && isset($otp_id)){
                    //Password updated successfully
                     $this->successMessage('password_changed_successfully', $msg_data, $userLanguage); 
                }else{
                    //some error ocurred try gain later
                     $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
                }
            }else{
                $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
            }
    }
    
    
    public function refreshFcmToken() {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
            $_POST = $data;
        }

        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }

        $token_array = $this->read_header_token($userLanguage);
        $msg_data = array();
        $new_password  = $old_password = '';
        $mobile_no     = '';
        $check_condition = " 1=1 And status='Active' ";
            if($this->read_header()  && is_array($token_array)){
                $check_condition .= ' AND admin_id = '.$this->db->escape($token_array['uid']);
                
                if (isset($_POST['fcm_token']) && ! empty($_POST['fcm_token'])) {
                    $fcm_id = ($_POST['fcm_token']);
                    $update_array = array('fcm_id'=>$fcm_id);
                    $otp_id = $this->apis_models1->updateRecord('admin',$update_array,$check_condition);
                    if(!empty($otp_id) && isset($otp_id)){
                        $this->successMessage('user_info_update_successfully', $msg_data, $userLanguage); 
                    }else{
                        $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
                    }
                }else{
                    $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
                }
            }else{
                $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
            }
    }

    //FORGET PASSWORD Request OTP FLOW
    public function requestOtp(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        
        $msg_data = array();
        $mobile_no     = '';
        $userLanguage = 'ar';
        $currency_code = "2";
        //User choice userLanguage  : START
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }

        //User choice userLanguage  : END
        if($this->read_header()){
            if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {
                if (( preg_match($this->mobile_check, $_POST['phone_number']))) {
                    $mobile_no = $_POST['phone_number'];
                    $mobileExist = $this->apis_models1->verify_if_unique('admin', 'phone = ' . $this->db->escape($mobile_no));
                    if (!is_array($mobileExist)) {
                        $this->errorMessage('user_not_registered_with_given_mobile_number', $msg_data, $userLanguage);
                    }
                } else {
                    $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
            }
            $randomNumber = $this->generateRandomOTP();
            //trigger this random number in sms to user
            $mobileLast9Digit = $mobile_no;

            if(strlen($mobile_no) > 9 ){
                $mobileLast9Digit = substr($mobile_no, -9);
            }

            $mobile_no_with_code = '249'.$mobileLast9Digit;
            // Send SMS From Here                   
            $this->messaging_model->send_otp_sms($randomNumber,$mobile_no_with_code);

            $MobileExistArray = $this->db->get_where('admin',array('phone'=>$mobile_no))->row_array();

         // OLD FLOW 
            if(is_array($MobileExistArray)){
                $update_array = array(
                    'fpwd_key' => $randomNumber,
                    'fpwd_flag' => 'Active'
                );
                $condition= 'phone = '. $this->db->escape($mobile_no);
                $otp_id = $this->apis_models1->updateRecord('admin',$update_array,$condition);
            }else{
                $this->errorMessage('user_not_registered_with_given_mobile_number', $msg_data, $userLanguage);
            }

            if(!empty($otp_id) && isset($otp_id)){
                 $this->successMessage('otp_delivered', $msg_data, $userLanguage); 
            }else{
                $this->errorMessage('error_while_sending_otp', $msg_data, $userLanguage);
                exit();
            }
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }

    public function validateOtp(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if(!empty($data) && isset($data)){
            $_POST = $data;
        }
        $msg_data = array();
        $mobile_no = $otp_code    = '';
        $userLanguage = 'ar';
        
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        if($this->read_header()){
            if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {
                if (( preg_match($this->mobile_check, $_POST['phone_number']))) {
                    $mobile_no = $_POST['phone_number'];
                } else {
                    $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
            }

            if(!empty($_POST['otp_code']) && isset($_POST['otp_code'])){
                $otp_code = $_POST['otp_code'];
                $condition =  '1=1 ';
                $condition .=  ' AND i.fpwd_key = '. $this->db->escape($otp_code);
                $condition .=  ' AND i.fpwd_flag = "Active"';
                $condition .=  ' AND i.phone = '. $this->db->escape($mobile_no);
                $data = $this->apis_models1->getData('i.*','admin',$condition);
                if(!empty($data[0]) && is_array($data[0])){
                   $this->successMessage('otp_verified', $msg_data, $userLanguage); 
                }else{
                    $this->errorMessage('invalid_otp', $msg_data, $userLanguage);
                }
            }else{
                 $this->errorMessage('please_enter_otp_code', $msg_data, $userLanguage);
            }
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    
    public function forgetPassword(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if(!empty($data) && isset($data)){
            $_POST = $data;
        }
        $msg_data = array();
        $mobile_no = $otp_code    = '';
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        if($this->read_header()){
            if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {
                if (( preg_match($this->mobile_check, $_POST['phone_number']))) {
                    $mobile_no = $_POST['phone_number'];
                } else {
                    $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
            }
            
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                 if(strlen($_POST['password']) < 4 ){
                    $this->errorMessage('password_length_error', $msg_data, $userLanguage);
                }
                $password = sha1($_POST['password']);
            } else {
                $this->errorMessage('please_enter_password', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
                if($_POST['password'] != $_POST['confirm_password']){
                    $this->errorMessage('password_not_match', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_confirm_password', $msg_data, $userLanguage);
                exit();
            }

            if(!empty($_POST['otp_code']) && isset($_POST['otp_code'])){
                $otp_code = $_POST['otp_code'];
                $condition =  '1=1';
                $condition .=  ' AND i.fpwd_key = '. $this->db->escape($otp_code);
                $condition .=  ' AND i.fpwd_flag = "Active"';
                $condition .=  ' AND i.phone = '. $this->db->escape($mobile_no);
                $data = $this->apis_models1->getData('i.*','admin',$condition);
                if(!empty($data[0]) && !is_array($data[0])){
                    $this->errorMessage('invalid_otp', $msg_data, $userLanguage);
                }
            }else{
                 $this->errorMessage('please_enter_otp_code', $msg_data, $userLanguage);
            }
            $updatePasswordData = array('password' => $password);
            $check_condition = ' phone = ' . $this->db->escape($mobile_no);
            $this->apis_models1->updateRecord('admin', $updatePasswordData, $check_condition);
            $this->successMessage('password_changed_successfully', $msg_data, $userLanguage); 
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
      
    private function generateRandomOTP(){
        if(PROJECT_ENVIRONMENT == 'production'){
            return (rand(1000,9999));
        }else{
            return (1234);
        }
    }
    
    private function send_otp_sms($randomNumber = '', $mobile_number = '',$lang='en') {
        if (isset($mobile_number) && !empty($mobile_number) && !empty($randomNumber)) {
            $SMS_BODY = SMS_CONTENT;
            $sms_body = $SMS_BODY['otp'][$lang];
            $sms_body = str_replace('$$OTP$$', $randomNumber, $sms_body);
            $mobile_number_array[] = $mobile_number;
            //$this->load->library('mypcotSMS');
            //$this->mypcotsms->triggerOTPSMS($sms_body, $mobile_number_array);
        }
    }
    //FORGET PASSWORD OTP FLOW : END


    public function orderStatusCount(){
        // sale
        $pending = $all_revenue = $today_revenue = 0;
        $all_pending_count = $all_pending_revenue = $all_process_count = 
        $all_process_revenue = $all_delivered_count = $all_delivered_revenue = 0;

        $today_delivered_count = $today_delivered_revenue = $today_process_count = 
        $today_process_revenue = $today_pending_count = $today_pending_revenue = 0;
        $data = json_decode( file_get_contents( 'php://input' ), true);

        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = "ar";

        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $token_array = $this->read_header_token($userLanguage);
        $msg_data = array();

        if($this->read_header()  && is_array($token_array)){
            $user_id = $token_array['uid'];
            $return_array = array();

            $today_revenue = $this->getRevenueCount($user_id,0,'delivered');
            $today_delivered_count = $today_revenue['count'];
            $today_delivered_revenue = $today_revenue['revenue'];

            $today_process = $this->getRevenueCount($user_id,0,'process');
            $today_process_count = $today_process['count'];
            $today_process_revenue = $today_process['revenue'];

            $today_pending = $this->getRevenueCount($user_id,0,'pending');
            $today_pending_count = $today_pending['count'];
            $today_pending_revenue = $today_pending['revenue'];

            //should return count only for today
            $timeslots_array = $this->getTimeslotsCount($user_id,60);
            $timeslots_array_unique = array_unique(array_column($timeslots_array, 'delivery_timeslot'));

            //processed orders till date
            $all_revenue = $this->getRevenueCount($user_id,-1,'delivered');
            $all_delivered_count = $all_revenue['count'];
            $all_delivered_revenue = $all_revenue['revenue'];

            $all_process = $this->getRevenueCount($user_id,-1,'process');
            $all_process_count = $all_process['count'];
            $all_process_revenue = $all_process['revenue'];

            $all_pending = $this->getRevenueCount($user_id,-1,'pending');
            $all_pending_count = $all_pending['count'];
            $all_pending_revenue = $all_pending['revenue'];

            $return_array['pending_orders'] = $today_pending_count + $today_process_count;
            $return_array['delivered_orders'] = $today_delivered_count;
            $return_array['assigned_orders'] = $today_delivered_count + $today_pending_count + $today_process_count;
            $return_array['today_revenue'] = $today_delivered_revenue;
            $return_array['today_pending_order_revenue'] = $today_process_revenue + $today_pending_revenue;
            $return_array['today_all_revenue'] = $today_delivered_revenue + $today_process_revenue + $today_pending_revenue;

            $return_array['all_delivered'] = $all_delivered_count;
            $return_array['all_pending'] = $all_pending_count + $all_process_count;
            $return_array['total_orders'] = $all_pending_count + $all_process_count +$all_delivered_count;
            $return_array['all_revenue'] = $all_delivered_revenue;
            $return_array['all_pending_order_revenue'] = $all_process_revenue + $all_pending_revenue;

            $return_array['timeslots'] = array_values($timeslots_array_unique);
            $return_array['show_message'] = DELIVERY_BOY_MSG;
            $return_array['message_value'] = 'Designed and developed by Mypcot';
            $return_array['click_url'] = 'https://www.mypcot.com';

            $this->successMessage('data_fetched_successfully',$return_array,$userLanguage);
        } else {
            $this->errorMessage('please_login_and_try_again',$msg_data,$userLanguage);
        }
    }
    

    // Pending Orders list to apis
    public function getOrders() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }

        $user_id = null;
        $msg_data = $phone_number = $email = $fourth_name = $db_address_number = $second_name =  $third_name = $title  = $langlat  = $address_number = $city = $area = '';
        $limit = 10;
        $page =  $totalRecords =  0;
        $sortBy = 'sale_id';
        $orderBy = 'desc';
        $check_condition = " 1=1 ";
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        $showMap = TRUE;
        $showDialer = TRUE;
        $showInvoice = TRUE;
        $showReceipt= TRUE;
        $sysDate= date('Y-m-d',strtotime("-300 days"));
        $click_allowed = "Y";
        //user choice : START
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        //user choice : END

        $token_array = $this->read_header_token($userLanguage);
        if ($this->read_header() && is_array($token_array)) {
            $user_id = $admin_id= $token_array['uid'];

            $user_id = '"admin_id":"'.$user_id.'"';
            $pending_status = '"status":"pending"';
            $process_status = '"status":"process"';

            $condition = " 1=1 And assign_delivery_data like '%".$user_id."%' And (delivery_status like '%".$pending_status."%' OR delivery_status like '%".$process_status."%')"; 
            //order details
            if (isset($_POST['page_no']) && is_numeric($_POST['page_no'])) {
                $page = $_POST['page_no'];
            }
            
            if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {
                $limit = $_POST['limit'];
            }
            
            if (isset($_POST['sort']) && !empty($_POST['sort'])) {
                $sortBy = $_POST['sort'];
            }
            
            if (isset($_POST['order']) && !empty($_POST['order'])) {
                $orderBy = $_POST['order'];
            }
            
            if (isset($_POST['order_status']) && !empty($_POST['order_status'])) {
                $order_status = $_POST['order_status'];
                $condition .= " AND order_status =".$this->db->escape($order_status);
            }
            
            if (isset($_POST['notification_data']) && is_numeric($_POST['notification_data'])) {
                $sale_id = $_POST['notification_data'];
                $condition .= " AND sale_id =".$this->db->escape($sale_id);
            }
            
            if (isset($_POST['delivery_date']) && !empty($_POST['delivery_date'])) {
                $delivery_date = date('Y-m-d',strtotime($_POST['delivery_date']));
                $db_encoded_delivery_date = '"date":"'.$delivery_date.'"';
                $condition .= " AND delivery_date_timeslot like '%".$db_encoded_delivery_date."%' ";
            }
            
            if (isset($_POST['delivery_timeslots']) && !empty($_POST['delivery_timeslots'])) {
                $delivery_timeslots = $_POST['delivery_timeslots'];
                $delivery_timeslots_array = explode("|", $delivery_timeslots);
                if(isset($delivery_timeslots_array[0]) && isset($delivery_timeslots_array[1])){
                    $delivery_date = $delivery_timeslots_array[0];
                    $delivery_slots = $delivery_timeslots_array[1];
                    
                    $delivery_date_timeslot_array[] = array(
                        'date' => $delivery_date,
                        'timeslot' => $delivery_slots,
                    );
                    $delivery_date_timeslot_encoded = json_encode($delivery_date_timeslot_array);
                    $condition .= " AND delivery_date_timeslot like '%".$delivery_date_timeslot_encoded."%' ";
                }else{
                    $db_encoded_delivery_timeslot = '"timeslot":"'.$delivery_timeslots.'"';
                    $condition .= " AND delivery_date_timeslot like '%".$db_encoded_delivery_timeslot."%' ";
                }
            }
            
            if (isset($_POST['payment_status']) && !empty($_POST['payment_status'])) {
                $payment_status = $_POST['payment_status'];
                $db_encoded_payment_status = '"status":"'.$payment_status.'"';
                $condition .= " AND payment_status like '%".$db_encoded_payment_status."%' ";
            }
            
            if (isset($_POST['trolley_credit_type']) && !empty($_POST['trolley_credit_type'])) {
                $trolley_credit_type = $_POST['trolley_credit_type'];
                //vip or normal
                $trolley_credit_type = strtolower($trolley_credit_type);
                
                if($trolley_credit_type == 'vip' || $trolley_credit_type == 'normal')
                    $condition .= "and  c.wallet_type=".$this->db->escape($trolley_credit_type);
            }
            
            if (isset($_POST['sort_by_type']) && !empty($_POST['sort_by_type'])) {
                $sort_by_type = $_POST['sort_by_type'];
                //vip or normal
                $sort_by_type = strtolower($sort_by_type);
                if($sort_by_type == 'lowtohigh'){
                    $sortBy = 'grand_total';
                    $orderBy = 'asc';
                }
                    
                if($sort_by_type == 'hightolow'){
                    $sortBy = 'grand_total';
                    $orderBy = 'desc';
                }
                    
            }

            $order_data = $this->apis_models1->getDeliveryBoyOrderdata($condition, $limit, $page, $sortBy, $orderBy);
            
            if (is_array($order_data)) {
                $order = $order_data['query_result'];
                $totalRecords = $order_data['totalRecords'];
                foreach ($order as $key => $value) {
                    
                    $showMap = TRUE;
                    $showDialer = TRUE;
                    $showInvoice = TRUE;
                    $showReceipt= TRUE;
                    $order_cancel_flag =  false;
                    $product_details = json_decode($value['product_details'], true);
                    $cnt = 0;
                    foreach($product_details as $detail) {
                        if(!in_array('status', array_keys($detail))) {
                            $cnt++;
                        }
                    }
                    $orders_data[$key]['product_count'] = $cnt;
                    $sale_id = $value['sale_id'];
                    $orders_data[$key]['sale_id'] = $sale_id;
		    $orders_data[$key]['invoice_url'] = base_url()."deliveryBoyInvoice/".$sale_id;
                    
                    $orders_data[$key]['orders_id'] = $value['sale_code'];
                    $orders_data[$key]['customers_id'] = $value['buyer'];
		    $orders_data[$key]['wallet_type'] = $value['wallet_type'];
                    $orders_data[$key]['attempts_left'] = $attempts_left = WRONG_VERIFICATION_COUNTS - $value['verification_counts'];
                    
                    $orders_data[$key]['date_purchased'] = date("Y-m-d H:i:s", $value['sale_datetime']);
                    $orders_data[$key]['payment_type'] = $payment_type = $value['payment_type'];
                    
                    
                    $orders_data[$key]['delivery_type'] = $delivery_type = $value['delivery_type'];
                    
                    $status = json_decode($value['delivery_status'], true);
                    $payment_status = json_decode($value['payment_status'], true);
                    $delivery_status = json_decode($value['delivery_date_timeslot'], true);
                    
                    $orders_data[$key]['delivery_date'] = $delivery_date = $delivery_status[0]['date'];
                    $orders_data[$key]['delivery_timeslot'] = $delivery_status[0]['timeslot'];
                    $orders_data[$key]['payment_status'] = $payment_status[0]['status'];
                    $orders_data[$key]['delivery_status'] = $delivery_status = $status[0]['status'];
                    $orders_data[$key]['order_status'] = $value['order_status'];
                    
                    // Added by satesh 26_05_2020 Start                    
                    $orders_data[$key]['distinct_suppliers_stores'] = array();
                    if(!empty($value['supplier_store_ids'])){
                        $distinct_supplier_store_ids = $value['supplier_store_ids'];
//                        $distinct_supplier_store_ids = str_replace(",","','",$distinct_supplier_store_ids);
//                        $distinct_supplier_store_ids = "'".$distinct_supplier_store_ids."'";
                        $distinctStoresData = $this->crud_model->getSupplierStoreData($distinct_supplier_store_ids);
                        $orders_data[$key]['distinct_suppliers_stores'] =  $distinctStoresData;
                    } 
                    
                    $delivery_date_timeslots = json_decode($value['delivery_date_timeslot'], true);
                    $delivery_date_in_time = strtotime($delivery_date_timeslots[0]['date']);
                    $current_time =  time();
                    
                    if($value['order_status'] == 'cancelled' || $delivery_date < $sysDate || $attempts_left<=0){
                        $showMap = FALSE;
                        $showDialer = FALSE;
                        $showInvoice = FALSE;
                        $showReceipt= FALSE;
                    }
                    
                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($value['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    //CONVERSION RATE FROM SALE ENTRY 

                    
                    $delivery_address = json_decode($value['shipping_address'],TRUE);
                    if ($delivery_type == 'homeDelivery') {
                        if($showDialer && isset($delivery_address['phone_number'])){
                            $db_phone_number = $delivery_address['phone_number'];
                            $phone_number = maskMobileNumber($db_phone_number);
                        }

                        if(isset($delivery_address['email'])){
                            $db_email = $delivery_address['email'];
                            $email = maskEmailAddress($db_email);
                        }

                        if(isset($delivery_address['first_name']) && !empty($delivery_address['first_name'])){
                             $first_name = $delivery_address['first_name'];
                        }

                        if(isset($delivery_address['fourth_name']) && !empty($delivery_address['fourth_name'])){
                             $fourth_name = $delivery_address['fourth_name'];
                        }


                        if($showMap && isset($delivery_address['langlat']) && !empty($delivery_address['langlat'])){
                             $shipping_coordinates = $delivery_address['langlat'];
                             $shipping_coordinates_array = explode(',',$shipping_coordinates);
                             $langlat = implode(',',array_reverse($shipping_coordinates_array));
                        }

                        if(isset($delivery_address['address_number']) && !empty($delivery_address['address_number'])){
                             $db_address_number = $delivery_address['address_number'];
                             $address_number = maskMobileNumber($db_address_number);
                             
                             if(!$showDialer){
                                 $db_address_number = '';
                             }
                        }

                        if($showMap && isset($delivery_address['city']) && !empty($delivery_address['city'])){
                             $city = $city_ar = $delivery_address['city'];
                             if($userLanguage == 'ar'){
                                $city = $this->apis_models1->get_type_name_by_id( 'city', $value['city_id'], 'city_name_ar' );
                             }
                             
                        }

                        if($showMap && isset($delivery_address['area']) && !empty($delivery_address['area'])){
                             $area = $area_ar =  $delivery_address['area'];
                              if($userLanguage == 'ar'){
                                   $area = $this->apis_models1->get_type_name_by_id( 'area', $value['area_id'], 'area_name_ar' );
                                }
                        }
                    }

                    $orders_data[$key]['phone_number'] = $phone_number;
                    $orders_data[$key]['email'] = $email;
                    $orders_data[$key]['first_name'] = $first_name;
                    $orders_data[$key]['second_name'] = $second_name;
                    $orders_data[$key]['third_name'] = $third_name;
                    $orders_data[$key]['fourth_name'] = $fourth_name;
                    $orders_data[$key]['langlat'] = $langlat;
                    $orders_data[$key]['title'] = $title;
                    $orders_data[$key]['number'] = $address_number;
                    $orders_data[$key]['dial_number'] = $db_address_number;
                    $orders_data[$key]['city'] = $city;
                    $orders_data[$key]['area'] = $area;
                   
                    
                    $orders_data[$key]['coupons'] = json_decode($value['coupon_detail'], true);
//                                $orders_data[ $key ]['coupons']        = $value['coupon_code'];
                    $orders_data[$key]['coupon_amount'] = get_converted_currency($value['discount_amount'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['products_tax'] = get_converted_currency($value['vat'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['order_price'] = get_converted_currency($value['grand_total'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['invoice_amount'] = get_converted_currency($value['invoice_amount'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['delivery_charge'] = get_converted_currency($value['delivery_charge'], $currency_code,$sale_currency_conversion_rate);
                    
                    $orders_data[$key]['show_map'] = $showMap;
                    $orders_data[$key]['show_call'] = $showDialer;
                    $orders_data[$key]['show_invoice'] = $showInvoice;
                    $orders_data[$key]['show_verify'] = $showReceipt;
                    
                }
                $responseData =  array(
                    'data'=>$orders_data,
                    'total_record'=>$totalRecords,
                );
                $this->successMessage('returned_all_orders',array(), $userLanguage,$responseData);
            } else {
                $this->errorMessage('empty_list_criteria', $msg_data, $userLanguage,$orders_data);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    // order Listing assigned orders list

    // Deliverd Orders
    public function getOrdersHistory() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $user_id = null;
        $limit = 25;
        $page = $totalRecords = 0;
        $sortBy = 'sale_id';
        $orderBy = 'desc';
        $check_condition = " 1=1 ";
        $currency_code = "2";
        $userLanguage = "ar";
        $click_allowed = "N";
        $to_date = $end_date = date('Y-m-d');
        $orders_data = $msg_data = array();
        //user choice : START

        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        //user choice : END
        $token_array = $this->read_header_token($userLanguage);

        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $user_id = '"admin_id":"'.$user_id.'"';
            $delivered_status = '"status":"delivered"';
            $condition = " 1=1 And assign_delivery_data like '%".$user_id."%' And (delivery_status like '%".$delivered_status."%')"; 
            //order details
            if (isset($_POST['page_no']) && is_numeric($_POST['page_no'])) {
                $page = $_POST['page_no'];
            }
            if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {
                $limit = $_POST['limit'];
            }
            if (isset($_POST['sort']) && !empty($_POST['sort'])) {
                $sortBy = $_POST['sort'];
            }
            if (isset($_POST['order']) && !empty($_POST['order'])) {
                $orderBy = $_POST['order'];
            }
            if (isset($_POST['to_date']) && !empty($_POST['to_date'])) {
                $to_date = $_POST['to_date'];
                $to_date = $end_date = date('Y-m-d',strtotime($to_date));
            }
            if (isset($_POST['from_date']) && !empty($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $from_date = $start_date = date('Y-m-d',strtotime($from_date));

                if($from_date > $to_date){
                    $this->errorMessage('from_date_must_be_less',$msg_data,$userLanguage);
                }
                $deliveryDateCondition = " AND ( ";

                while (strtotime($start_date) <= strtotime($end_date)) {
                    $delivery_date = date ("Y-m-d",strtotime($start_date));
                    $deliveryDateCondition .= ' delivery_date_timeslot Like '.'\'%[{"date":"'.$delivery_date.'"%\'';
                    $deliveryDateCondition .= ' OR ';
                    $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
                }
                $deliveryDateCondition = rtrim($deliveryDateCondition, ' OR ');
                $deliveryDateCondition .= ' ) ';

                $condition .= $deliveryDateCondition;
            }

            if (isset($_POST['trolley_credit_type']) && !empty($_POST['trolley_credit_type'])) {
                $trolley_credit_type = $_POST['trolley_credit_type'];
                //vip or normal
                $trolley_credit_type = strtolower($trolley_credit_type);

                if($trolley_credit_type == 'vip' || $trolley_credit_type == 'normal')
                    $condition .= "and  c.wallet_type=".$this->db->escape($trolley_credit_type);
            }

            if (isset($_POST['sort_by_type']) && !empty($_POST['sort_by_type'])) {
                $sort_by_type = $_POST['sort_by_type'];
                //vip or normal
                $sort_by_type = strtolower($sort_by_type);

                if($sort_by_type == 'lowtohigh'){
                    $sortBy = 'grand_total';
                    $orderBy = 'asc';
                }

                if($sort_by_type == 'hightolow'){
                    $sortBy = 'grand_total';
                    $orderBy = 'desc';
                }
            }
            if (isset($_POST['delivery_timeslots']) && !empty($_POST['delivery_timeslots'])) {
                $delivery_timeslots = $_POST['delivery_timeslots'];
                $delivery_timeslots_array = explode("|", $delivery_timeslots);

                if (isset($delivery_timeslots_array[0]) && isset($delivery_timeslots_array[1])) {
                    $delivery_date = $delivery_timeslots_array[0];
                    $delivery_slots = $delivery_timeslots_array[1];
                    $delivery_date_timeslot_array[] = array(
                        'date' => $delivery_date,
                        'timeslot' => $delivery_slots,
                    );
                    $delivery_date_timeslot_encoded = json_encode($delivery_date_timeslot_array);
                    $condition .= " AND delivery_date_timeslot like '%".$delivery_date_timeslot_encoded."%' ";
                } else {
                    $db_encoded_delivery_timeslot = '"timeslot":"'.$delivery_timeslots.'"';
                    $condition .= " AND delivery_date_timeslot like '%".$db_encoded_delivery_timeslot."%' ";
                }
            }
            $order_data = $this->apis_models1->getDeliveryBoyOrderdata($condition, $limit, $page, $sortBy, $orderBy);

            if (is_array($order_data)) {
                $order = $order_data['query_result'];
                $totalRecords = $order_data['totalRecords'];
                foreach ($order as $key => $value) {
                    $sale_id = $value['sale_id'];
                    //$product_id = 0;
                    $orders_data[$key]['click_allowed'] = $click_allowed;
                    $orders_data[$key]['orders_id'] = $value['sale_code'];
                    $orders_data[$key]['sale_id'] = $value['sale_id'];
                    $orders_data[$key]['wallet_type'] = $value['wallet_type'];
                    $orders_data[$key]['date_purchased'] = date("Y-m-d H:i:s", $value['sale_datetime']);
                    $orders_data[$key]['payment_type'] = $payment_type = $value['payment_type'];
                    $orders_data[$key]['delivery_type'] = $delivery_type = $value['delivery_type'];
                    $status = json_decode($value['delivery_status'], true);
                    $payment_status = json_decode($value['payment_status'], true);
                    $orders_data[$key]['payment_status'] = $payment_status[0]['status'];
                    $orders_data[$key]['delivery_status'] = $delivery_status = $status[0]['status'];

                    $delivery_date_timeslots = json_decode($value['delivery_date_timeslot'], true);
                    $orders_data[$key]['delivery_date'] = $delivery_date_timeslots[0]['date'];
                    $orders_data[$key]['delivery_timeslot'] = $delivery_date_timeslots[0]['timeslot'];
                    $orders_data[$key]['delivery_date_timeslot'] = $delivery_date_timeslots[0]['timeslot'];

                    $user_choice = json_decode($value['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];

                    $orders_data[$key]['order_price'] = get_converted_currency($value['grand_total'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['delivery_charge'] = get_converted_currency($value['delivery_charge'], $currency_code,$sale_currency_conversion_rate);
                }
                $responseData =  array(
                    'data'=>$orders_data,
                    'total_record'=>$totalRecords,
                );
                $this->successMessage('returned_all_orders',array(), $userLanguage,$responseData);
            } else {
                $this->errorMessage('empty_list_criteria', $msg_data, $userLanguage,$orders_data);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    
    public function getOrdersRevenue() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $user_id = null;
        $limit = 25;
        $page = $totalRecords = $delivery_amount_in_egp = $delivery_amount_in_usd = 0;
        $sortBy = 'sale_id';
        $orderBy = 'desc';
        $check_condition = " 1=1 ";
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        $click_allowed = "N";
        $to_date = $end_date = date('Y-m-d');
        //user choice : START
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        //user choice : END
        $token_array = $this->read_header_token($userLanguage);

        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];

            $user_id = '"admin_id":"'.$user_id.'"';
            $delivered_status = '"status":"delivered"';
            $condition = " 1=1 And assign_delivery_data like '%".$user_id."%' And (delivery_status like '%".$delivered_status."%')"; 

            //order details
            if (isset($_POST['page_no']) && is_numeric($_POST['page_no'])) {
                $page = $_POST['page_no'];
            }
            if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {
                $limit = $_POST['limit'];
            }
            if (isset($_POST['sort']) && !empty($_POST['sort'])) {
                $sortBy = $_POST['sort'];
            }
            if (isset($_POST['order']) && !empty($_POST['order'])) {
                $orderBy = $_POST['order'];
            }
            if (isset($_POST['to_date']) && !empty($_POST['to_date'])) {
                $to_date = $_POST['to_date'];
                $to_date = $end_date = date('Y-m-d',strtotime($to_date));
            }
            if (isset($_POST['from_date']) && !empty($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $from_date = $start_date = date('Y-m-d',strtotime($from_date));

                if($from_date > $to_date){
                    $this->errorMessage('from_date_must_be_less',$msg_data,$userLanguage);
                }
                $deliveryDateCondition = " AND ( ";

                while (strtotime($start_date) <= strtotime($end_date)) {
                    $delivery_date = date ("Y-m-d",strtotime($start_date));
                    $deliveryDateCondition .= ' delivery_date_timeslot Like '.'\'%[{"date":"'.$delivery_date.'"%\'';
                    $deliveryDateCondition .= ' OR ';
                    $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
                }
                $deliveryDateCondition = rtrim($deliveryDateCondition, ' OR ');
                $deliveryDateCondition .= ' ) ';
                $condition .= $deliveryDateCondition;
            }
            $order_data = $this->apis_models1->getDeliveryBoyRevenuedata($condition, $limit, $page, $sortBy, $orderBy);
            $requiredCounts = $order_data['requiredCounts'];

            if(is_array($requiredCounts) && isset($requiredCounts['0'])){
                $delivery_amount_in_egp = $requiredCounts['0']['charge_in_egp'];
                $delivery_amount_in_usd = $requiredCounts['0']['charge_in_usd'];
                $totalRecords = $requiredCounts['0']['total_orders'];
            }

            if (is_array($order_data)) {
                $order = $order_data['query_result'];
                foreach ($order as $key => $value) {
                    $sale_id = $value['sale_id'];
                    //$product_id = 0;
                    $orders_data[$key]['click_allowed'] = $click_allowed;
                    $orders_data[$key]['orders_id'] = $value['sale_code'];
                    $orders_data[$key]['sale_id'] = $value['sale_id'];
                    $orders_data[$key]['date_purchased'] = date("Y-m-d H:i:s", $value['sale_datetime']);
                    $orders_data[$key]['payment_type'] = $payment_type = $value['payment_type'];
                    $orders_data[$key]['delivery_type'] = $delivery_type = $value['delivery_type'];
                    $status = json_decode($value['delivery_status'], true);
                    $payment_status = json_decode($value['payment_status'], true);
                    $orders_data[$key]['payment_status'] = $payment_status[0]['status'];
                    $orders_data[$key]['delivery_status'] = $delivery_status = $status[0]['status'];

                    $delivery_date_timeslots = json_decode($value['delivery_date_timeslot'], true);
                    $orders_data[$key]['delivery_date'] = $delivery_date_timeslots[0]['date'];
                    $orders_data[$key]['delivery_timeslot'] = $delivery_date_timeslots[0]['timeslot'];
                    $orders_data[$key]['delivery_date_timeslot'] = $delivery_date_timeslots[0]['timeslot'];

                    $user_choice = json_decode($value['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];

                    $orders_data[$key]['order_price'] = get_converted_currency($value['grand_total'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['delivery_charge'] = get_converted_currency($value['delivery_charge'], $currency_code,$sale_currency_conversion_rate);
                }
                $responseData =  array(
                    'data' => $orders_data,
                    'total_record' => $totalRecords,
                    'amount' => get_converted_currency($delivery_amount_in_egp, $currency_code, $sale_currency_conversion_rate),
                );
                $this->successMessage('returned_all_orders',array(), $userLanguage,$responseData);
            } else {
                $this->errorMessage('empty_list_criteria', $msg_data, $userLanguage,$orders_data);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function verifyDeliveryCode(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
            $_POST = $data;
        }

        $return_array = $update_array = $other_array = array();
        $deliveryCode = $sale_id = $remarks = '';
        $userLanguage = "ar";
        $attempts_left = WRONG_VERIFICATION_COUNTS;
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }

        $token_array = $this->read_header_token($userLanguage);
        $msg_data = array();
        if($this->read_header()  && is_array($token_array)){
            $user_id = $token_array['uid'];

            if (isset($_POST['deliveryCode']) && !empty($_POST['deliveryCode'])) {
                $deliveryCode = $_POST['deliveryCode'];
            }else{
                $this->errorMessage('please_enter_delivery_verification_code',$msg_data,$userLanguage);
            }
            
            if (isset($_POST['remark']) && !empty($_POST['remark'])) {
                $remarks = $_POST['remark'];
            }

            if (isset($_POST['sale_id']) && !empty($_POST['sale_id'])) {
                $sale_id = $_POST['sale_id'];
            }else{
                $this->errorMessage('no_such_order_id_found',$msg_data,$userLanguage);
            }

            $conditionDeliveryCode = "1=1 and sale_id=".$this->db->escape($sale_id);
            $isDeliveryCodePresent = $this->apis_models1->getData('verification_code,payment_status,delivery_status,sale_code,verification_counts', 'sale', $conditionDeliveryCode);
            
            
            //WRONG_VERIFICATION_COUNTS - $value['verification_counts'];
            if(!is_array($isDeliveryCodePresent)){
                $this->errorMessage('please_login_and_try_again',$msg_data,$userLanguage);
            }
            
            $saleCode = $isDeliveryCodePresent[0]['sale_code'];
            $verification_code = $isDeliveryCodePresent[0]['verification_code'];
            $verification_counts = $isDeliveryCodePresent[0]['verification_counts'];
            $paymentStatus = json_decode($isDeliveryCodePresent[0]['payment_status'], true);
            $deliveryStatus = json_decode($isDeliveryCodePresent[0]['delivery_status'], true);
            
            if($verification_counts >= WRONG_VERIFICATION_COUNTS){
                $this->errorMessage('reached_maximum_retry_attempts',$msg_data,$userLanguage);
            }
            
            if($verification_code != $deliveryCode){
                $updated_verification_counts = $verification_counts+1;
                $attempts_left = WRONG_VERIFICATION_COUNTS - $updated_verification_counts;
                $return_array['attempts_left'] = $attempts_left;
                $updatesale_array['verification_counts'] = $updated_verification_counts;
                $update_condition = 'sale_id = ' . $this->db->escape( $sale_id );
                $this->apis_models1->updateRecord( 'sale', $updatesale_array, $update_condition );
                
                if($attempts_left <=0){
                    //block user from receiving new orders and also inActive his login
                    $updateuser_array['status'] = 'In-active';
                    $updateuser_array['access_token'] = '';
//                    $updateuser_array['assign_orders'] = 'no';
                    $updateuser_condition = 'admin_id = ' . $this->db->escape( $user_id );
                    $this->apis_models1->updateRecord( 'admin', $updateuser_array, $updateuser_condition );
                
                }
                
                $this->errorMessage('invalid_verification_code',$msg_data,$userLanguage,$return_array,$updated_verification_counts);
            }else{
                
                if(!empty($paymentStatus)){
                    $paymentStatus[0]['status'] = "paid";
                    $update_array['payment_status'] = json_encode($paymentStatus);
                }

                if(!empty($deliveryStatus)){
                    $deliveryStatus[0]['status'] = "delivered";
//                    $deliveryStatus[0]['delivery_time'] = time();
                    $deliveryStatus[0]['delivery_time'] = date('Y-m-d H:i:s');
                    $deliveryStatus[0]['comment'] = $remarks;
                    $update_array['delivery_status'] = json_encode($deliveryStatus,JSON_UNESCAPED_UNICODE);
                }
                
                $update_condition = 'sale_id = ' . $this->db->escape( $sale_id );
                $this->apis_models1->updateRecord( 'sale', $update_array, $update_condition );
                
                $filePath = DOC_ROOT_FRONT.'/uploads/pdf_files';
                $fileName = 'trolley_'.$saleCode.'.pdf';
                if (file_exists($filePath."/".$fileName)) {
                     unlink($filePath."/".$fileName);
                }
                
                $this->successMessage('delivered_successfully',$return_array,$userLanguage);
            }
            
        }else{
            $this->errorMessage('please_login_and_try_again',$msg_data,$userLanguage);
        }
    }

    public function getPDF(){

        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
            $_POST = $data;
        }
        $return_array = array();
        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }

        $token_array = $this->read_header_token($userLanguage);
        $msg_data = array();
        if($this->read_header()  && is_array($token_array)){
            $user_id = $token_array['uid'];

            if (isset($_POST['sale_id']) && !empty($_POST['sale_id'])) {
                $sale_id = $_POST['sale_id'];
            }else{
                $this->errorMessage('no_such_order_id_found',$msg_data,$userLanguage);
            }           

            $preString =  'maakecommerce';
            $postString =  'delivery app';
            $encoded_string =  rtrim(strtr(base64_encode($preString.'|'.$sale_id.'|'.$postString), '+/', '-_'), '=');
            $url = $this->generatedPDFInvoice($encoded_string);
            $url = $this->sanitize_output($url);
            //$return_array['invoice'] = $url[0];
            $return_array['invoice'] = $url;
            $this->successMessage('data_fetched_successfully',$return_array,$userLanguage);
        }else{
            $this->errorMessage('please_login_and_try_again',$msg_data,$userLanguage);
        }
    }

    public function paintInvoice($sale_id=''){

        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
            $_POST = $data;
        }
        $return_array = array();
        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }

   //     $token_array = $this->read_header_token($userLanguage);
        $msg_data = array();
 //       if($this->read_header()  && is_array($token_array)){
        if(TRUE){
            //$user_id = $token_array['uid'];

            if (isset($sale_id) && !empty($sale_id)) {
                $preString =  'maakecommerce';
                $postString =  'delivery app';
                $condition = ' sale_id ='.$this->db->escape($sale_id);
                $page_data['sale'] = $sale_array = $this->apis_models1->getData('*','sale',$condition);

                foreach($page_data['sale'] as $saleData) {
                    $product_details = json_decode($saleData['product_details'],true);
                    $key = array_keys($product_details);
                    $i=0;
                    $prods = array();
                    foreach($product_details as $prod) {
                        if(!isset($prod['status']) || $prod['status'] != 'cancelled') {
                            array_push($prods, $prod); 
                        }
                        $i++;
                    }
                }
                $page_data['sale'][0]['product_details'] = $prods;

                $payment_status_array = json_decode($sale_array[0]['payment_status'],true);
                $payment_status = ucfirst($payment_status_array[0]['status']);
                $sale_code = $sale_array[0]['sale_code'];
                $this->load->view('back/admin/delivery_boy_pdf', $page_data);
            }else{
                $this->errorMessage('no_such_order_id_found',$msg_data,$userLanguage);
            }           

        }else{
            $this->errorMessage('please_login_and_try_again',$msg_data,$userLanguage);
        }
    }
    

    private function sanitize_output($buffer) {
            $search = array(
                '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
                '/[^\S ]+\</s',     // strip whitespaces before tags, except space
                '/(\s)+/s',         // shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/' // Remove HTML comments
            );

            $replace = array(
                '>',
                '<',
                '\\1',
                ''
            );

            $buffer = preg_replace($search, $replace, $buffer);

            return $buffer;
        }
    
    
    
    public function triggerNotification(){
        $date = date('Y-m-d H:i:s');
            $fcm_data = $notification = array();
            $title = 'Test Reminder for week Delivery Assigned';
//            foreach($data as $key => $val){
                $sales_id = 2174;
                $body = 'Kindly check the order is assigned to you for '.$sales_id ;
                $fcm_id = $this->db->get_where('admin',array('admin_id'=>'26'))->row()->fcm_id;
                $fcm_trader_details = array(
                                'id'=>$sales_id,
                                'fcm_id'=>$fcm_id,
                                'title'=>$title,
                                'body'=> $body,
                                'for'=>'delivery'
                );
                
                
                $fcm_data[] =   $fcm_trader_details;                 
//            }
            $this->messaging_model->sendFcmNotification($fcm_data);
    }

    private function generatedPDFInvoice($para1=''){
        if (!empty($para1) && isset($para1)) {
            $varr = base64_decode(strtr($para1, '-_', '+/'));
            $varr_array = explode('|',$varr);
            $sale_id = $varr_array[1];
            $condition = ' sale_id ='.$this->db->escape($sale_id);
            $page_data['sale'] = $sale_array = $this->apis_models1->getData('*','sale',$condition);
            $payment_status_array = json_decode($sale_array[0]['payment_status'],true);
            $payment_status = ucfirst($payment_status_array[0]['status']);
            $sale_code = $sale_array[0]['sale_code'];
	    $view_pdf_html = $this->load->view('back/admin/delivery_boy_pdf', $page_data,true);
            return $view_pdf_html;   
            
            $filePath = DOC_ROOT_FRONT.'/uploads/pdf_files';
            $fileName = 'trolley_'.$sale_code.'.pdf';
            if (file_exists($filePath."/".$fileName)) {
                 return array(base_url().'uploads/pdf_files/'.$fileName, $fileName);
            }else{
                $view_pdf_html = $this->load->view('back/admin/delivery_boy_pdf', $page_data,true);
                $this->load->library('m_pdf');
                $mpdf = new mPDF('utf-8'); 
                $mpdf->SetMargins(20, 15, 45);
                $mpdf->SetTitle("Trolley Ecommerce - Invoice");
                $mpdf->SetAuthor("MAAK Technology");
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->SetWatermarkText($payment_status);
                $mpdf->showWatermarkText = true;
                $mpdf->watermark_font = 'DejaVuSansCondensed';
                $mpdf->watermarkTextAlpha = 0.1;
                $mpdf->WriteHTML($view_pdf_html);
                $mpdf->Output($filePath."/".$fileName, "F");
                return array(base_url().'uploads/pdf_files/'.$fileName, $fileName);
            }
        }else{
             return '';
        }
    }

    private function getRevenueCount($user_id, $forDays = 0, $status = 'delivered') {
        $orderStatusArray = array();
        $orderCounts = $delivery_amount_in_egp = $all_totalRecords = 0;
        $admin_id = '"admin_id":"'.$user_id.'"';
        $db_status = '"status":"'.$status.'"';
        $ts_condition = " 1=1 ";

        if($forDays >= 0) {
            $ts_condition = $this->getTimeslotsDates($forDays, $user_id);
        }
        $final_condition = $ts_condition." And assign_delivery_data like '%".$admin_id."%'";

        if(!empty($db_status)) {
            $final_condition .= " And delivery_status like '%".$db_status."%' ";
        }
        $db_orders = $this->apis_models1->getDeliveryBoyRevenueCounts($final_condition);
        $requiredCounts = !empty($db_orders) ? $db_orders['requiredCounts'] : array();

        if(is_array($requiredCounts) && isset($requiredCounts['0'])) {
            $delivery_amount_in_egp = $requiredCounts['0']['charge_in_egp'];
            $delivery_amount_in_usd = $requiredCounts['0']['charge_in_usd'];
            $all_totalRecords = $requiredCounts['0']['total_orders'];
        }

        return array(
            'count' => $all_totalRecords,
            'revenue' => $delivery_amount_in_egp
        );
    }

    private function getOrderCount($user_id,$forDays = 0,$status='delivered'){
        $orderStatusArray = array();
        $orderCounts = 0;
        $admin_id = '"admin_id":"'.$user_id.'"';
        $db_status = '"status":"'.$status.'"';
        $ts_condition = " 1=1 ";
        
        if($forDays >=0 )
            $ts_condition = $this->getTimeslotsDates($forDays, $user_id);
            
        
        
        $final_condition = $ts_condition." And i.assign_delivery_data like '%".$admin_id."%'";
        
        if(!empty($db_status))
           $final_condition .= "And delivery_status like '%".$db_status."%' ";
        
        $db_orders = $this->apis_models1->getData('count(sale_id) as count','sale',$final_condition);
        $orderCounts = $db_orders[0]['count'];
        return $orderCounts;
    }
    private function getTotalOrderCount($user_id,$order_status = 'processed',$delivery_status='delivered'){
        $orderCounts = 0;
        $db_status = '"'.$delivery_status.'"';
        $ord_status = $order_status;
        $ts_condition = " 1=1 ";
        
        $ts_condition .= "And admin_id =" .$user_id. " And delivery_status like '%".$db_status."%' ";
        $ts_condition .= "And order_status = '".$ord_status."'";
       
        $db_orders = $this->apis_models1->getData('count(sale_id) as count','sale',$ts_condition);
        
        $orderCounts = $db_orders[0]['count'];
        return $orderCounts;
    }
    
    
    
    private function getTimeslotsCount($user_id,$forDays = 1){
            $ts_array_data  = array();
            $ts_condition = $this->getTimeslotsDates($forDays, $user_id);
            $pending_status = '"status":"pending"';
            $process_status = '"status":"process"';

            $condition = $ts_condition." And assign_delivery_data like '%".$user_id."%' And (delivery_status like '%".$pending_status."%' OR delivery_status like '%".$process_status."%')"; 
            $distinct_timeslots = $this->apis_models1->getDeliveryBoyDistinctTimeslots($condition);
            
            if(is_array($distinct_timeslots) && isset($distinct_timeslots)){
                foreach($distinct_timeslots as $tskey=>$timeslotval){
                    $delivery_date_timeslot = $timeslotval['delivery_date_timeslot'];
                    $assigned_count = $timeslotval['assigned_count'];

                    $delivery_date_timeslot_json = json_decode($delivery_date_timeslot,TRUE);
                    
                    $ts_array_data[$tskey]['delivery_count']=$assigned_count;
                    $ts_array_data[$tskey]['delivery_date']=$delivery_date_timeslot_json['0']['date'];
                    $ts_array_data[$tskey]['delivery_timeslot']=$delivery_date_timeslot_json['0']['timeslot'];
                    $ts_array_data[$tskey]['date_timeslot']=$delivery_date_timeslot_json['0']['date'].'|'.$delivery_date_timeslot_json['0']['timeslot'];
                }
            }
            return $ts_array_data;
    }
    
    
     private function getTimeslotsDates($forDays = 0,$user_id=''){
            $ts_array_data  = array();
            $puser_id = '"admin_id":"'.$user_id.'"';
            $current_date = '"date":"'.date('Y-m-d').'"';
            $ts_condition =  " 1=1 ";
            if($forDays >= 0){
                $date_condition = " delivery_date_timeslot like '%".$current_date."%' OR ";
                for($i=1;$i<=$forDays;$i++){
                   $current_date_minus_1 = date('Y-m-d',strtotime("-$i days"));
                   $date_condition .= " delivery_date_timeslot like '%".$current_date_minus_1."%' OR ";
                }

                $date_condition = trim($date_condition, " OR ");
                if(isset($user_id) && !empty($user_id)){
                    $ts_condition = "1=1 And assign_delivery_data like '%".$puser_id."%' And ( $date_condition ) "; 
                }else{
                    $ts_condition = "1=1 And ( $date_condition ) "; 
                }
            }
            return $ts_condition;
    }
}
