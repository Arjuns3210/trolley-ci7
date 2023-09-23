<?php

class Apis_1 extends CI_Controller {

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
        
        $this->device_id = "";
    }

    public function getSetting() {
        $setting = $this->apis_models1->getData('*', 'app_setting');
        return $setting;
    }

    public function getAlertSetting() {
        $setting = $this->apis_models1->getData('*', 'alert_settings');
        return $setting;
    }

    public function siteSetting() {
        $setting = $this->getSetting();
       
        $responseData = array('success' => '1', 'data' => $setting, 'message' => "Returned all site data.");
        $categoryResponse = json_encode($responseData);
        print $categoryResponse;
    }

    private function read_header() {
        $header_data = $_SERVER;
        $get_header_data = getallheaders();
        $this->device_id = !empty($get_header_data['Device-Id']) ? $get_header_data['Device-Id'] : "";

        if (is_array($header_data) && isset($header_data['PHP_AUTH_USER']) && isset($header_data['PHP_AUTH_PW']) && $header_data['PHP_AUTH_USER'] == PHP_AUTH_USER && $header_data['PHP_AUTH_PW'] == PHP_AUTH_PW) {
            return true;
        } else {
            return false;
        }
    }

    private function read_header_old() {
        $header_data = $_SERVER;
        $remote_address =  $this->db->get_where('general_settings',array('type'=>'server_remote_address'))->row()->value;
        $serverAddress = $_SERVER['SERVER_NAME']; 
        $remote_address = base64_decode($remote_address);
        if (is_array($header_data) && isset($header_data['PHP_AUTH_USER']) && isset($header_data['PHP_AUTH_PW']) && $header_data['PHP_AUTH_USER'] == PHP_AUTH_USER && $header_data['PHP_AUTH_PW'] == PHP_AUTH_PW) {
            if($serverAddress == $remote_address){
                return true;
            }else{
                return false;
            }
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
        //added by sagar : FOR Product specific error msg
        if( $replaceText !== ""){
            $msg =  str_replace( '$$productName$$', $replaceText, $msg );
            $msg =  str_replace( '$$number$$', $replaceText, $msg );
        }
        $return_array['message'] = $msg;

        if (empty($msg)) {
            $return_array['message'] = $msg_name;
        }

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
                $check_condition = ' user_id = ' . $this->db->escape($user_id).'and deleted_at is null';
                // Added by Arjun 03-07-2023 : Start 
                $merchantExist = $this->apis_models1->getData('user_id,status,last_pwd_update_time,access_token', 'user', $check_condition);

                if ($merchantExist[0]['access_token']== null) {
                    $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage,'','',4);
                    exit();
                }
                // Added by Arjun 03-07-2023 : End
                if (!is_array($merchantExist)) {
                    $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
                    exit();
                }
                if (is_array($merchantExist) && $merchantExist[0]['status'] != 'Active') {
                    //literal not yet set
                    $this->errorMessage('please_contact_with_admin_team', $msg_data, $userLanguage,'','',4); //4 is for  token expire
                    exit();
                }
                if (is_array($merchantExist) && $merchantExist[0]['last_pwd_update_time'] != '') {
                    $last_pwd_update_time = $merchantExist[0]['last_pwd_update_time'];
                    $tokenExpireTime =  43200 ;  //12 hours
                    $userPasswordTime =  $last_pwd_update_time + $tokenExpireTime;
                    if($userPasswordTime > $token['exp']) {
                        //logout those users
                        $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage ,'','',4);
                        exit();
                    }
                }
                $db_appflow =  $this->db->get_where('general_settings',array('type'=>'app_flow'))->row()->value;
                $appflow = APP_FLOW;
                $appflow_ipwd = APP_FLOW_IPWD;
                if($appflow != $appflow_ipwd){
                    $this->errorMessage('incorrect_username_or_password', $msg_data, $userLanguage);
                    exit();
                }
                if($db_appflow != $appflow){
                   $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage,'','',4);
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

    function checkVersion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
    
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
    
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
    
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
    
        $condition = " 1=1 ";
        $msg_data = array();
        $byPassHeader = true;
    
        if ($this->read_header() || $byPassHeader) {
            if (isset($_POST['platform']) && !empty($_POST['platform'])) {
                $platform = $_POST['platform'];
            } else {
                echo json_encode(array('success' => "0"));
                exit();
            }
    
            if (isset($_POST['version']) && !empty($_POST['version'])) {
                $version = $_POST['version'];
            } else {
                echo json_encode(array('success' => "0"));
                exit();
            }
            $msg = 'Update your application';
            
            if ($platform == 'android') {
                $unique_condition = "type = 'actual_android_version' OR type = 'android_url'";
                $dbData = $this->apis_models1->getData('type,value', 'general_settings', $unique_condition);
                $url = $dbData[0]['value'];
                $dbversion = json_decode($dbData[1]['value'], true);
            } else {
                $unique_condition = "type = 'actual_ios_version' OR type = 'ios_url'";
                $dbData = $this->apis_models1->getData('type,value', 'general_settings', $unique_condition);
                $url = $dbData[0]['value'];
                $dbversion = json_decode($dbData[1]['value'], true);
            }
    
            if (in_array($version, $dbversion)) {
                echo json_encode(array('success' => "1"));
                exit();
            } else {
                echo json_encode(array('success' => "0", 'message' => $msg, 'url' => $url));
                exit();
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }    

    private function saveUserRegisterDetails() {
        $phone_number = $password = $email =  "";
        $first_name = $second_name = $third_name = $fourth_name = "";
        $sex = $state = $locality = $job_type = $social_status = "";
        $dob = "1970-01-01";
//        
        $langlat = "";
    
        $nmval = "/^[a-zA-Z]+$/i";
        $userLanguage = 'en';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
        }

        $header = $this->read_header();
        if ($header) {
            
            if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {
                if (( preg_match($this->mobile_check, $_POST['phone_number']))) {
//                    $numberCode = substr($_POST['phone_number'],0,3);
//                    if($numberCode != '249'){
//                        $this->errorMessage('phone_no_should_start_with_code_249', $msg_data, $userLanguage);
//                        exit();
//                    }
                    $con = ' phone = ' . $this->db->escape($_POST['phone_number'] .'and deleted_at is  null ');
                    $phoneExist = $this->apis_models1->getData('phone', 'user', $con);

                    if (is_array($phoneExist)) {
                        $this->errorMessage('phone_no_already_exist', $msg_data, $userLanguage);
                        exit();
                    } else {
                        $phone_number = $_POST['phone_number'];
                    }
                } else {
                    $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                    exit();
                }
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                if(strlen($_POST['password']) < 4 ){
                    $this->errorMessage('password_length_error', $msg_data, $userLanguage);
                }
                $password = md5($_POST['password']);
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

            if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
                    $first_name = $_POST['first_name'];
               /* if (( preg_match($nmval, $_POST['first_name']))) {
                    $first_name = $_POST['first_name'];
                } else {
                    $this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    exit();
                }*/
            } else {
                $this->errorMessage('please_enter_first_name', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['second_name']) && !empty($_POST['second_name'])) {
                    $second_name = $_POST['second_name'];
               /* if (( preg_match($nmval, $_POST['second_name']))) {
                    $second_name = $_POST['second_name'];
                } else {
                    $this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    exit();
                } */
            } else {
//                $this->errorMessage('please_enter_second_name', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['third_name']) && !empty($_POST['third_name'])) {
                    $third_name = $_POST['third_name'];
              /*  if (( preg_match($nmval, $_POST['third_name']))) {
                    $third_name = $_POST['third_name'];
                } else {
                    $this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    exit();
                } */
            } else {
//                $this->errorMessage('please_enter_third_name', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['fourth_name']) && !empty($_POST['fourth_name'])) {
                    $fourth_name = $_POST['fourth_name'];
               /* if (( preg_match($nmval, $_POST['fourth_name']))) {
                    $fourth_name = $_POST['fourth_name'];
                } else {
                    $this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    exit();
                } */
            } else {
               $this->errorMessage('please_enter_fourth_name', $msg_data, $userLanguage);
                exit();
            }
            

            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $email = $_POST['email'];
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $con = ' email = ' . $this->db->escape($email) .'and deleted_at is null';
                    $emailexist = $this->apis_models1->getData('email', 'user', $con);
                    if (is_array($emailexist)) {
                        $this->errorMessage('email_id_already_exist', $msg_data, $userLanguage);
                        exit();
                    }
                } else {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                    exit();
                }
            } else {
//                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
//                exit();
            }

            if (isset($_POST['sex']) && !empty($_POST['sex'])) {
                if($_POST['sex'] == 'M' || $_POST['sex'] == 'F'){
                $sex = $_POST['sex'];
                }else{
                    $this->errorMessage('invalid_gender_type', $msg_data, $userLanguage);
                    exit();
            }
            }else{
//                $this->errorMessage('please_select_gender_type', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['state']) && !empty($_POST['state']) ) {
                $state = $_POST['state'];
            }else{
//                $this->errorMessage('please_enter_state', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['dob'])) {
                $dob = date('Y-m-d',strtotime($_POST['dob']));
            }
          
            if (isset($_POST['locality']) && !empty($_POST['locality'])) {
                $locality = $_POST['locality'];
            }
            
            if (isset($_POST['job_type']) && !empty($_POST['job_type'])) {
                $job_type = $_POST['job_type'];
            }else{
//                $this->errorMessage('please_enter_job_type', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['social_status']) && !empty($_POST['social_status'])) {
                $social_status = $_POST['social_status'];
            }else{
//                $this->errorMessage('please_enter_social_status', $msg_data, $userLanguage);
//                exit();
            }
        /*  -- currently NOT In use
            if (isset($_POST['longitude']) && !empty($_POST['longitude']) && 
                isset($_POST['latitude']) && !empty($_POST['latitude'])) {
                $langlat = $_POST['longitude'].','.$_POST['latitude'];
            } else {
//                $this->errorMessage('please_select_location', $msg_data, $userLanguage);
//                exit();
            }
        */

            //city and area new fields 
            $city_id = $area_id = 0;
            if(isset($_POST['city_id']) && !empty($_POST['city_id'])){
                $city_id= $_POST['city_id'];
                $cityCondition = ' city_id = '.$this->db->escape($city_id);
                $cityExist = $this->apis_models1->getData( 'city_id', 'city', $cityCondition );
                if (!is_array( $cityExist ) ) {
                   $this->errorMessage('no_such_city_found', $msg_data, $userLanguage);
                }
            }else{
                //$this->errorMessage('please_select_city', $msg_data, $userLanguage);
            }

            if(isset($_POST['area_id']) && !empty($_POST['area_id'])){
                $area_id= $_POST['area_id'];
                $areaCondition = ' area_id = '.$this->db->escape($area_id);
                $areaCondition .= ' AND city_id  =  '.$this->db->escape($city_id);
                $areaExist = $this->apis_models1->getData( 'area_id', 'area', $areaCondition );
                if (!is_array( $areaExist ) ) {
                   $this->errorMessage('no_such_area_found', $msg_data, $userLanguage);
                }
            }else{
               // $this->errorMessage('please_select_area', $msg_data, $userLanguage);
            }
            
            //$auto_generated_wallet_no =  $this->apis_models1->geneateWalletNumber();
            $data_array = array(
                'email' => $email,
                'password' => $password,
                'phone' => $phone_number,
                'first_name' => $first_name,
                'second_name' => $second_name,
                'third_name' => $third_name,
                'fourth_name' => $fourth_name,
                'sex' => $sex,
                'locality' => $locality,
                'state' => $state,
                'job_type' => $job_type,
                'social_status' => $social_status,
                'dob' => $dob,
                'langlat' => $langlat,
                'wishlist' => '[]',
                'creation_date' => time(),
                'is_verified' => 'N',
                'city_id'=>$city_id,
                'area_id'=>$area_id,
                //'wallet_no' => $auto_generated_wallet_no,
            );
          
            //$result = $this->apis_models1->insertData('user', $data_array, 1);
         
            //NEW CODE ---- To handle duplicate entry on sever 
            $columns = array_keys($data_array);
            $columns = implode(',', $columns);
            $values = array_values($data_array);
            $valuess = "'";
            $valuess .= implode("','", $values);
            $valuess .= "'";

            $result = $this->apis_models1->insert_ignore('user', $columns, $valuess ,1);
            //NEW CODE ---- To handle duplicate entry on sever 
         
            if (is_numeric($result) && $result > 0) {
                
                $fix_wallet_series= UNIQUE_WALLET_NUMBER_SERIES;
                $update_array['wallet_no'] = $fix_wallet_series+$result;
                $usercondition = 'user_id = ' . $this->db->escape($result);
                $this->apis_models1->updateRecord('user', $update_array, $usercondition);

                //added by REitesh to send SMS after registration : start
                    $this->messaging_model->user_registeration_successful($phone_number);
                //added by REitesh to send SMS after registration : end
                /* -- NO need to add address for user 
                if(!empty($city_id) && !empty($area_id) && !empty($langlat)){ 
                $data = array(
                    'title' => 'home',
                    'user_id' => $result,
                    'langlat' => $langlat,
                    'number' => $phone_number,
                    'city_id'=>$city_id,
                    'area_id'=>$area_id,
                    'created_on' => date('Y-m-d H:i:s'),
                );
                $address_id = $this->apis_models1->insertData('user_address', $data, 1);
                }
                */
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }
    
     public function processLogin() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $username = $password = null;
        $fcm_id = "";
        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $header = $this->read_header();
        if ($header) {
            if (empty($this->device_id)){
                $this->errorMessage('please_enter_device_id', [], $userLanguage);
                exit();
            }
            if (isset($_POST['username']) && !empty($_POST['username'])) {
               $username =  $_POST['username'];
            } else {
                $this->errorMessage('please_enter_username', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $password = md5($_POST['password']);
            } else {
                $this->errorMessage('please_enter_password', $msg_data, $userLanguage);
                exit();
            }

            if (isset($_POST['fcm_id']) && !empty($_POST['fcm_id'])) {
                $fcm_id = ( $_POST['fcm_id'] );
            }

            $login_user = $this->apis_models1->validate($username, $password);
            if (is_array($login_user)) {
                if($login_user[0]['deleted_at'] != null){

                    $this->errorMessage('user_not_found', $msg_data, $userLanguage);
                    exit();
                }
                if($login_user[0]['status'] != 'Active'){
                    $this->errorMessage('please_contact_with_admin_team', $msg_data, $userLanguage);
                    exit();
                }
                $user_id = $login_user[0]['user_id'];
                $last_logon = $login_user[0]['last_login'];
                $customers_info_date_of_last_logon = time();
                $customer_info_update = array(
                    'last_login' => $customers_info_date_of_last_logon,
                    'fcm_id' => $fcm_id,
                );
                $condition1 = 'user_id = ' . $this->db->escape($user_id);
                $this->apis_models1->updateRecord('user', $customer_info_update, $condition1);

                $users = array();
                $arrayKey = 0;
                foreach ($login_user as $key => $val) {
                    $arrayKey = $key;
                    $phone = $val['phone'];
                    if(strlen($phone) == 9){
                        $phone = '0'.$phone;
                    }
                    $users[$key]['customers_id'] = $user_id;
                    $users[$key]['customers_email'] = $val['email'];
                    $users[$key]['customers_phone'] = $phone;
                    $users[$key]['first_name'] = $val['first_name'];
                    $users[$key]['second_name'] = $val['second_name'];
                    $users[$key]['third_name'] = $val['third_name'];
                    $users[$key]['fourth_name'] = $val['fourth_name'];
                    $users[$key]['sex'] = $val['sex'];
                    $users[$key]['state'] = $val['state'];
                    $users[$key]['locality'] = $val['locality'];
                    $users[$key]['job_type'] = $val['job_type'];
                    $users[$key]['social_status'] = $val['social_status'];
                    $users[$key]['langlat'] = $val['langlat'];
                    $users[$key]['user_type'] = 'b2c';
                    $users[$key]['last_login'] = date('Y-m-d H:i:s',$val['last_login']);
                    $users[$key]['city_id'] = $val['city_id'];
                    $users[$key]['area_id'] = $val['area_id'];
                    $users[$key]['wallet_no'] = $val['wallet_no'];
                    $users[$key]['wallet_balance'] = round($val['wallet_balance'],2);
                    $users[$key]['wallet_type'] = $val['wallet_type'];
                }
                $con = 'user_id = ' . $this->db->escape($user_id);
                $userAddress = $this->apis_models1->getData('langlat,address_id', 'user_address', $con);
                if (is_array($userAddress) && !empty($userAddress[0])) {
                    $users[$arrayKey]['address_id'] = $userAddress[0]['address_id'];
                    $users[$arrayKey]['langlat'] = $userAddress[0]['langlat'];
                }
                
                //adding credit limit amount and loyalty point
                $users[$arrayKey]['credit_limit'] = 0;
                $loyalty_point = $this->crud_model->getCurrentLoyaltyPoint($user_id);
                if (is_array($loyalty_point)) {
                    $users[$arrayKey]['loyalty_point'] = $loyalty_point[0]['add_amount'] - $loyalty_point[0]['destroy_amount'];
                } else {
                    $users[$arrayKey]['loyalty_point'] = 0;
                }
                //PASSING JWT TOKEN ON LOGIN USER
                $token = $this->jwttoken->createToken($user_id);
                $users[$arrayKey]['token'] = $update_array['access_token'] = $token;
                $token_condition = 'user_id = ' . $this->db->escape($user_id);
                $this->apis_models1->updateRecord('user', $update_array, $token_condition);
                //update cart user_id 
                if (!empty($this->device_id)){
                    $cart_condition = 'cart_session_id = ' . $this->db->escape(md5($this->device_id));
                    $cart_condition .= ' AND user_id = 0';
                    $this->apis_models1->updateRecord('cart', ['user_id'=>$user_id], $cart_condition);   
                }
                $this->successMessage('logged_in_successfully', $users, $userLanguage);
            } else {
                $this->errorMessage('incorrect_username_or_password', $msg_data, $userLanguage);
                exit();
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }
    
    function updateCustomerInfo() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $msg_data = array();
        $phone_number = $password = $email =  "";
        $first_name = $second_name = $third_name = $fourth_name = "";
        $sex = $state = $locality = $job_type = $social_status = "";
        $dob = "1970-01-01";
        $langlat = "";
        
        $nmval = "/^[a-zA-Z ]+$/i";
        $check_condition = " 1=1 ";
        $return_array = array();
        //user choice : START
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        //user choice : END
        $token_array = $this->read_header_token($userLanguage);
        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = ' . $this->db->escape($user_id);

            if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
                //if (( preg_match($nmval, $_POST['first_name']))) {
                    $first_name = $_POST['first_name'];
                    $return_array['first_name'] = $first_name;
                //} else {
                    //$this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    //exit();
                //}
                } else {
                $this->errorMessage('please_enter_first_name', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['second_name']) && !empty($_POST['second_name'])) {
                //if (( preg_match($nmval, $_POST['second_name']))) {
                    $second_name = $_POST['second_name'];
                    $return_array['second_name'] = $second_name;
                //} else {
                    //$this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    //exit();
                //}
                } else {
                    $return_array['second_name'] = $second_name;
//                $this->errorMessage('please_enter_second_name', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['third_name']) && !empty($_POST['third_name'])) {
                //if (( preg_match($nmval, $_POST['third_name']))) {
                    $third_name = $_POST['third_name'];
                    $return_array['third_name'] = $third_name;
                //} else {
                    //$this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    //exit();
                //}
                } else {
                    $return_array['third_name'] = $third_name;
//                $this->errorMessage('please_enter_third_name', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['fourth_name']) && !empty($_POST['fourth_name'])) {
                //if (( preg_match($nmval, $_POST['fourth_name']))) {
                    $fourth_name = $_POST['fourth_name'];
                    $return_array['fourth_name'] = $fourth_name;
                //} else {
                   //$this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    //exit();
                //}
                } else {
                    $return_array['fourth_name'] = $fourth_name;
                //$this->errorMessage('please_enter_fourth_name', $msg_data, $userLanguage);
                    //exit();
                }

            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $email = $_POST['email'];
                $return_array['email'] = $email;
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $con = ' email = ' . $this->db->escape($email);
                    $con .= ' AND user_id != ' . $this->db->escape($user_id);
                    $emailexist = $this->apis_models1->getData('email', 'user', $con);
                    if (is_array($emailexist)) {
                        $this->errorMessage('email_id_already_exist', $msg_data, $userLanguage);
                        exit();
                    }
                } else {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                    exit();
                }
            }else{
//                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
//                exit();
            } 

            if (isset($_POST['sex']) && !empty($_POST['sex']) ) {
                if($_POST['sex'] == 'M' || $_POST['sex'] == 'F'){
                $sex = $_POST['sex'];
                $return_array['sex'] = $sex;
                }else{
                    $this->errorMessage('invalid_gender_type', $msg_data, $userLanguage);
                    exit();
            }
            }else{
                $this->errorMessage('please_select_gender_type', $msg_data, $userLanguage);
                exit();
            }
            
//            NOT IN USE
//            if (isset($_POST['state']) && !empty($_POST['state']) ) {
//                $state = $_POST['state'];
//                $return_array['state'] = $state;
//            }else{
//                $this->errorMessage('please_enter_state', $msg_data, $userLanguage);
//                exit();
//            }
            
            if (isset($_POST['dob'])) {
                $dob = date('Y-m-d',strtotime($_POST['dob']));
                $return_array['dob'] = $dob;
            }
          
            if (isset($_POST['locality']) && !empty($_POST['locality']) ) {
                $locality = $_POST['locality'];
                $return_array['locality'] = $locality;
            }
            
            if (isset($_POST['job_type']) && !empty($_POST['job_type']) ) {
                $job_type = $_POST['job_type'];
                $return_array['job_type'] = $job_type;
            }else{
                $this->errorMessage('please_enter_job_type', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['social_status']) && !empty($_POST['social_status']) ) {
                $social_status = $_POST['social_status'];
                $return_array['social_status'] = $social_status;
            }else{
                $this->errorMessage('please_enter_social_status', $msg_data, $userLanguage);
                exit();
            }
            /* -- currently NOT in Use
            if (isset($_POST['longitude']) && !empty($_POST['longitude']) && 
                isset($_POST['latitude']) && !empty($_POST['latitude'])) {
                $langlat = $_POST['longitude'].','.$_POST['latitude'];
                $return_array['langlat'] = $langlat;
            } else {
                $this->errorMessage('please_select_location', $msg_data, $userLanguage);
                exit();
            }
            */
            
            //city and area new fields 
            $city_id = $area_id = 0;
            if(isset($_POST['city_id']) && !empty($_POST['city_id'])){
                $city_id= $_POST['city_id'];
                $cityCondition = ' city_id = '.$this->db->escape($city_id);
                $cityExist = $this->apis_models1->getData( 'city_id', 'city', $cityCondition );
                if (!is_array( $cityExist ) ) {
                   $this->errorMessage('no_such_city_found', $msg_data, $userLanguage);
                }
                $return_array['city_id'] = $city_id;
            }else{
                //$this->errorMessage('please_select_city', $msg_data, $userLanguage);
            }
            
            if(isset($_POST['area_id']) && !empty($_POST['area_id'])){
                $area_id= $_POST['area_id'];
                $areaCondition = ' area_id = '.$this->db->escape($area_id);
                $areaCondition .= ' AND city_id  =  '.$this->db->escape($city_id);
                $areaExist = $this->apis_models1->getData( 'area_id', 'area', $areaCondition );
                if (!is_array( $areaExist ) ) {
                   $this->errorMessage('no_such_area_found', $msg_data, $userLanguage);
                }
                $return_array['area_id'] = $area_id;
            }else{
               // $this->errorMessage('please_select_area', $msg_data, $userLanguage);
            }
            
            
            $data_array = array(
                'email' => $email,
                'first_name' => $first_name,
                'second_name' => $second_name,
                'third_name' => $third_name,
                'fourth_name' => $fourth_name,
                'sex' => $sex,
                'locality' => $locality,
                'state' => $state,
                'job_type' => $job_type,
                'social_status' => $social_status,
                'dob' => $dob,
                'langlat' => $langlat,
                'city_id' => $city_id,
                'area_id' => $area_id,
            );
        
            $this->apis_models1->updateRecord('user', $data_array, $check_condition);
//            COMMENTED BOCZ NOW MULTIPLE ADDRESS ARE STORED FOR USER
//            $data = array(
//                'langlat' => $langlat,
//            );
//            $this->apis_models1->updateRecord('user_address', $data, $check_condition);
            
            $responseData = array(
                'data' => $return_array,
            );
            $this->successMessage('user_info_update_successfully',$msg_data, $userLanguage,$responseData);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    function updateCustomerAddress() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $msg_data = array();
        $phone_number = $password = $email =  "";
        $first_name = $second_name = $third_name = $fourth_name = "";
        $sex = $state = $locality = $job_type = $social_status = "";
        $dob = "0000-00-00";
        $langlat = "";
        
        $nmval = "/^[a-zA-Z ]+$/i";
        $check_condition = " 1=1 ";
        $return_array = array();
        //user choice : START
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        //user choice : END
        $token_array = $this->read_header_token($userLanguage);
   
        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = ' . $this->db->escape($user_id);
            
            if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
                //if (( preg_match($nmval, $_POST['first_name']))) {
                    $first_name = $_POST['first_name'];
                    $return_array['first_name'] = $first_name;
                //} else {
                   // $this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    //exit();
               // }
                } else {
                $this->errorMessage('please_enter_first_name', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['second_name']) && !empty($_POST['second_name'])) {
                //if (( preg_match($nmval, $_POST['second_name']))) {
                    $second_name = $_POST['second_name'];
                    $return_array['second_name'] = $second_name;
                //} else {
                    //$this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    //exit();
                //}
                } else {
                $return_array['second_name'] = $second_name;
//                $this->errorMessage('please_enter_second_name', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['third_name']) && !empty($_POST['third_name'])) {
                //if (( preg_match($nmval, $_POST['third_name']))) {
                    $third_name = $_POST['third_name'];
                    $return_array['third_name'] = $third_name;
                //} else {
                   // $this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                   // exit();
                //}
                } else {
                    $return_array['third_name'] = $third_name;
//                $this->errorMessage('please_enter_third_name', $msg_data, $userLanguage);
//                exit();
            }
            
            if (isset($_POST['fourth_name']) && !empty($_POST['fourth_name'])) {
                //if (( preg_match($nmval, $_POST['fourth_name']))) {
                    $fourth_name = $_POST['fourth_name'];
                    $return_array['fourth_name'] = $fourth_name;
               // } else {
                   //$this->errorMessage('please_enter_valid_name', $msg_data, $userLanguage);
                    //exit();
                //}
                } else {
                $return_array['fourth_name'] = $fourth_name;
                //$this->errorMessage('please_enter_fourth_name', $msg_data, $userLanguage);
                //exit();
            }

            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $email = $_POST['email'];
                $return_array['email'] = $email;
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $con = ' email = ' . $this->db->escape($email);
                    $con .= ' AND user_id != ' . $this->db->escape($user_id);
                    $emailexist = $this->apis_models1->getData('email', 'user', $con);
                    if (is_array($emailexist)) {
                        $this->errorMessage('email_id_already_exist', $msg_data, $userLanguage);
                        exit();
                    }
                } else {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                    exit();
                }
            }else{
//                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
//                exit();
            } 
            
            if (isset($_POST['state']) && !empty($_POST['state']) ) {
                $state = $_POST['state'];
                $return_array['state'] = $state;
            }else{
                //$this->errorMessage('please_enter_state', $msg_data, $userLanguage);
                //exit();
            }
     
            if (isset($_POST['longitude']) && !empty($_POST['longitude']) && 
                isset($_POST['latitude']) && !empty($_POST['latitude'])) {
                $langlat = $_POST['longitude'].','.$_POST['latitude'];
                $return_array['langlat'] = $langlat;
            } else {
                $this->errorMessage('please_select_location', $msg_data, $userLanguage);
                exit();
            }
            $data_array = array(
                //'state' => $state,
                'langlat' => $langlat,
            );
        
            $this->apis_models1->updateRecord('user', $data_array, $check_condition);
            $data = array(
                'langlat' => $langlat,
            );
            $this->apis_models1->updateRecord('user_address', $data, $check_condition);
            
            $responseData = array(
                'data' => $return_array,
            );
            $this->successMessage('user_info_update_successfully',$msg_data, $userLanguage,$responseData);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }


    function getCollection() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $user_id = "";
        //User choice Currency_code and userLanguage  : START
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = 'ar';
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
            
        }
         //User choice Currency_code and userLanguage  : END
        if ($this->read_header()) {
            $data = $variation_attribute_values = $attr_mapp_data = array();

            //categories array
            $categories = $this->getAllCategories($userLanguage);
            //Banner array 
            $con1 = 'status = "ok" ';
            $con1 .= ' And slides_lang = ' . $this->db->escape($userLanguage);
            $banners = $this->apis_models1->getData('slides_id as id,button_link as slide_image', 'slides', $con1);

            if (is_array($banners)) {
                foreach ($banners as $key => $val) {
                    if (isset($val['slide_image']) && !empty($val['slide_image'])) {
                        if (file_exists('uploads/slides_image/'.$val['slide_image'])) {
                            $banners[$key]['image'] = base_url() . 'uploads/slides_image/'.$val['slide_image'].'?d='.refreshedImage();
                        } else {
                            $banners[$key]['image'] = base_url() . 'uploads/slides_image/default.jpg?d='.refreshedImage();
                        }
                    } else {
                        $banners[$key]['image'] = base_url() . 'uploads/slides_image/default.jpg?d='.refreshedImage();
                    }
                }
            }           
            if (!is_array($banners)) {
                $banners = array();
            }
            //hardcoded value for feature list on homepage
            $featureData = $this->apis_models1->appFeaturedData($userLanguage);
           
            $features_list = array(
                array(
                    'title' => $featureData[0]['value'],
                    'image' => base_url() . 'uploads/instant-delivery.png',
                    'description' => $featureData[1]['value']
                ),
                array(
                    'title' => $featureData[2]['value'],
                    'image' => base_url() . 'uploads/store-pickup.png',
                    'description' => $featureData[3]['value']
                ),
            );
            //about_us and terms_cond
            $pageData = $this->apis_models1->appPageData();

            $otherPageData = array();
            if (is_array($pageData) && !empty($pageData)) {
                foreach ($pageData as $key => $value) {
                    $otherPageData[$key]['type'] = $value['type'];
                    $otherPageData[$key]['language'] = $value['language'];
                    if ($value['type'] == 'about_us_en' || $value['type'] == 'about_us_ar') {
                        $otherPageData[$key]['name'] = 'About Us';
                    }

                    if ($value['type'] == 'terms_conditions_en' || $value['type'] == 'terms_conditions_ar') {
                        $otherPageData[$key]['name'] = 'Terms And Condition';
                    }
                    $otherPageData[$key]['description'] = $value['value'];
                }
            }
            //contact page details  also passing free_delivery_amount and product_fix_tax
            $contactUsData = $this->apis_models1->appContactusData();
            //collection product array
            $all_collection = $this->db->order_by("order_by_collection ASC")->get('collection')->result_array();
            //$collection_product_ids = array();
            $products_In_collections = "";
            if(is_array($all_collection)){
                foreach ($all_collection as $key => $val) {
                    $product_ids_array = json_decode($val['product_for_collection']);
                    if(!empty($product_ids_array)){
                        $first_five_product_ids = array_slice($product_ids_array, 0, 5, true);
                        foreach ($first_five_product_ids as $pVal) {
                         //array_push($collection_product_ids,$pVal);
                         $products_In_collections .= $pVal . ',';
                        }
                    }
                }
                $products_In_collections = rtrim($products_In_collections, ',');
            }

            $data = $this->apis_models1->getProductsData($products_In_collections);
          
            //added by sagar : start 2-08
            $product_id_In = ' product_id IN (';
            $product_id_vIn = ' v.product_id IN (';
            $attr_mapp_data = $variation_attribute_values = array();
            if (is_array($data[0]) && $data[1] > 0) {
                foreach ($data[0] as $key => $value) {
                    $product_id_In .= $value['product_id'] . ',';
                    $product_id_vIn .= $value['product_id'] . ',';
                }
                $product_id_In = rtrim($product_id_In, ',');
                $product_id_vIn = rtrim($product_id_vIn, ',');
                $product_id_In .= ' )';
                $product_id_vIn .= ' )';
                $attr_mapp_data = $this->apis_models1->get_attmapp_data(0, $product_id_In);
                $variation_attribute_values = $this->apis_models1->getVariationAttributeMapping(0, 0, $product_id_vIn);
            }

            //added by sagar : end 2-08

            $final_array = array();
//            if (is_array($data[0]) && $data[1] > 0) {
            foreach ($data[0] as $key => $value) {
                $variation_product_weight = $value['weight'];
                $offer_validity = $value['offer_validity'];
                $attribute_value_details = array();
                $final_array[$key]['products_id'] = $value['product_id'];
                $final_array[$key]['product_code'] = $value['product_code'];
                $product_id = $value['product_id'];
                $product_type = $value['product_type'];
                // attributes added by sagar : start 2-08
                $variation_count = $this->apis_models1->get_variation_stocks_maxcount($value['product_id']);
                $final_array[$key]['products_quantity'] = $variation_count;
                if ($product_type == "variation") {
                    //when product type is variation -> color ,size, length (multiple attribute)
                    $ATTR_CONDITION = " attribute_id in (";
                    $default_position = array_search($product_id, array_column($attr_mapp_data, 'product_id'));
                    if (isset($default_position) && $default_position >= 0) {
                        $ATTR_CONDITION .= $attr_mapp_data[$default_position]['group_attribute_id'];
                    }
                    $ATTR_CONDITION .= " ) ";
                    $attr_data = $this->apis_models1->getData('*', 'attribute', $ATTR_CONDITION);
                    $attr_id_arr = array();
                    $attr_name_arr = array();
                    if (is_array($attr_data)) {
                        foreach ($attr_data as $ad) {
                            $attr_id_arr[] = $ad['attribute_id'];
                            $attr_name_arr[] = $ad['attribute_name'];
                        }
                    }
                    //loop $attr_mapp_data here and form the condition for firing into attributes_values table
                    $ATTR_value_CONDITION = " attributevalue_id in (";
                    if (isset($default_position) && $default_position >= 0) {
                        $ATTR_value_CONDITION .= $attr_mapp_data[$default_position]['group_attributevalue_id'];
                    }
                    $ATTR_value_CONDITION = rtrim($ATTR_value_CONDITION, ',');
                    $ATTR_value_CONDITION .= " ) ";
                    $attr_value_data = $attribute_value_details = $this->apis_models1->getData('*', 'attributevalue', $ATTR_value_CONDITION);
                    //fire the query and fetch the data from both master tables
                    $main_atttribute = array();
                    $var_main_key = 0;
                    foreach ($attr_id_arr as $key0 => $val0) {
                        $main_atttribute[$var_main_key] = array(
                            "option" => array(
                                "id" => $val0,
                                "name" => $attr_name_arr[$key0]
                            ),
                        );
                        foreach ($attr_value_data as $key00 => $val00) {
                            if ($val0 == $val00['attribute_id']) {
                                $main_atttribute[$var_main_key]['values'][] = array(
                                    'id' => $val00['attributevalue_id'],
                                    'value' => $val00['value'],
                                    'attribuite_id' => $val00['attribute_id'],
                                    'rgb' => $val00['rgb'],
                                    'is_color' => $val00['is_color'],
                                );
                            }
                        }
                        $var_main_key += 1;
                    }
                    $final_array[$key]['attributes'] = $main_atttribute;
                    //loop throgh data u got from attributes tables and by using the attribute id,use nested for looop to check for that attribute id in the data recd from attributes_vale table
                } else {
                    $final_array[$key]['attributes'] = array();
                }

                //fethcing the all possible variations of the product
                $is_any_default = '';
                $variation_attribute_values_product = array();

                foreach ($variation_attribute_values as $varKey => $varValue) {

                    if ($varValue['product_id'] == $product_id) {
                        if ($varValue['is_default'] == 'yes') {
                            $is_any_default = $varKey;
                            if ($varValue['product_type'] == 'variation') {
                                $attribut_key = array_search($varValue['group_attributevalue_id'], array_column($attribute_value_details, 'attributevalue_id'));
                                $variation_product_weight = $attribute_value_details[$attribut_key]['value'];
                            }
                        }

                        //calculation discount
                        if (isset($varValue['discount']) && !empty($varValue['discount']) && $varValue['discount'] > 0) {
                            $var_discount_amount = $this->apis_models1->get_discount_amount($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                            $var_discount_price = $this->apis_models1->get_product_price($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                            $variation_attribute_values[$varKey]['discount_amount'] = get_converted_currency($var_discount_amount, $currency_code);
                            $variation_attribute_values[$varKey]['variation_discount_price'] = get_converted_currency($var_discount_price, $currency_code);
                            $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                        }
                       
                        $variation_attribute_values[$varKey]['sale_price'] = get_converted_currency($varValue['sale_price'], $currency_code);
                        $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                        $variation_attribute_values_product[] = $variation_attribute_values[$varKey];
                    }
                }

                //$variation_attribute_values_product;
                $final_array[$key]['variations'] = $variation_attribute_values_product;
                // attributes added by sagar : End 2-08
                $image_src = $this->apis_models1->file_view('product', $value['product_id'], '', '', 'no', 'src', 'multi', 'all');
                $final_array[$key]['products_image'] = $this->apis_models1->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');
                $multi_image_array = array();
                foreach ($image_src as $imagekey => $imgvalue) {
                    $multi_image_array[]['image'] = $imgvalue.'?d='.refreshedImage();
                }
                $final_array[$key]['images'] = $multi_image_array;
                $final_array[$key]['products_weight_unit'] = $value['unit'];
                //Title and Description On the basis of User language choice : START
                if ($userLanguage == 'en') {
                    $final_array[$key]['products_name'] = $value['title'];
                    $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description']));
                } else if ($userLanguage == 'ar') {
                    $final_array[$key]['products_name'] = $value['title_ar'];
                    $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description_ar']));
                }
                //Title and Description On the basis of User language choice : START
                $final_array[$key]['product_weight'] = $variation_product_weight;
                $final_array[$key]['product_type'] = $value['product_type'];
                $final_array[$key]['categories_id'] = $value['category'];
                $final_array[$key]['categories_name'] = $value['category_name'];
                $final_array[$key]['sub_category_id'] = $value['sub_category'];
                $final_array[$key]['sub_category_name'] = $value['sub_category_name'];
                $final_array[$key]['brand_id'] = $value['brand'];
                $final_array[$key]['brand_name'] = $value['brand_name'];
                $final_array[$key]['food_type'] = $value['food_type'];
                $final_array[$key]['is_offer'] = $value['is_offer'];
                $final_array[$key]['offer_validity'] = date('d-m-Y', strtotime($offer_validity));
                $final_array[$key]['is_galaxy_choice'] = $value['is_galaxy_choice'];
                $final_array[$key]['b2c_unit'] = $value['unit'];
                $final_array[$key]['unit_link'] = $value['unit_link'];
                $final_array[$key]['b2c_discount'] = $value['discount'];
                $final_array[$key]['discount_type'] = $value['discount_type'];
                //added by sagar : start -- additional field of product data
                $additional_fields = json_decode(( $value['additional_fields']), true);
                $name_1 = json_decode($additional_fields['name'], true);
                $value_1 = json_decode($additional_fields['value'], true);
                $main_additional_field = array();
                if (is_array($name_1)) {
                    foreach ($name_1 as $add_field_key => $add_field_value) {
                        $nameVal = $add_field_value;
                        $main_additional_field[$add_field_key]['name'] = $nameVal;
                        if (isset($value_1[$add_field_key])) {
                            $valueVal = $value_1[$add_field_key];
                            $main_additional_field[$add_field_key]['value'] = $valueVal;
                        }
                    }
                }
                $final_array[$key]['additional_fields'] = $main_additional_field;
            }
            //Adding product to their respective collection  
            $placement = count($all_collection);
            foreach ($all_collection as $key => $val) {
                if (isset($val['collection_id']) && !empty($val['collection_id'])) {
                    if (file_exists('uploads/collection_image/' . $val['collection_image'])) {
                        $all_collection[$key]['image'] = base_url() . 'uploads/collection_image/' . $val['collection_image'].'?d='.refreshedImage();
                    } else {
                        $all_collection[$key]['image'] = base_url() . 'uploads/collection_image/default.jpg?d='.refreshedImage();;
                    }
                } else {
                    $all_collection[$key]['image'] = base_url() . 'uploads/collection_image/default.jpg?d='.refreshedImage();;
                }

                //added by sagar : START 03-01
                if ($userLanguage == 'ar') {
                    $all_collection[$key]['title'] = $val['title_ar'];
                }
                //added by sagar : END 03-01
                $product_ids_array = json_decode($val['product_for_collection']);
                $product_ids = array_slice($product_ids_array, 0, 5, true);
                foreach ($product_ids as $pVal) {
                    $array_key = array_search($pVal, array_column($final_array, 'products_id'));
                    if (is_numeric($array_key)) {
                        $all_collection[$key]['collection_products'][] = $final_array[$array_key];
                    }
                }
            }

//            $brands = $this->apis_models1->fetchAllBrands( null , 'page');
            $brands = $this->db->select('brand_id,name,logo')->get_where('brand', array('name != ' => 'NA', 'status' => 'ok'))->result_array();

            $brands_data = array();
            foreach ($brands as $key => $val) {
                $brands_data[$key]['brand_id'] = $val['brand_id'];
                $brands_data[$key]['name'] = $val['name'];
               
                if (!empty($val['logo']) && file_exists('uploads/brand_image/' . $val['logo'])) {
                    $brands_data[$key]['image'] = base_url() . 'uploads/brand_image/' . $val['logo'].'?d='.refreshedImage();
                } else {
                    $brands_data[$key]['image'] = base_url() . 'uploads/brand_image/default.jpg?d='.refreshedImage();
                }
            }

            //Number of active coupons
            $coupon_count = $this->db->get_where('coupon', array('status' => 'Active'))->num_rows();
            $currency_conversion = $this->db->get_where('general_settings', array('type' => 'currency_conversion'))->row()->value;

            $delivery_charge = $this->db->get_where('general_settings', array('type' => 'delivery_charge'))->row()->value;
            if (!empty($delivery_charge)) {
                $delivery_charge = get_converted_currency($delivery_charge, $currency_code);
            } else {
                $delivery_charge = 0;
            }
            //hardcoded --ios and android url
            $ios_url = $this->db->get_where('general_settings', array('type' => 'ios_url'))->row()->value;
            $android_url = $this->db->get_where('general_settings', array('type' => 'android_url'))->row()->value;
            
            //For jobtype and social status :START
            $job_type = $this->db->select('job_type_id,name_en,name_ar,status')->get_where('job_type', array('status' => 'ok'))->result_array();
            $social_status = $this->db->select('social_status_id,name_en,name_ar,status')->get_where('social_status', array('status' => 'ok'))->result_array();
            //For jobtype and social status : END 
            
            //Default payment modes
            $paymentMethods= array(
                array('key'=>'payInCash','Name'=>'Cash'),
                array('key'=>'payInCard','Name'=>'Swipe Card'),
                array('key'=>'trolleyCredit','Name'=>'Trolley Credit'),
                array('key'=>'ePaymentCard','Name'=>'Epayment (card)'),
                //array('key'=>'ePaymentWallet','Name'=>'Epayment (wallet)'),
            );
            
            //Add Money methods
            $addMoneyMethods = array(
                array('key'=>'ePaymentCard','Name'=>'Epayment (card)'),
                //array('key'=>'ePaymentWallet','Name'=>'Epayment (wallet)'),
            );
            
            $responseData = array(
                'product_data' => $all_collection,
                'banners' => $banners,
                'categories' => $categories,
                'features_list' => $features_list,
                'brands' => $brands_data,
                'otherPages' => $otherPageData,
                'contactDetails' => $contactUsData,
                'coupon_count' => $coupon_count,
                'currency_conversion' => $currency_conversion,
                'delivery_charge' => $delivery_charge,
                'total_record' => count($all_collection),
                 'ios_url'=>$ios_url,
                'android_url'=>$android_url,
                'jobType'=>$job_type,
                'socialStatus'=>$social_status,
                'paymentMethods'=>$paymentMethods,
                'addMoneyMethods'=>$addMoneyMethods,
            );
            $this->successMessage('data_fetched_successfully', array(), $userLanguage,$responseData);
        } else {
//            'success' => 3
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    private function getAllCategories($userLanguage,$is_featured = null) {
        //category and subcategory param for future use
        $category_id = $sub_category_id = '';
        $filterData = $this->apis_models1->fetchAllFilters($category_id, $sub_category_id, $userLanguage,$is_featured);

        if (is_array($filterData) && count($filterData) > 0) {
            foreach ($filterData as $key => $val) {
                if (file_exists('uploads/category_image/' . $val['category_banner'])) {
                    $filterData[$key]['category_image'] = base_url() . 'uploads/category_image/' . $val['category_banner'].'?d='.refreshedImage();
                } else {
                    $filterData[$key]['category_image'] = base_url() . 'uploads/category_image/default.jpg?d='.refreshedImage();
                }

                if (file_exists('uploads/sub_category_image/' . $val['sub_category_banner'])) {
                    $filterData[$key]['subcategory_image'] = base_url() . 'uploads/sub_category_image/' . $val['sub_category_banner'].'?d='.refreshedImage();
                } else {
                    $filterData[$key]['subcategory_image'] = base_url() . 'uploads/sub_category_image/default.jpg?d='.refreshedImage();
                }
            }
        }
        $main_array = array();
     
        if (is_array($filterData) && COUNT($filterData) > 0) {
            foreach ($filterData as $key => $value) {
                if (!isset($main_array[$value['category_id']])) {
                    $main_array[$value['category_id']] = array(
                        "category_name" => $value['category_name'],
                        "category_id" => $value['category_id'],
                        "category_image" => $value['category_image'],
                        "is_featured" => (strtolower($value['is_featured']) == 'yes') ? 'yes' : 'no' ,
                    );
                }
                    if (!empty($value['sub_category_id'])) {
                    $main_array[$value['category_id']]['subcategory'][] = array(
                            "sub_category_name" => $value['sub_category_name'],
                            "sub_category_id" => $value['sub_category_id'],
                            "subcategory_image" => $value['subcategory_image'],
                            "parent_category_id" => $value['category_id'],
                        );
                    }
                }
            }
        return array_values($main_array);
    }

    public function getCategoryData() {
        $msg_data = array();
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['is_featured']) && !empty($_POST['is_featured']) && $_POST['is_featured']=='yes') {
                $is_featured = $_POST['is_featured'];
        }
        $header = $this->read_header();
        if ($header) {
                $categories = $this->getAllCategories($userLanguage,$is_featured);
                $total_records = count($categories);
                if(is_numeric($total_records) && $total_records > 0){
                    $response['result'] = $categories;
                    $response['total_records'] = $total_records;
                    $responseData =  array(
                        'data'=>$response,
                    );
                    $this->successMessage('data_fetched_successfully', array(), $userLanguage,$responseData);
                }else{
                    $this->errorMessage('empty_list_criteria', $msg_data, $userLanguage);
                }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }

    public function getSubCategoryData() {
        $msg_data = array();
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
                $category_id = $_POST['category_id'];
        }
        $check_condition = " 1=1";

        $header = $this->read_header();
        if ($header) {
                if(!is_numeric($category_id)){
                    $this->errorMessage('category_id_should_be_numeric', $msg_data, $userLanguage);
                }
                if(!empty($category_id)){
                    $check_condition .= " AND category = $category_id";
                }
                $sub_categories = $this->apis_models1->getData('*', 'sub_category', $check_condition);
                $total_records = count($sub_categories);
                if(is_numeric($total_records) && $total_records > 0){
                    
                    foreach($sub_categories as $key => &$val){
                        if (!empty($val['banner']) && file_exists('uploads/sub_category_image/' . $val['banner'])) {
                            $val['image'] = base_url() . 'uploads/sub_category_image/' . $val['banner'].'?d='.refreshedImage();
                        } else {
                            $val['image'] = base_url() . 'uploads/sub_category_image/default.jpg?d='.refreshedImage();
                        }
                    }
    
                    $response['result'] = $sub_categories;
                    $response['total_records'] = $total_records;
                    $responseData =  array(
                        'data'=>$response,
                    );
                    $this->successMessage('data_fetched_successfully', array(), $userLanguage,$responseData);
                }else{
                    $this->errorMessage('empty_list_criteria', $msg_data, $userLanguage);
                }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }
    public function getBrandsData() {
        $msg_data = array();
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = 'ar';
        $pagination_request = false;

        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
            $userLanguage = $_POST['userLanguage'];
        }
        if (!empty($_POST['pagination_request']) && $_POST['pagination_request'] == true) {
            $pagination_request = true;
        }
        $header = $this->read_header();
        $check_condition = '1=1';
        if ($header) {
            if(isset($_POST['search_brand'])  && trim($_POST['search_brand']) !== ''){
                $search_brand = $_POST['search_brand'];
                $check_condition .= " and name LIKE '%$search_brand%'";
            }

            if (isset($_POST['sub_category_id']) && !empty($_POST['sub_category_id'])) {
                $sub_category_id = $_POST['sub_category_id'];
                $check_condition .= " and find_in_set(brand_id, (select replace(replace(replace(brand, '[', ''), ']', ''), '\"', '') from sub_category where sub_category_id = $sub_category_id))";
            }

            $brands = $this->apis_models1->getData('brand_id,name,logo', 'brand', $check_condition);
            
            $total_records = count($brands);
            if(is_numeric($total_records) && $total_records > 0){
                $brands_data = array();
                foreach ($brands as $key => $val) {
                    $brands_data[$key]['brand_id'] = $val['brand_id'];
                    $brands_data[$key]['name'] = $val['name'];
                    if (!empty($val['logo']) && file_exists('uploads/brand_image/' . $val['logo'])) {
                        $brands_data[$key]['image'] = base_url() . 'uploads/brand_image/' . $val['logo'].'?d='.refreshedImage();
                    } else {
                        $brands_data[$key]['image'] = base_url() . 'uploads/brand_image/default.jpg?d='.refreshedImage();
                    }
                }
                $response['result'] = $brands_data;
                $response['total_records'] = $total_records;
                $responseData =  array(
                    'data'=>$response,
                );
                $this->successMessage('data_fetched_successfully', array(), $userLanguage,$responseData);
            }else{
                $this->errorMessage('empty_list_criteria', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }

    //get all product : start
    public function getAllProducts() {
        $data = json_decode(file_get_contents('php://input'), true);
        $final_array = array();
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $current_date = time();
        $skip = '0';
        $limit = DEFAULT_LIMIT;
        $page = 0;
        $type = '';
        $minprice = '';
        $maxprice = '';
        $product_id = '';
        $customer_id = '';
        $categories_id = '';
        $subcategories_id = '';
        $brand_id = '';
        $condition = ' 1=1 ';
        $wishlist_cond = ' 1=1 ';
        $variation_count = 0;
        $searchValue = '';
        $msg_data = array();
        //User choice Currency_code and userLanguage  : START
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (!empty($_POST['limit'])) {
            $limit = $_POST['limit'];
        }
         //User choice Currency_code and userLanguage  : END
        $header = $this->read_header();
        $token_array = $this->read_header_token($userLanguage);
        $user_id = $token_array['uid'] ?? 0;

        if ($header) {
            if (isset($_POST['categories_id'])) {
                $categories_id = $_POST['categories_id'];
                if (!empty($categories_id)) {
                    $con = 'category_id =' . $this->db->escape($categories_id);
                    $res = $this->apis_models1->getData('category_id', 'category', $con);
                    if (is_array($res)) {
                        $categories_id = $_POST['categories_id'];
                    } else {
                        $this->errorMessage('no_such_category_found', $msg_data, $userLanguage);
                    }
                }
            }
            if (isset($_POST['subcategories_id'])) {
                $subcategories_id = $_POST['subcategories_id'];
                if (!empty($subcategories_id)) {
                    $con = 'category =' . $this->db->escape($_POST['categories_id']);
                    $con .= ' AND sub_category_id =' . $this->db->escape($subcategories_id);
                    $res = $this->apis_models1->getData('sub_category_id', 'sub_category', $con);

                    if (is_array($res)) {
                        $subcategories_id = $_POST['subcategories_id'];
                    } else {
                        $this->errorMessage('no_such_subcategory_found', $msg_data, $userLanguage);
                    }
                }
            }

            if (isset($_POST['brand_id']) && is_array($_POST['brand_id']) && count($_POST['brand_id'] > 0)) {
                $brand_id = implode(',', $_POST['brand_id']);
            }

            if (isset($_POST['products_id'])) {
                $product_id = $_POST['products_id'];
                if (!empty($product_id)) {
                    $con = 'product_id =' . $this->db->escape($_POST['products_id']);
                    $res = $this->apis_models1->getData('product_id', 'product', $con);
                    if (is_array($res)) {
                        $product_id = $_POST['products_id'];
                        $product_dataa = $this->apis_models1->getProductDetails($product_id);
                        //updating the product view count in this cases : start
                        $this->db->where('product_id', $product_id);
                        $this->db->update('product', array(
                            'number_of_view' => $product_dataa[0]['number_of_view'] + 1,
                            'last_viewed' => time()
                        ));
                        //updating the product view count in this cases : end
                    } else {
                        $this->errorMessage('no_such_product_found', $msg_data, $userLanguage);
                    }
                }
            }

            if (isset($_POST['searchValue'])) {
                $searchValue = $_POST['searchValue'];
            }

            if (isset($_POST['page_number'])) {
                $skip = $_POST['page_number'] . '0';
                $page = $_POST['page_number'];
            }
            if (is_array($_POST['price'][0]) && isset($_POST['price'][0]['minPrice'])) {
                $minprice = $_POST['price'][0]['minPrice'];
            }
            if (is_array($_POST['price'][1]) && isset($_POST['price'][1]['maxPrice'])) {
                $maxprice = $_POST['price'][1]['maxPrice'];
            }

            if (isset($_POST['type'])) {
                $type = $_POST['type'];
                if ($type == "A - Z") {
                    $sortby = "p.title";
                    $order = "ASC";
                } else if ($type == "Z - A") {
                    $sortby = "p.title";
                    $order = "DESC";
                } else if ($type == "Price : high - low") {
                    $sortby = "p.sale_price";
                    $order = "DESC";
                } else if ($type == "Price : low - high") {
                    $sortby = "p.sale_price";
                    $order = "ASC";
                } else if ($type == "oldest") {
                    $sortby = "p.products_id";
                    $order = "ASC";
                } else if ($type == "Newest") {
                    $sortby = "p.product_id";
                    $order = "DESC";
                } else if ($type == "Most Viwed") {
                    $sortby = "p.number_of_view";
                    $order = "DESC";
                } else if ($type == "Featured Products") {
                    $sortby = "p.featured";
                    $order = "DESC";
                    $condition .= " And p.featured='yes' ";
                } else if ($type == "Todays Deal") {
                    $sortby = "p.product_id";
                    $order = "DESC";
                    $condition .= " And p.deal='ok' ";
                } else {
                    $sortby = "p.product_id";
                    $order = "DESC";
                }
            } else {
                $sortby = "p.product_id";
                $order = "DESC";
            }

            if (isset($searchValue) && !empty($searchValue)) {
                $condition .= " And (p.title like '%" . $searchValue . "%' ";
                $condition .= " OR p.title_ar like '%" . $searchValue . "%' ";
                $condition .= " OR p.product_code like '%" . $searchValue . "%' ";
                $condition .= " OR p.sku_code like '%" . $searchValue . "%' ";
                $condition .= " OR c.category_name like '%" . $searchValue . "%' ";
                $condition .= " OR c.category_name_ar like '%" . $searchValue . "%' ";
                $condition .= " OR s.sub_category_name like '%" . $searchValue . "%' ";
                $condition .= " OR s.sub_category_name_ar like '%" . $searchValue . "%' ";
                $condition .= " OR b.name like '%" . $searchValue . "%'";
                $condition .= " )";
                $sortby = "p.is_offer";
                $order = "ASC";
            }

            $collection_product_ids = '';
            if (isset($_POST['collection_id']) && !empty($_POST['collection_id'])) {
                $collection_id = $_POST['collection_id'];
                $collection_array = $this->db->get_where('collection', array('collection_id' => $_POST['collection_id']))->row_array();
                if (is_array($collection_array)) {
                    $collection_product_ids = implode(',', json_decode($collection_array['product_for_collection']));
                    if (empty($collection_product_ids)) {
                        $this->errorMessage('no_products_in_collection', $msg_data, $userLanguage);
                    }
                } else {
                    $this->errorMessage('no_such_collection_found', $msg_data, $userLanguage);
                }
            }

            $related_subcategory_product = '';
            if (isset($_POST['related_subcategory_product']) && !empty($_POST['related_subcategory_product'])) {
                $prod_id = $_POST['related_subcategory_product'];
                $related_subcategory_product_array = $this->apis_models1->getRelatedProduct($prod_id);
                if (is_array($related_subcategory_product_array)) {
                    foreach ($related_subcategory_product_array as $key => $val) {
                        $related_subcategory_product .= $val['product_id'] . ',';
                    }
                    $related_subcategory_product = rtrim($related_subcategory_product, ',');
                } else {
                    $related_subcategory_product = '0';
                }
            }
            $is_offer_product = '';
            if (isset($_POST['is_offer']) && !empty($_POST['is_offer'])) {
                $is_offer_product = $_POST['is_offer'];
            }
            $is_new_product = '';
            if (isset($_POST['is_new']) && !empty($_POST['is_new'])) {
                $is_new_product = $_POST['is_new'];
            }

            $filterProducts = array();
            $eliminateRecord = array();
            //filters are not set now they can be considered in future developments
            $filter = '';
            if (empty($filter)) {
                $data = $total_record = $this->apis_models1->getProducts($categories_id, $subcategories_id, $brand_id, $product_id, $collection_product_ids, $related_subcategory_product, $is_offer_product, $is_new_product, $limit, $page, $sortby, $order, $minprice, $maxprice, $condition);
                //added by sagar : start
                $product_id_In = ' product_id IN (';
                $product_id_vIn = ' v.product_id IN (';
                $attr_mapp_data = $variation_attribute_values = array();
                $final_array = array();

                if (is_array($data[0]) && $data[1] > 0) {
                    $all_product_ids = array_column($data[0], 'product_id');
                    $all_product_ids_str = implode(",", $all_product_ids);

                    $product_id_In .= $all_product_ids_str;
                    $product_id_vIn .= $all_product_ids_str;

                    $product_id_In = rtrim($product_id_In, ',');
                    $product_id_vIn = rtrim($product_id_vIn, ',');

                    $product_id_In .= ' )';
                    $product_id_vIn .= ' )';

                    $attr_mapp_data = $this->apis_models1->get_attmapp_data(0, $product_id_In);
                    $variation_attribute_values = $this->apis_models1->getVariationAttributeMapping(0, 0, $product_id_vIn);

                    //setting the product data
                    foreach ($data[0] as $key => $value) {
                        $variation_product_weight = $value['weight'];
                        $offer_validity = $value['offer_validity'];
                        $attribute_value_details = array();
                        $final_array[$key]['products_id'] = $value['product_id'];
                        $final_array[$key]['product_code'] = $value['product_code'];
                        $variation_count = $this->apis_models1->get_variation_stocks_maxcount($value['product_id']);
                        $final_array[$key]['products_quantity'] = $variation_count;
                        // attributes added by sagar : start
                        $product_id = $value['product_id'];
                        $product_type = $value['product_type'];

                        if ($product_type == "variation") {
                            //when product type is variation -> color ,size, length (multiple attribute)
                            $ATTR_CONDITION = " attribute_id in (";
                            $default_position = array_search($product_id, array_column($attr_mapp_data, 'product_id'));
                           
                            if (isset($default_position) && $default_position >= 0) {
                                $ATTR_CONDITION .= $attr_mapp_data[$default_position]['group_attribute_id'];
                            }
                            $ATTR_CONDITION .= " ) ";
                            $attr_data = $this->apis_models1->getData('*', 'attribute', $ATTR_CONDITION);
                            $attr_id_arr = array();
                            $attr_name_arr = array();
                            if (is_array($attr_data)) {
                                foreach ($attr_data as $ad) {
                                    $attr_id_arr[] = $ad['attribute_id'];
                                    $attr_name_arr[] = $ad['attribute_name'];
                                }
                            }
                            //loop $attr_mapp_data here and form the condition for firing into attributes_values table
                            $ATTR_value_CONDITION = " attributevalue_id in (";
                            if (isset($default_position) && $default_position >= 0) {
                                $ATTR_value_CONDITION .= $attr_mapp_data[$default_position]['group_attributevalue_id'];
                            }
                            $ATTR_value_CONDITION = rtrim($ATTR_value_CONDITION, ',');
                            $ATTR_value_CONDITION .= " ) ";
                            $attr_value_data = $attribute_value_details = $this->apis_models1->getData('*', 'attributevalue', $ATTR_value_CONDITION);
                            //fire the query and fetch the data from both master tables
                            $main_atttribute = array();
                            $var_main_key = 0;
                            foreach ($attr_id_arr as $key0 => $val0) {
                                $main_atttribute[$var_main_key] = array(
                                    "option" => array(
                                        "id" => $val0,
                                        "name" => $attr_name_arr[$key0]
                                    ),
                                );
                                foreach ($attr_value_data as $key00 => $val00) {
                                    if ($val0 == $val00['attribute_id']) {
                                        $main_atttribute[$var_main_key]['values'][] = array(
                                            'id' => $val00['attributevalue_id'],
                                            'value' => $val00['value'],
                                            'attribuite_id' => $val00['attribute_id'],
                                            'rgb' => $val00['rgb'],
                                            'is_color' => $val00['is_color'],
                                        );
                                    }
                                }
                                $var_main_key += 1;
                            }
                            $final_array[$key]['attributes'] = $main_atttribute;
                            //loop throgh data u got from attributes tables and by using the attribute id,use nested for looop to check for that attribute id in the data recd from attributes_vale table
                        } else {
                            $final_array[$key]['attributes'] = array();
                        }

                        //fethcing the all possible variations of the product
                        $is_any_default = '';
                        $variation_attribute_values_product = array();
                        foreach ($variation_attribute_values as $varKey => $varValue) {
                            if ($varValue['product_id'] == $product_id) {
                                if ($varValue['is_default'] == 'yes') {
                                    $is_any_default = $varKey;
                                    if ($varValue['product_type'] == 'variation') {
                                        $attribut_key = array_search($varValue['group_attributevalue_id'], array_column($attribute_value_details, 'attributevalue_id'));
                                        $variation_product_weight = $attribute_value_details[$attribut_key]['value'];
                                    }
                                }
                                //calculation discount
                                if (isset($varValue['discount']) && !empty($varValue['discount']) && $varValue['discount'] > 0) {
                                    $var_discount_amount = $this->apis_models1->get_discount_amount($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                                    $var_discount_price = $this->apis_models1->get_product_price($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                                    $variation_attribute_values[$varKey]['discount_amount'] = get_converted_currency($var_discount_amount, $currency_code);
                                    $variation_attribute_values[$varKey]['variation_discount_price'] = get_converted_currency($var_discount_price, $currency_code);
                                    $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                                }
                                $sale_price = get_converted_currency($varValue['sale_price'], $currency_code);
                                $variation_attribute_values[$varKey]['sale_price'] = $sale_price;
                                $variation_attribute_values[$varKey]['purchase_price'] = !empty($varValue['discount']) ? get_converted_currency($varValue['purchase_price'], $currency_code) : $sale_price;
                                $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                                $variation_attribute_values_product[] = $variation_attribute_values[$varKey];
                            }
                        }

                        //$variation_attribute_values_product;
                        $final_array[$key]['variations'] = $variation_attribute_values_product;
                        // attributes added by sagar : End
                        $image_src = $this->apis_models1->file_view('product', $value['product_id'], '', '', 'no', 'src', 'multi', 'all');
                        $final_array[$key]['products_image'] = $this->apis_models1->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');
                        $multi_image_array = array();
                        foreach ($image_src as $imagekey => $imgvalue) {
                            $multi_image_array[]['image'] = $imgvalue.'?d='.refreshedImage();
                        }
                        $final_array[$key]['images'] = $multi_image_array;
                        $final_array[$key]['products_weight_unit'] = $value['unit'];
                        //Title and Description On the basis of User language choice : START
                        if ($userLanguage == 'en') {
                            $final_array[$key]['products_name'] = $value['title'];
                            $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description']));
                        } else if ($userLanguage == 'ar') {
                            $final_array[$key]['products_name'] = $value['title_ar'];
                            $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description_ar']));
                        }
                     
                        //Title and Description On the basis of User language choice : START
//                        $final_array[$key]['product_weight'] = $value['weight'];
                        $final_array[$key]['product_weight'] = $variation_product_weight;
                        $final_array[$key]['product_type'] = $value['product_type'];
                        $final_array[$key]['categories_id'] = $value['category'];
                        $final_array[$key]['categories_name'] = $value['category_name'];
                        $final_array[$key]['sub_category_id'] = $value['sub_category_id'];
                        $final_array[$key]['sub_category_name'] = $value['sub_category_name'];
                        $final_array[$key]['brand_id'] = $value['brand_id'];
                        $final_array[$key]['brand_name'] = $value['brand_name'];
                        $final_array[$key]['food_type'] = $value['food_type'];
                        $final_array[$key]['is_offer'] = $value['is_offer'];
                        $final_array[$key]['purchase_price'] = !empty($varValue['discount']) ? $value['purchase_price'] : $value['sale_price'];
                        $final_array[$key]['offer_validity'] = date('d-m-Y', strtotime($offer_validity));
                        $final_array[$key]['is_galaxy_choice'] = $value['is_galaxy_choice'];
                        $final_array[$key]['is_new_product'] = $value['featured'];
                        $final_array[$key]['b2c_unit'] = $value['unit'];
                        $final_array[$key]['unit_link'] = $value['unit_link'];
                        $final_array[$key]['b2c_discount'] = $value['discount'];
                        $final_array[$key]['discount_type'] = $value['discount_type'];
                        $final_array[$key]['currency_code'] = $currency_code;
                        //added by sagar : start -- additional field of product data
                        $additional_fields = json_decode(( $value['additional_fields']), true);
                        $name_1 = json_decode($additional_fields['name'], true);
                        $value_1 = json_decode($additional_fields['value'], true);
                        $main_additional_field = array();
                        if (is_array($name_1)) {
                            foreach ($name_1 as $add_field_key => $add_field_value) {
                                $nameVal = $add_field_value;
                                $main_additional_field[$add_field_key]['name'] = $nameVal;
                                if (isset($value_1[$add_field_key])) {
                                    $valueVal = $value_1[$add_field_key];
                                    $main_additional_field[$add_field_key]['value'] = $valueVal;
                                }
                            }
                        }
                        $final_array[$key]['additional_fields'] = $main_additional_field;
                        //added by Arjun 07-07-2023 : Start

                        $this->db->where_in('product_id', $all_product_ids);
                        $this->db->where('user_id', $user_id);
                        $this->db->where('cart_session_id', md5($this->device_id));
                        $cartItem = $this->db->get('cart')->result_array();
                        $cartProductIds = array_column($cartItem, 'product_id');
                        $final_array[$key]['InCartFlag'] = 0;
                        if (in_array($product_id,$cartProductIds)) {
                            $final_array[$key]['InCartFlag'] = 1;
                            foreach ($cartItem as $item) {
                                if ($item['product_id'] == $product_id) {
                                    $final_array[$key]['cart_id'] = $item['cart_id'];
                                    $final_array[$key]['qty'] = $item['qty'];
                                    break;
                                }
                            }
                        }
                        //added by Arjun 07-07-2023 : End
                        //added by sagar : end --additional field of product data
                    }
                    $responseData = array(
                        'product_data' => $final_array,
                        'total_record' => $data[1]
                    );
                    $this->successMessage('returned_all_products', array(), $userLanguage,$responseData);
                } else {
                    $responseData = array(
                        'product_data' => array(),
                        'total_record' => 0
                    );
                    $this->errorMessage('no_product_found_please_try_again_later', array() , $userLanguage,$responseData);
                }
            }
        } else {
             $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    //get all product : end

    public function saveNewsletter() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $msg_data=array();
        //user choice : START
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        //user choice : END

        $header = $this->read_header();
        $newsletter_email = "";
        if ($header) {
            if (isset($_POST['newsletter_email']) && !empty($_POST['newsletter_email'])) {
                $newsletter_email = strtolower($_POST['newsletter_email']);
                if (filter_var($newsletter_email, FILTER_VALIDATE_EMAIL)) {
                    $emailExist = $this->apis_models1->verify_if_unique('newsletter', 'newsletter_email = ' . $this->db->escape($newsletter_email));
                    if (is_array($emailExist)) {
                        $this->errorMessage('email_id_already_exist', $msg_data, $userLanguage);
                    }
                    $data_array = array(
                        'newsletter_email' => $newsletter_email,
                        'created_on' => date('Y-m-d h:i:s'),
                    );

                    $this->apis_models1->insertData('newsletter', $data_array, 1);
                    $this->successMessage('subcribed_to_newslatters', $msg_data, $userLanguage);

                } else {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
            }
            } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            }
        }


    function processForgotPassword() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $bcustomer_email = "";
        $header = $this->read_header();
        $userLanguage = 'ar';
        if ($header) {
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
            }
            if (isset($_POST['bcustomer_email']) && !empty($_POST['bcustomer_email'])) {
                $bcustomer_email = $_POST['bcustomer_email'];
                if (filter_var($bcustomer_email, FILTER_VALIDATE_EMAIL)) {
                    $con = ' email = ' . $this->db->escape($bcustomer_email);
                    $userData = $this->apis_models1->getData('email,user_id', 'user', $con);
                    if (!is_array($userData)) {
                        $this->errorMessage('email_id_not_registered', $msg_data, $userLanguage);
                    }
                    //for forget password flow
                    $random = $this->generateRandomString();
                    $data_array['fpwd_key'] = $random;
                    $data_array['fpwd_flag'] = 'Active';
                    $user_id = $userData[0]['user_id'];
                    $check_condition = 'user_id=' . $this->db->escape($user_id);
                    $update_result = $this->apis_models1->updateRecord('user', $data_array, $check_condition);
                    $creating_link = $random . '------abcde)))))' . $bcustomer_email;
                    $creating_link = base64_encode($creating_link);
                    $link = base_url() . 'userchangepassword/' . $creating_link;
                    $url = $link;
                    if ($this->email_model->password_reset_email('user', $user_id, $url, $userLanguage)) {
                        $this->successMessage('email_send_successfully', $msg_data, $userLanguage);
                    } else {
                        $this->errorMessage('email_send_failed', $msg_data, $userLanguage);
                    }
                    //for forget password flow
                } else {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function saveEnquiry() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $customer_name = $customer_email = $customer_mobile = $customer_msg = "";
        $userLanguage = "ar";
        $msg_data= array();
        $token_array = $this->read_header_token($userLanguage);
        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
            
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
            }
            if (isset($_POST['customer_name']) && !empty($_POST['customer_name'])) {
                $customer_name = $_POST['customer_name'];
            } else {
                $this->errorMessage('please_enter_your_name', $msg_data, $userLanguage);
            }

            if (isset($_POST['customer_email']) && !empty($_POST['customer_email'])) {
                $customer_email = strtolower($_POST['customer_email']);
                if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
            }

            if (isset($_POST['customer_mobile']) && !empty($_POST['customer_mobile'])) {
                if (( preg_match($this->mobile_check, $_POST['customer_mobile']))) {
                    $customer_mobile = $_POST['customer_mobile'];
                } else {
                    $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
            }

            if (isset($_POST['customer_msg']) && !empty($_POST['customer_msg'])) {
                $customer_msg = $_POST['customer_msg'];
            }

            $data_array = array(
                'name' => $customer_name,
                'mobile' => $customer_mobile,
                'email' => $customer_email,
                'msg' => $customer_msg,
                'created_on' => date('Y-m-d h:i:s'),
            );
            $this->apis_models1->insertData('enquiry', $data_array, 1);
            $this->successMessage('enquired_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function saveEnquiryForm() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $name = $email = $phone = $msg =  $subject = "";
        $userLanguage = "ar";
        $msg_data = array();
        $check_condition = " 1=1 And status='Active' ";
        $token_array = $this->read_header_token($userLanguage);

        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = ' . $this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData('user_id', 'user', $check_condition);

            if (!is_array($merchantExist)) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
            }
            if (isset($_POST['name']) && !empty($_POST['name'])) {
                $name = $_POST['name'];
            } else {
                $this->errorMessage('please_enter_your_name', $msg_data, $userLanguage);
            }
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $email = strtolower($_POST['email']);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
            }
            if (isset($_POST['phone']) && !empty($_POST['phone'])) {
                if ((preg_match($this->mobile_check, $_POST['phone']))) {
                    $phone = $_POST['phone'];
                } else {
                    $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
            }

            if (isset($_POST['msg']) && !empty($_POST['msg'])) {
                $msg = $_POST['msg'];
            } else {
                $this->errorMessage('please_enter_text_in_msg_box', $msg_data, $userLanguage);
            }
            if (isset($_POST['subject']) && !empty($_POST['subject'])) {
                $subject = $_POST['subject'];
            } else {
                $this->errorMessage('please_enter_subject', $msg_data, $userLanguage);
            }
            $from_where = array('type' => 'user', 'id' => $user_id);
            $to_where = array('type' => 'admin', 'id' => "");
            $view_status = array('user_show'=> 'ok', 'admin_show' => 'no');
//            $subject = " Enquire raised on ".date('d/m/Y h:i A');  --OLD FLOW

            $data_array =  array(
                'time' => time(),
                'name' => $name,
                'mobile' => $phone,
                'email' => $email,
                'from_where' => json_encode($from_where),
                'subject' => $subject,
            );
            $enquiry_id = $this->apis_models1->insertData('ticket', $data_array, 1);

            if (!empty($enquiry_id)) {
                $data = array(
                    'time' => time(),
                    'subject' => $subject,
                    'ticket_id' => $enquiry_id,
                    'from_where' => json_encode($from_where),
                    'to_where' => json_encode($to_where),
                    'view_status' => json_encode($view_status),
                    'message' => $msg
                );
                $this->apis_models1->insertData('ticket_message', $data, 1);
            }
            $this->successMessage('enquired_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    function fetchEnquiryList() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $msg_data = array();
        $table = 'ticket';
        $limit = 10;
        $page = 0;
        $sortby = " ticket_id ";
        $order = " DESC ";
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);

        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $user_exist = $this->apis_models1->getData( 'user_id', 'user', $check_condition);

            if (!is_array($user_exist)) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            if (!empty($_POST['page_no'])) {
                $page = $_POST['page_no'];
            }
            if (!empty($_POST['limit'])) {
                $limit = $_POST['limit'];
            }
            $condition = ' from_where Like '.'\'%"type":"user","id":"'.$user_id.'"%\'';
            $result_data = $this->apis_models1->fetchedLimitedData($table, $condition, $sortby, $order, $limit, $page);

            if (is_array($result_data) && count($result_data) > 0) {
                foreach($result_data as $key => $val) {
                    $count = $this->apis_models1->ticket_unread_messages($val['ticket_id'], 'user');

                    $final_array[$key]['enquiry_id'] = $val['ticket_id'];
                    $final_array[$key]['name'] = $val['name'];
                    $final_array[$key]['mobile'] = $val['mobile'];
                    $final_array[$key]['email'] = $val['email'];
                    $final_array[$key]['subject'] = $val['subject'];
                    $final_array[$key]['new_msg_count'] = $count;
                    $final_array[$key]['status'] = $val['view_status'] == 'Closed' ? $val['view_status'] : ' Opened';
                    $final_array[$key]['date_time'] = date('Y-m-d h:i A',$val['time']);
                }
                $responseData = array(
                    'data' => $final_array,
                );
               $this->successMessage('fetched_enquiry_list',$msg_data, $userLanguage,$responseData);
            } else {
               $this->errorMessage('empty_enquiry_list', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    function fetchEnquiryMsgList() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $_POST = $data;
        }
        $msg_data = array();
        $table = 'ticket_message';
        $limit = 20;
        $page = 0;
        $sortby = " ticket_message_id ";
        $order = " DESC ";
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);

        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );

            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            $enquiry_id = 0;

            if (!empty($_POST['enquiry_id'])) {
                $enquiry_id = $_POST['enquiry_id'];
                $condition = ' from_where Like '.'\'%"type":"user","id":"'.$user_id.'"%\'';
                $condition .= ' AND ticket_id = '. $this->db->escape($enquiry_id);
                $enquiryExist = $this->apis_models1->getData('*', 'ticket', $condition);

                if (!is_array($enquiryExist)) {
                    $this->errorMessage('no_such_enquiry_found', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('provide_enquiry_id', $msg_data, $userLanguage);
            }
            $condition = ' ticket_id = '. $this->db->escape($enquiry_id);
            $resultdata = $this->apis_models1->getData('*', $table, $condition, null, ' ticket_message_id ASC');

            if (is_array($resultdata) && count($resultdata)> 0) {
                foreach($resultdata as $key => $val) {
                    $from_where_array = json_decode($val['from_where'], true);
                    $to_where_array = json_decode($val['to_where'], true);
                    $from_where = ($from_where_array['type'] == 'user') ? 'You' : 'Admin';
                    $to_where = ($to_where_array['type'] == 'user') ? 'You' : 'Admin';

                    $view_status = json_decode($val['view_status'], true);
                    $isNewMsg = ($view_status['user_show']  == 'ok' ) ? 'no' : 'yes';
                    $final_array[$key]['enquiry_id'] = $val['ticket_id'];
                    $final_array[$key]['enquiry_message_id'] = $val['ticket_message_id'];
                    $final_array[$key]['from'] = $from_where;
                    $final_array[$key]['to'] = $to_where;
                    $final_array[$key]['subject'] = $val['subject'];
                    $final_array[$key]['message'] = $val['message'];
                    $final_array[$key]['isNewMsg'] = $isNewMsg;
                    $final_array[$key]['date_time'] = date('Y-m-d h:i A',$val['time']);
                    $final_array[$key]['num_of_files'] = !empty($val['num_of_files']) ? (int)$val['num_of_files'] : 0;
                    // Initialize an array to hold image sources
                    $image_sources = array();
                    
                    if ($val['num_of_files'] > 0) {
                        $count = $val['num_of_files'];
                        for ($x = 0; $x < $count; $x++) {
                            $image_files = glob('uploads/enquiries_docs/enquiries_' . $val['ticket_message_id'] . '_' . $x . '.*');

                            if (!empty($image_files)) {
                                $image_path = $image_files[0];
                                $image_extension = pathinfo($image_path, PATHINFO_EXTENSION);
                                $image_sources[] = base_url() . $image_path . '?d=' . refreshedImage();
                            }
                        }
                    }
                    $final_array[$key]['files'] = $image_sources;
                }
                $responseData = array(
                    'data'=>$final_array,
                );
                $this->apis_models1->ticket_message_viewed($enquiry_id, 'user');
                $this->successMessage('fetched_enquiry_msg_list',$msg_data, $userLanguage,$responseData);
            } else {
               $this->errorMessage('empty_enquiry_msg_list', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function replyEnquiry() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $customer_msg = "";
        $subject = "";
        $userLanguage = "ar";
        $msg_data = array();
        $check_condition = " status='Active' ";
        $token_array = $this->read_header_token($userLanguage);

        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );

            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
            }
            if (!empty($_POST['enquiry_id'])) {
                $enquiry_id = $_POST['enquiry_id'];
                $condition = ' from_where Like '.'\'%"type":"user","id":"'.$user_id.'"%\'';
                $condition .= ' AND ticket_id = '. $this->db->escape($enquiry_id);
                $enquiryExist = $this->apis_models1->getData( 'subject', 'ticket', $condition );

                if (!is_array($enquiryExist)) {
                    $this->errorMessage('enquiry_not_found', $msg_data, $userLanguage);
                }
                $subject = $enquiryExist[0]['subject'];
            } else {
                $this->errorMessage('please_provide_enquiry_id', $msg_data, $userLanguage);
            }
            if (isset($_POST['customer_msg']) && !empty($_POST['customer_msg'])) {
                $customer_msg = $_POST['customer_msg'];
            } else {
                $this->errorMessage('please_enter_text_in_msg_box', $msg_data, $userLanguage);
            }
            $from_where = array('type' => 'user', 'id' => $user_id);
            $to_where = array('type' => 'admin', 'id' => "");
            $view_status = array('user_show' => 'ok', 'admin_show' => 'no');
            $num_of_files = count($_FILES["files"]['name']);
            $data_array =  array(
                'time' => time(),
                'ticket_id' => $enquiry_id,
                'subject' => $subject,
                'from_where' => json_encode($from_where),
                'to_where' => json_encode($to_where),
                'view_status' => json_encode($view_status),
                'message' => $customer_msg,
                'num_of_files' => $num_of_files,
            );
            $this->apis_models1->insertData('ticket_message', $data_array);
            $last_id = $this->db->insert_id();
            if (!empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $index => $uploadedFile) {
                    if (!empty($_FILES['files']['name'][$index])) {
                        $image_extension = strtolower(pathinfo($_FILES['files']['name'][$index], PATHINFO_EXTENSION));
                        // allowed extension jpg, jpeg, png , pdf
                        if (in_array($image_extension, ALLOWED_EXTENSIONS_FOR_ENQUIRY)) {
                            $image_path = 'uploads/enquiries_docs/enquiries_' . $last_id . '_' .$index.'.' . $image_extension;
                            // Delete previous image if it exists
                            $previous_image_files = glob('uploads/enquiries_docs/enquiries_' . $last_id . '_' .$index.'.*');
                            foreach ($previous_image_files as $previous_image_file) {
                                unlink($previous_image_file);
                            }
                            // Move the uploaded image to the desired path
                            move_uploaded_file($_FILES['files']['tmp_name'][$index], $image_path);
                        } else {
                            echo "File upload is not supported for this type";
                            exit;
                        }
                    }
                }
            }
            $this->successMessage('enquired_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    //To check product stock quantity before placing Order : START
    function checkProductStock() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        //user choice : START
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        //user choice : END
        $token_array = $this->read_header_token($userLanguage);
        
        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $products = array();
            if (isset($_POST['products']) && is_array($_POST['products']) && count($_POST['products']) > 0) {
                $products = $_POST['products'];
            } else {
                $this->errorMessage('atleast_one_product_is_required_to_create_an_order', $msg_data, $userLanguage);
                exit();
            }
            
            //ORDER MUST BE PLACED IN SDG ONLY 
            // if ($currency_code != 2) {
            //     $this->errorMessage('proceed_in_sdg_currency_only', $msg_data, $userLanguage);
            //     exit();
            // }
            
            foreach ($products as $key => $val) {
                $product_result_array[] = $val['variation_id'];
                if ($val['qty'] <= 0) {
                    $this->errorMessage('product_quantity_must_be_greater_than_0', $msg_data, $userLanguage);
                    exit();
                }
            }

            if (count($product_result_array) !== count(array_flip($product_result_array))) {
                $this->errorMessage('duplicate_product_in_cart', $msg_data, $userLanguage);
                exit();
            }

            if (is_array($products) && count($products) > 0) {
                foreach ($products as $key => $value) {
                    $product_id = $value['product_id'];
                    $variation_id = $value['variation_id'];
                    $product_data_fromdb = $this->apis_models1->getProductVariations($product_id, $variation_id,$userLanguage);
                    if (is_array($product_data_fromdb) && isset($product_data_fromdb[0])) {
                        $qty = $value['qty'];
                        $title = $product_data_fromdb[0]['title'];
                        if (empty($product_data_fromdb[0]['variation_stock']) || $product_data_fromdb[0]['variation_stock'] < $qty) {
//                            $err_msg = $title . ' is OUT OF STOCK';
                            $this->errorMessage('out_of_stock_product', $msg_data, $userLanguage,array(),$title);
                        }
                    }
                }
                $this->successMessage('product_quantity_is_ok',$msg_data, $userLanguage);
            } else {
                $this->errorMessage('please_add_product_in_cart', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    //To check product stock quantity before placing Order : END

    function addToOrder() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $msg_data = array();
        $token_array = $this->read_header_token();
        $final_product_array = array();
        $order_shipping_address = array();
        $payable_amt_final = 0;
        $tax_final = 0;
        $shipping_final = 0;
        $coupon_discount_final = 0;
        $total_coupon_discount = 0;
        $store_master_id = 0;
        $payment_type = $payment_method = "";
        $delivery_type = "";
        $loyaltycard_option = "no";
        $credit_option = "no";
        $check_condition = " 1=1 ";
        $coupon_applied = 'no';
        $stock_decrease = true;
        
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = 'ar';
        //user choice : START
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
            
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        //user choice : END
        
        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $products = array();
            if (isset($_POST['products']) && is_array($_POST['products']) && count($_POST['products']) > 0) {
                $products = $_POST['products'];
            } else {
                $this->errorMessage('atleast_one_product_is_required_to_create_an_order', $msg_data, $userLanguage);
                exit();
            }
            
            //ORDER MUST BE PLACED IN SDG ONLY 
            // if ($currency_code != 2) {
            //     $this->errorMessage('proceed_in_sdg_currency_only', $msg_data, $userLanguage);
            //     exit();
            // }
            
            foreach ($products as $key => $val) {
                $product_result_array[] = $val['variation_id'];
                if ($val['qty'] <= 0) {
                    $this->errorMessage('product_quantity_must_be_greater_than_0', $msg_data, $userLanguage);
                    exit();
                }
            }
            if (count($product_result_array) !== count(array_flip($product_result_array))) {
                $this->errorMessage('duplicate_product_in_cart', $msg_data, $userLanguage);
                exit();
            }

            $userCardId = 0 ;
            $cardPin = "";
            $cardNumber = "";
            if (isset($_POST['payment_type']) && !empty($_POST['payment_type'])) {
                if ($_POST['payment_type'] == 'payInCash' || $_POST['payment_type'] == 'payInCard' || $_POST['payment_type'] == 'ePaymentCard' 
                    || $_POST['payment_type'] == 'ePaymentWallet' ||$_POST['payment_type'] == 'trolleyCredit') {
                    $payment_type = $payment_method = $_POST['payment_type'];
//                    if($payment_type == 'ePayment'){
                    if($payment_type == 'ePaymentCard' || $payment_type == 'ePaymentWallet' ){
                        if (isset($_POST['selectedCardId']) && !empty($_POST['selectedCardId'])) {
                            $userCardId = $_POST['selectedCardId'];
                            $check_condition .= ' AND user_id = '.$this->db->escape($user_id);
                            $check_condition .= ' AND user_card_id = '.$this->db->escape($userCardId);
                            $cardDataArray = $this->apis_models1->getData( 'user_card_id,card_name,card_number', 'user_card', $check_condition );
                            if(!is_array($cardDataArray)){
                                $this->errorMessage('no_such_card_found', $msg_data, $userLanguage);
                            }
                            $user_cardNumber = $cardDataArray[0]['card_number'];
                            $cardNumber = base64_decode($user_cardNumber);
                        }else{
                            $this->errorMessage('please_select_card_for_payment', $msg_data, $userLanguage);
                    exit();
                }

                        if (isset($_POST['cardPin']) && !empty($_POST['cardPin'])) {
                            $cardPin = $_POST['cardPin'];
                        }else{
                            $this->errorMessage('plese_enter_card_pin', $msg_data, $userLanguage);
                                exit();
                            }
                                }
                            } else {
                    $this->errorMessage('invalid_payment_type', $msg_data, $userLanguage);
                                exit();
                            }
                            } else {
                $this->errorMessage('please_select_payment_type', $msg_data, $userLanguage);
                                exit();
                            }

            $delivery_type = 'homeDelivery';
            $city_id = $area_id = 0;
            $userWalletType = "normal";
            $smsPhoneNumber = "";
            if (isset($_POST['address_id']) && !empty($_POST['address_id'])) {
                $address_id = $_POST['address_id'];
//                $addressCondition = ' address_id = '.$this->db->escape($address_id);
//                $addressCondition .= ' AND user_id = '.$this->db->escape($user_id);
//                $addressExist = $this->apis_models1->getData( 'address_id,city_id,area_id', 'user_address', $addressCondition );
                $userAddressExist = $this->apis_models1->checkDeliveryAddress($address_id,$user_id);
                
                if (!is_array( $userAddressExist ) ) {
                   $this->errorMessage('no_such_address_found', $msg_data, $userLanguage);
                            }
                $city_id = $userAddressExist[0]['city_id'];
                $area_id = $userAddressExist[0]['area_id'];
                $userWalletType = $userAddressExist[0]['wallet_type'];
              
                $order_shipping_address['phone_number']  = $smsPhoneNumber  = $userAddressExist[0]['phone'];
                $order_shipping_address['email']       = $userAddressExist[0]['email'];
                $order_shipping_address['first_name']      = $userAddressExist[0]['first_name'];
                $order_shipping_address['fourth_name']      = $userAddressExist[0]['fourth_name'];
                $order_shipping_address['title']      = $userAddressExist[0]['title'];
                $order_shipping_address['address_number']      = $userAddressExist[0]['number'];
                $order_shipping_address['address_1']      = $userAddressExist[0]['address_1'];
                $order_shipping_address['address_2']      = $userAddressExist[0]['address_2'];
                $order_shipping_address['landmark']      = $userAddressExist[0]['landmark'];
                $order_shipping_address['city']      = $userAddressExist[0]['city_name_en'];
                $order_shipping_address['area']      = $userAddressExist[0]['area_name_en'];
                
            }else {
                $this->errorMessage('please_select_address_for_delivery', $msg_data, $userLanguage);
                                exit();
                            }

        //for delivery date and timeslot : START - 09-04-2020 PHASE-2
            $date = date('Y-m-d');
            if(isset($_POST['delivery_date'])){
                if($_POST['delivery_date'] < $date){
                    $this->errorMessage('date_cannot_be_less_than_current_date', $msg_data, $userLanguage);
                            }
                $date = $_POST['delivery_date'];
            }else{
                $this->errorMessage('please_provide_delivery_date', $msg_data, $userLanguage);
                            }
            
            $checkDate =  $this->validateDate($date);
            if(!$checkDate){
                $this->errorMessage('invalid_date', $msg_data, $userLanguage);
                            }
            $timeslot = "";
            $timeslotDay =  date("l", strtotime($date));
            $timeslots_id = 0;
            $orderLimitPerTimeslot = 0;
            if(isset($_POST['timeslots_id']) && !empty($_POST['timeslots_id'])){
                $timeslots_id = $_POST['timeslots_id'];
                $timeslot_condition = ' timeslots_id = '. $this->db->escape($timeslots_id);
                $timeslot_condition .= ' AND day  = '. $this->db->escape($timeslotDay);
                $timeslot_condition .= ' AND status = "ok" ';
                $timeslotExist = $this->apis_models1->verify_if_unique('timeslots',$timeslot_condition );
                if (!is_array($timeslotExist)) {
                    $this->errorMessage('invalid_timeslot_selection', $msg_data, $userLanguage);
                        }
                $startTime = date('h:i A',strtotime($timeslotExist[0]['start_time']));
                $endTime = date('h:i A',strtotime($timeslotExist[0]['end_time']));
                $timeslot = $startTime .' - '. $endTime;
                $orderLimitPerTimeslot = $timeslotExist[0]['order_limit'];
            }else{
                $this->errorMessage('please_provide_delivery_timeslot', $msg_data, $userLanguage);
            }

            $deliveryDateInCart =  $date;
            $TimeSlotInCart =  $timeslot;
            $delivery_date_timeslot_array[] = array(
                'date' => $date,
                'timeslot' => $timeslot,
            );

            $delivery_date_timeslot_encoded = json_encode($delivery_date_timeslot_array);
            //per day timeslots order -- START
            /*$saleCount = $this->apis_models1->getSalesCountForTimeslots($deliveryDateInCart,$timeslots_id);
            if($saleCount >= $orderLimitPerTimeslot){
                 $this->errorMessage('order_timeslot_limit_reached', $msg_data, $userLanguage);
            } */
            //per day timeslots order -- END
        //for delivery date and timeslot : END - 09-04-2020 PHASE-2

            //Store comma separted supplier ids for auto assign store 
            $supplier_ids_array =  array();

            $check_condition .= ' AND user_id = ' . $this->db->escape($user_id);
            if (is_array($products) && count($products) > 0) {
                $a = $b = 0;
                $subtotal_amount = 0;
                foreach ($products as $key => $value) {
                 
                    $product_id = $value['product_id'];
                    $variation_id = $value['variation_id'];
                    $product_data_fromdb = $this->apis_models1->getProductVariations($product_id, $variation_id,$userLanguage);

                    if (is_array($product_data_fromdb) && isset($product_data_fromdb[0])) {
                        $qty = $value['qty'];
                        $title = $product_data_fromdb[0]['title'];
                        //product price handling : START 
                        if (empty($product_data_fromdb[0]['variation_stock']) || $product_data_fromdb[0]['variation_stock'] < $qty) {
                            //$err_msg = $title . ' is OUT OF STOCK';
                            $this->errorMessage('out_of_stock_product', $msg_data, $userLanguage,array(),$title);
                        }

                        $rate = 0;
                        $product_discount = $unit = "";
                        $product_discount_type = $product_data_fromdb[0]['discount_type'];
                        //User Type check value
                        $unit = $product_data_fromdb[0]['unit'];
                        $product_discount = (double) $product_data_fromdb[0]['discount'];
                        $rate = $product_data_fromdb[0]['variation_price'];
                        $variation_purchase_price = $product_data_fromdb[0]['variation_purchase_price'];

                        $rowid = md5($variation_id);
                        $subtotal = $qty * $rate;

                        //Attribute selection: start
                        $option = array();
                        if (isset($value['attributes']) && !empty($value['attributes']) && is_array($value['attributes'])) {
                            $attributes_array = $value['attributes'];
                            foreach ($attributes_array as $attKey => $attVal) {
                                $attributename = 'choice_' . $attVal['products_options_values_id'];
                                $option[$attributename] = array(
                                    'title' => $attVal['products_options'],
                                    'value' => $attVal['name'],
                                    //added by sagar : 14-08 START 
                                    'attribute_id' => $attVal['products_options_id'],
                                    'attribute_value_id' => $attVal['products_options_values_id'],
                                        //added by sagar : 14-08 END 
                                );
                            }
                        }
                        //Attribute selection : end
                        //calculations part start
                        $calculated_price = (double) $rate * (int) $qty;
                        $discount_per_piece = 0;
                        $total_discount = 0;

                        if ($product_discount_type == 'percent' && $product_discount > 0 && $product_discount < 100) {
                            // $total_discount = $calculated_price * $product_discount / 100;
                            // $discount_per_piece = (double) $rate * $product_discount / 100;
                            $total_discount = (double)($rate - $variation_purchase_price) * (int) $qty;
                            $discount_per_piece = (double)($rate - $variation_purchase_price) ;

                        } elseif ($product_discount_type == 'amount' && $product_discount > 0 && $product_discount < (double) $product_data_fromdb[0]['sale_price']) {
                            $total_discount = $product_discount * $qty;
                            $discount_per_piece = (double) $product_data_fromdb[0]['discount'];
                        }

                        $payable_amount_after_discount = $calculated_price = $rate - $discount_per_piece;


                        //coupon PART
                        $coupon_code = '';
                        $coupon_id = 0;
                        $coupon_detail = '';
                        $discount_amount = '';
                        if (isset($_POST['coupon_code']) && !empty($_POST['coupon_code'])) {
                            $coupon_details = $this->check_coupon_used(trim($_POST['coupon_code']), $user_id, $payable_amount_after_discount);
                            if ($coupon_details) {
                                $coupon_applied = 'yes';
                                $final_discount = $coupon_details['final_discount'];
                                $coupon_detail_here = $coupon_details['coupon_details'];
                                $coupon_id = $coupon_details['coupon_id'];
                                $coupon_code = $coupon_details['coupon_code'];

                                $coupon_code = $coupon_code;
                                $coupon_id = $coupon_id;
                                $coupon_detail = json_encode($coupon_detail_here);
                                $discount_amount = $final_discount;

                                $payable_amount_after_discount = $payable_amount_after_discount - $final_discount;
                                $calculated_coupon_discount = $final_discount;
                            }
                        }
                        //coupon END
                        
                        //SET AS 0 as no product tax here direct service tax 
                        $product_tax = 0;
                        if (!is_numeric($product_tax) && empty($product_tax)) {
                            $product_tax = 0;
                        }
                        $product_tax_type = 'percent';
                        $tax_value_in_crn = 0;
                        $total_tax = 0;
                        if ($product_tax_type == 'percent' && $product_tax > 0 && $product_tax < 100) {
                            $total_tax = $payable_amount_after_discount * $product_tax / 100;
                            $tax_value_in_crn = ( (double) $product_tax * (double) $payable_amount_after_discount ) / 100;
                        } elseif ($product_tax_type == 'amount' && $product_tax > 0) {
                            $total_tax = $product_tax * (int) $qty;
                            $tax_value_in_crn = (double) $product_tax;
                        }

                        $subtotal_payable_Amt = $payable_amount_after_discount * $qty;
                        $payable_amt_final += $subtotal_payable_Amt;
                        $calulated_discount_amount = $calculated_coupon_discount * $qty;
                        $total_coupon_discount += $calulated_discount_amount;
                        $calculated_tax = $total_tax * $qty;
                        $tax_final += $calculated_tax;
                        $total_shipping = 0;
                        $shipping_value_in_crn = 0;
                     
                        $subtotal_amount += ( (double) $subtotal_payable_Amt + (double) $calculated_tax);
                        
                        //calculations part end
                        
                        //Store Unique supplier ids for order : START
                       
                        if(!in_array($product_data_fromdb[0]['supplier'],$supplier_ids_array)){
                           array_push($supplier_ids_array,$product_data_fromdb[0]['supplier']);
                        }
                        //Store Unique supplier ids for order : END
                        $calculated_price = round($calculated_price,3);
                        $cart = array(
                            'id' => $value['variation_id'],
                            'product_id' => $value['product_id'],
                            'variation_id' => $value['variation_id'],
                            'product_type' => $product_data_fromdb[0]['product_type'],
                            'qty' => (int) $qty,
                            'option' => json_encode($option),
                            'price' => (double) $product_data_fromdb[0]['variation_purchase_price'],
                            'sale_price' => $product_data_fromdb[0]['sale_price'],
                            'purchase_price' => $product_data_fromdb[0]['purchase_price'],
                            'name' => $product_data_fromdb[0]['product_name_en'],
                            'variation_title' => $product_data_fromdb[0]['variation_title_en'],
                            'name_ar' => $product_data_fromdb[0]['product_name_ar'],
                            'variation_title_ar' => $product_data_fromdb[0]['variation_title_ar'],
                            'unit' => $unit,
                            'weight' => $product_data_fromdb[0]['weight'],
                            'brand' => $this->apis_models1->get_type_name_by_id('brand', $product_data_fromdb[0]['brand'], 'name'),
                            'category'=>$product_data_fromdb[0]['category'],
                            'sub_category'=>$product_data_fromdb[0]['sub_category'],
//                                'shipping'     => $shipping_value_in_crn,
                            'tax' => (double) $tax_value_in_crn,
                            'image' => $this->apis_models1->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one'),
                            'coupon_id' => $coupon_id,
                            'coupon_applied' => $coupon_applied,
                            'rowid' => $rowid,
                            'subtotal' => $calculated_price * $qty,
                            //added by sagar : supplier price and supplier in cart
                            'supplier' => $product_data_fromdb[0]['supplier'],
                            'supplier_price' =>$product_data_fromdb[0]['supplier_price']
                        );
                   
                        $rowd_id_array[$rowid] = $cart;
                        /*
                        $cart_data = array(
                            'user_id' => $user_id,
                            'product_id' => $product_id,
                            'variation_id' => $variation_id,
                            'product_type' => $product_data_fromdb[0]['product_type'],
                            'product_data' => json_encode($cart,JSON_UNESCAPED_UNICODE),
                            'product_name' => $title,
                            'qty' => (int) $qty,
                            'price' => (double) $rate,
                            'product_discount' => (double) $product_discount, // $product_data_fromdb[0]['discount'],
                            'product_discount_type' => $product_data_fromdb[0]['discount_type'],
                            'product_tax' => (double) $product_tax,
                            'product_tax_type' => $product_tax_type,
                            'product_option' => json_encode($option),
                            'product_image' => $this->apis_models1->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one'),
//                                'shipping_amount'            => (double) $product_data_fromdb[0]['shipping_cost'],
                            'calculated_price' => (double) $subtotal_payable_Amt, //$calculated_price,
                            'calculated_tax' => (double) $calculated_tax,
                            'calculated_discount' => (double) $total_discount,
//                                'calculated_shipping'        => (double) $total_shipping,
                            'coupon_code' => $coupon_code,
                            'coupon_id' => $coupon_id,
                            'coupon_specs' => $coupon_detail,
//                                'coupon_validity_date'       => $coupon_validity_date,
                            'calculated_coupon_discount' => (double) $calulated_discount_amount,
                            'final_amount' => ( (double) $subtotal_payable_Amt + (double) $calculated_tax),
                             //added by sagar : supplier price and supplier in cart
                            'supplier' => $product_data_fromdb[0]['supplier'],
                            'supplier_price' =>$product_data_fromdb[0]['supplier_price'],
                            'delivery_date' =>$deliveryDateInCart,
                            'timeslot' =>$TimeSlotInCart,
                        );
                        $cart_data['created_on'] = date('Y-m-d H:i:s');
                       $this->db->insert('cart', $cart_data);
                       $cart_id = $this->db->insert_id();
                        $cart_array[] = $cart_id;
                        */
                        //product price handling : END
                    } else {
//                        $err_msg = 'Product added in Cart list at position ' . $key . ' needs to be removed and added again.';
                        $this->errorMessage('please_remove_product_from_cart', $msg_data, $userLanguage,array(),$key);
                    }
                }
//              //minimun order amount check - start
                $db_min_order_amount  = $this->db->get_where('general_settings', array('type' => 'min_order_amount'))->row()->value;  
                if($subtotal_amount < $db_min_order_amount) { 
                    $subtotal_amount = get_converted_currency($subtotal_amount,$currency_code);
                    $min_order_amount = get_converted_currency($db_min_order_amount,$currency_code);
                    $this->errorMessage('minimum_order_amount', $msg_data, $userLanguage,array(),$min_order_amount);
                }
//              //minimun order amount check - end  

                //ORDER Creation starts here
                if ($payment_method == 'trolleyCredit') {
                    $payment_array_hardcoded[] = array(
                        'admin' => "",
                        'status' => 'paid',
                    );
                    $payment_details = '';
                } else {
                    $payment_details = '';
                    $payment_array_hardcoded[] = array(
                        'admin' => "",
                        'status' => 'pending',
                    );
                }
                //PAYINCASH | PAYINCARD | TROLLEY CRADIT  --HERE
              
                $payment_status_encoded = json_encode($payment_array_hardcoded);
                $delivery_status[] = array('admin' => '',
                    'status' => 'pending',
                    'comment' => '',
                    'delivery_time' => ''
                );
                $order_shipping_address['payment_type'] = $payment_method;

                //added by sagar : FOR BACKEND CONVERSION ON INVOICE - 27-08
                $currency_conversion = $this->db->get_where('general_settings', array('type' => 'currency_conversion'))->row()->value;
                $user_choice[] = array('currency_code' => $currency_code, 'language_code' => $userLanguage, 'currency_conversion' => $currency_conversion);
                $user_choice_encoded = json_encode($user_choice);
                //added by sagar : FOR BACKEND CONVERSION ON INVOICE - 27-08
                
                //FOR  FREE delivery - start 
                $delivery_charge_amount = 0;
                if ($delivery_type == 'homeDelivery') {
                    $db_free_delivery_amount  = $this->db->get_where('general_settings', array('type' => 'free_delivery_amount'))->row()->value;
                    if($subtotal_amount < $db_free_delivery_amount) { 
                        $delivery_charge = $this->apis_models1->getDeliveryCharge($city_id,$area_id);
                        $delivery_charge_amount = 0;
                        if (is_numeric($delivery_charge) && !empty($delivery_charge)) {
                            $delivery_charge_amount = $delivery_charge ;
                        }
                    }
                }
                $serviceTax = $this->getServiceCharge($payment_type,$userWalletType,$cardNumber);
                $tax_final = 0;
                if ($serviceTax > 0 && $serviceTax < 100) {
                    $tax_final = (double) $subtotal_amount * $serviceTax / 100;
                }
                
                $grand_total= (double)$subtotal_amount + (double)$delivery_charge_amount + (double)$tax_final ;
                $grand_total_in_usd_rounded = round($grand_total,3);
       
                //FOR  FREE delivery - start 
                //TROLLET CRADIT WALLET CHECK - START
                $wallet_balance =  $this->db->get_where('user',array('user_id'=>$user_id))->row()->wallet_balance;
                $grand_total_in_sdg = 0 ; 
                if ($payment_method == 'trolleyCredit') {
//                    $grand_total_in_sdg = get_converted_currency($grand_total,2);  //2 for SDG
                    $grand_total_in_sdg =  get_converted_currency($grand_total_in_usd_rounded, 2,$currency_conversion);
                    if($grand_total_in_sdg > $wallet_balance){
                        $this->errorMessage('insufficient_wallet_balance', $msg_data, $userLanguage);
                    }
                }
                //TROLLET CRADIT WALLET CHECK - END
            
                //STORE IN SALE
                $supplier_ids_imploded  = implode(',',$supplier_ids_array);
                
                $sales_data = array(
                    'buyer' => $user_id,
                    'product_details' => json_encode($rowd_id_array,JSON_UNESCAPED_UNICODE),
                    'shipping_address' => json_encode($order_shipping_address,JSON_UNESCAPED_UNICODE ),
                    'vat' => $tax_final,
                    'payment_type' => $payment_method,
                    'payment_status' => $payment_status_encoded,
                    'payment_details' => $payment_details,
                    'delivery_charge' => $delivery_charge_amount,
                    'grand_total' => $grand_total,
                    'invoice_amount' => $grand_total,
                    //coupon part 
                    'coupon_code' => $coupon_code,
                    'coupon_id' => $coupon_id,
                    'coupon_detail' => $coupon_detail,
                    'discount_amount' => $total_coupon_discount,
                    //coupon part 
                    'sale_datetime' => time(),
                    'store_master_id' => $store_master_id,
                    'delivery_type' => $delivery_type,
                    'delivery_status' => json_encode($delivery_status),
                    'loyalty_card_option' => $loyaltycard_option,
                    'credit_limit_option' => $credit_option,
                    'user_choice' => $user_choice_encoded,
                    //added for delivery report
                    'delivery_date_timeslot'=>$delivery_date_timeslot_encoded,
                    'timeslots_id'=>$timeslots_id,
                    //added for auto assign store flow
                    'city_id'=>$city_id,
                    'area_id'=>$area_id,
                    'supplier_ids'=>$supplier_ids_imploded,
                );
                $this->db->insert('sale', $sales_data);         
                $sale_id = $this->db->insert_id();
                $sales_data_code['sale_code'] = $sales_code = $this->crud_model->get_sale_code($sale_id, true, $store_master_id);
                $this->db->where('sale_id', $sale_id);
                $this->db->update('sale', $sales_data_code);
                $success_msg = "order_placed_successfully";
//              $success_msg = $sales_code;
                $order_no = $sales_code;
                $this->cart_update_after_checkout($user_id);

                //FOR EPayment Call :START
                if($payment_method == 'ePaymentCard'){
                    $uuid= "";
                    $grand_total_in_sdg =  get_converted_currency($grand_total_in_usd_rounded, 2,$currency_conversion);
                    $paymentRespone = $this->processTransaction($userCardId,$user_id,$cardPin,$grand_total_in_sdg,$uuid,$userLanguage,$sale_id);
                    $response_array = json_decode($paymentRespone,true);
                    $isPaymentDone = false;
                //Working on Epayment Repsonse 
                    if(is_array($response_array)){
                        $ebs_response_code = $response_array['success'];
                        if($ebs_response_code == 1){
                            $isPaymentDone = true;
                        }
                    }
                    //update sale entry
                    if($isPaymentDone){
                        $ePayment_array[] = array(
                            'admin' => "",
                            'status' => 'paid',
                        );
                        $updateEPaymentData = array(
                            'payment_status' => json_encode($ePayment_array),
                            'payment_details' =>$paymentRespone,
                            'payment_timestamp' => time()
                        );
                        $this->db->where('sale_id', $sale_id);
                        $this->db->update('sale', $updateEPaymentData);
                    }else{
                        $stock_decrease = false;
                        //sale update if failed-- here
                        $ePayment_array[] = array(
                            'admin' => "",
                            'status' => 'failed',
                        );
                        
                        //added by sagar -- START 10-06-2020
                        $Delivery_status[] = array('admin' => '',
                            'status' => 'cancelled',
                            'comment' => 'transaction failed',
                            'delivery_time' => date('Y-m-d H:i:s'),
                        );
                        //added by sagar -- END 10-06-2020
                        
                        $updateEPaymentData = array(
                            'payment_status' => json_encode($ePayment_array),
                            'payment_details' =>$paymentRespone,
                            'payment_timestamp' => time(),
                            //added by sagar 
                            'order_status' => 'cancelled',
                            'delivery_status' => json_encode($Delivery_status),
                        );
                        $this->db->where('sale_id', $sale_id);
                        $this->db->update('sale', $updateEPaymentData);
                        $success_msg = 'we_have_tried_creating_an_order_for_you_but_the_payment_was_not_successful';                   
                    }
                //Working on Epayment Repsonse 
                }
              
                //FOR ePayementWallet
                if($payment_method == 'ePaymentWallet'){
                    $uuid= "";
                    $grand_total_in_sdg =  get_converted_currency($grand_total_in_usd_rounded, 2,$currency_conversion);
                    $paymentRespone = $this->processTransactionForWallet($userCardId,$user_id,$cardPin,$grand_total_in_sdg,$uuid,$userLanguage,$sale_id);
                    $response_array = json_decode($paymentRespone,true);
                    $isPaymentDone = false;
                //Working on Epayment Repsonse 
                    if(is_array($response_array)){
                        $ebs_response_code = $response_array['success'];
                        if($ebs_response_code == 1){
                            $isPaymentDone = true;
                        }
                    }
                    //update sale entry
                    if($isPaymentDone){
                        $ePayment_array[] = array(
                            'admin' => "",
                            'status' => 'paid',
                        );
                        $updateEPaymentData = array(
                            'payment_status' => json_encode($ePayment_array),
                            'payment_details' =>$paymentRespone,
                            'payment_timestamp' => time()
                        );
                        $this->db->where('sale_id', $sale_id);
                        $this->db->update('sale', $updateEPaymentData);
                    }else{
                        $stock_decrease = false;
                        //sale update if failed-- here
                        $ePayment_array[] = array(
                            'admin' => "",
                            'status' => 'failed',
                        );
                        
                        //added by sagar -- START 10-06-2020
                        $Delivery_status[] = array('admin' => '',
                            'status' => 'cancelled',
                            'comment' => 'transaction failed',
                            'delivery_time' => date('Y-m-d H:i:s'),
                        );
                        //added by sagar -- END 10-06-2020
                        
                        $updateEPaymentData = array(
                            'payment_status' => json_encode($ePayment_array),
                            'payment_details' =>$paymentRespone,
                            'payment_timestamp' => time(),
                            //added by sagar 
                            'order_status' => 'cancelled',
                            'delivery_status' => json_encode($Delivery_status)
                        );
                        $this->db->where('sale_id', $sale_id);
                        $this->db->update('sale', $updateEPaymentData);
                        $success_msg = 'we_have_tried_creating_an_order_for_you_but_the_payment_was_not_successful';                   
                    }
                //Working on Epayment Repsonse 
                    
                }
                //FOR EPayment Call : END
                
                //FOR Trollet Credit -- START
                $new_wallet_balance = $wallet_balance;
                if($payment_method == 'trolleyCredit'){
                    $new_wallet_balance = $wallet_balance - $grand_total_in_sdg;
                    $new_wallet_balance = round($new_wallet_balance,2);
                    $walletData = array(
                        'user_id'=>$user_id,
                        'amount' => $grand_total_in_sdg,
                        'type'=>'debit',
                        'reason'=> 'paid for order #'.$sales_code ,
                        'date_time'=>date('Y-m-d H:i:s'),
                        'sale_id' =>$sale_id,
                        'wallet_balance'=>$new_wallet_balance,
                    );
                    $this->db->insert('wallet', $walletData);
                    $update_wallet_balance =  array(
                        'wallet_balance' =>$new_wallet_balance,
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('user', $update_wallet_balance);
                    
                    //UPDATE IN SALE FOR TROLLEY CREDIT - by sagar
                    $updateTrolleyCreditData = array(
                        'payment_timestamp' => time()
                    );
                    $this->db->where('sale_id', $sale_id);
                    $this->db->update('sale', $updateTrolleyCreditData);
                }
                //FOR Trollet Credit --END
                    
                $userPoints = 0;
                //To reduce Product Stock after order placed : START 
                if($stock_decrease){
                foreach ($products as $key => $val) {
                    $this->apis_models1->decrease_variant_quantity($val['product_id'], $val['qty'], $val['variation_id']);
                    $stock_Data = array(
                        'type' => 'destroy',
                        'product' => $val['product_id'],
                        'variation_id' => $val['variation_id'],
                        'quantity' => $val['qty'],
                        'reason_note' => 'Order Place with Order No. : ' . $sales_code,
                        'datetime' => time(),
                        'sale_id' => $sale_id
                    );
                    $this->apis_models1->insertData('stock', $stock_Data, 1);
                    }
                }
                //To reduce Product Stock after order placed : END 
                $responseData =  array(
                    'order_no' => $order_no, 
                    'userPoints' => $userPoints,
                    'wallet_balance' =>$new_wallet_balance
                );
                //change by sagar : for error handling 
                if($success_msg == 'order_placed_successfully'){
                    $verification_code = $this->generateRandomOTP();
                    $updateSale =  array(
                        'verification_code' => $verification_code,
                    );
                    $this->db->where('sale_id', $sale_id);
                    $this->db->update('sale', $updateSale);
                    
                    //SMS TO USER : ORDER PLACED
                    $this->messaging_model->sms_order_placed($order_no,$smsPhoneNumber,$verification_code);
                    //SMS TO USER : ORDER PLACED
                $this->successMessage($success_msg, $msg_data, $userLanguage,$responseData);
                }else{
                    $this->errorMessage($success_msg, $msg_data, $userLanguage);
//                    $this->errorMessage('we_have_tried_creating_an_order_for_you_but_the_payment_was_not_successful', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_add_product_in_cart', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    function editCart()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && ! empty($data)) {
            $_POST = $data;
        }
        $msg_data = array();
        $token_array = $this->read_header_token();
        $this->read_header();
        $payable_amt_final = 0;
        $tax_final = 0;
        $total_coupon_discount = 0;
        $coupon_applied = 'no';

        $userLanguage = 'ar';
        //user choice : START
        if (isset($_POST['userLanguage']) && ! empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //user choice : END

        $user_id = $token_array['uid'] ?? 0;
        if (empty($_POST['product_id'])) {
            $this->errorMessage('please_add_product_in_cart', $msg_data, $userLanguage);
            exit();
        }

        if (empty($_POST['qty']) || $_POST['qty'] <= 0) {
            $this->deleteCart($_POST);
            $this->errorMessage('product_quantity_must_be_greater_than_0', $msg_data, $userLanguage);
            exit();
        }
        //Store comma separted supplier ids for auto assign store 
        $supplier_ids_array = array();
        $subtotal_amount = 0;

        $product_id = $_POST['product_id'];
        $variation_id = $_POST['variation_id'];
        $product_data_fromdb = $this->apis_models1->getProductVariations($product_id, $variation_id, $userLanguage);

        if (is_array($product_data_fromdb) && isset($product_data_fromdb[0])) {
            $qty = $_POST['qty'];
            $title = $product_data_fromdb[0]['title'];
            //product price handling : START 
            if (empty($product_data_fromdb[0]['variation_stock']) || $product_data_fromdb[0]['variation_stock'] < $qty) {
                //$err_msg = $title . ' is OUT OF STOCK';
                $this->errorMessage('out_of_stock_product', $msg_data, $userLanguage, array(), $title);
            }
            $product_discount_type = $product_data_fromdb[0]['discount_type'];
            //User Type check value
            $unit = $product_data_fromdb[0]['unit'];
            $product_discount = (double) $product_data_fromdb[0]['discount'];
            $rate = $product_data_fromdb[0]['variation_price'];
            $rowid = md5($variation_id);
            $subtotal = $qty * $rate;

            //Attribute selection: start
            $option = array();
            if (! empty($_POST['attributes']) && is_array($_POST['attributes'])) {
                $attributes_array = $_POST['attributes'];
                foreach ($attributes_array as $attKey => $attVal) {
                    $attributename = 'choice_'.$attVal['products_options_values_id'];
                    $option[$attributename] = array(
                        'title'              => $attVal['products_options'],
                        'value'              => $attVal['name'],
                        'attribute_id'       => $attVal['products_options_id'],
                        'attribute_value_id' => $attVal['products_options_values_id'],
                    );
                }
            }
            //Attribute selection : end

            //calculations part start
            $calculated_price = (double) $rate * (int) $qty;
            $discount_per_piece = 0;
            $total_discount = 0;

            if ($product_discount_type == 'percent' && $product_discount > 0 && $product_discount < 100) {
                $total_discount = $calculated_price * $product_discount / 100;
                $discount_per_piece = (double) $rate * $product_discount / 100;
            } elseif ($product_discount_type == 'amount' && $product_discount > 0 && $product_discount < (double) $product_data_fromdb[0]['sale_price']) {
                $total_discount = $product_discount * $qty;
                $discount_per_piece = (double) $product_data_fromdb[0]['discount'];
            }

            $payable_amount_after_discount = $calculated_price = $rate - $discount_per_piece;

            //coupon PART
            $coupon_code = '';
            $coupon_id = 0;
            $coupon_detail = '';
            $discount_amount = '';
            $calculated_coupon_discount = 0;
            if (isset($_POST['coupon_code']) && ! empty($_POST['coupon_code'])) {
                $coupon_details = $this->check_coupon_used(trim($_POST['coupon_code']), $user_id,
                    $payable_amount_after_discount);
                if ($coupon_details) {
                    $coupon_applied = 'yes';
                    $final_discount = $coupon_details['final_discount'];
                    $coupon_detail_here = $coupon_details['coupon_details'];
                    $coupon_id = $coupon_details['coupon_id'];
                    $coupon_code = $coupon_details['coupon_code'];
                    $coupon_detail = json_encode($coupon_detail_here);
                    $discount_amount = $final_discount;
                    $payable_amount_after_discount -= $final_discount;
                    $calculated_coupon_discount = $final_discount;
                }
            }
            //coupon END

            //SET AS 0 as no product tax here direct service tax 
            $product_tax = 0;
            $product_tax_type = 'percent';
            $tax_value_in_crn = 0;
            $total_tax = 0;
            if ($product_tax_type == 'percent' && $product_tax > 0 && $product_tax < 100) {
                $total_tax = $payable_amount_after_discount * $product_tax / 100;
                $tax_value_in_crn = ((double) $product_tax * (double) $payable_amount_after_discount) / 100;
            } elseif ($product_tax_type == 'amount' && $product_tax > 0) {
                $total_tax = $product_tax * (int) $qty;
                $tax_value_in_crn = (double) $product_tax;
            }

            $subtotal_payable_Amt = $payable_amount_after_discount * $qty;
            $calulated_discount_amount = $calculated_coupon_discount * $qty;
            $calculated_tax = $total_tax * $qty;
            //calculations part end

            //Store Unique supplier ids for order : START
            if (! in_array($product_data_fromdb[0]['supplier'], $supplier_ids_array)) {
                array_push($supplier_ids_array, $product_data_fromdb[0]['supplier']);
            }
            //Store Unique supplier ids for order : END
            $calculated_price = round($calculated_price, 3);
            $cart = array(
                'product_id'         => $product_id,
                'variation_id'       => $variation_id,
                'product_type'       => $product_data_fromdb[0]['product_type'],
                'qty'                => (int) $qty,
                'option'             => json_encode($option),
                'price'              => (double) $calculated_price,
                'name'               => $product_data_fromdb[0]['product_name_en'],
                'variation_title'    => $product_data_fromdb[0]['variation_title_en'],
                'name_ar'            => $product_data_fromdb[0]['product_name_ar'],
                'variation_title_ar' => $product_data_fromdb[0]['variation_title_ar'],
                'unit'               => $unit,
                'weight'             => $product_data_fromdb[0]['weight'],
                'brand'              => $this->apis_models1->get_type_name_by_id('brand',
                    $product_data_fromdb[0]['brand'], 'name'),
                'category'           => $product_data_fromdb[0]['category'],
                'sub_category'       => $product_data_fromdb[0]['sub_category'],
                'tax'                => (double) $tax_value_in_crn,
                'image'              => $this->apis_models1->file_view('product', $product_id, '', '', 'thumb',
                    'src', 'multi', 'one'),
                'coupon_id'          => $coupon_id,
                'coupon_applied'     => $coupon_applied,
                'rowid'              => $rowid,
                'subtotal'           => $calculated_price * $qty,
                'supplier'           => $product_data_fromdb[0]['supplier'],
                'supplier_price'     => $product_data_fromdb[0]['supplier_price'],
            );

            $cart_data = array(
                'product_id'                 => $product_id,
                'variation_id'               => $variation_id,
                'product_type'               => $product_data_fromdb[0]['product_type'],
                'product_data'               => json_encode($cart, JSON_UNESCAPED_UNICODE),
                'product_name'               => $title,
                'qty'                        => (int) $qty,
                'price'                      => (double) $rate,
                'product_discount'           => (double) $product_discount,
                'product_discount_type'      => $product_data_fromdb[0]['discount_type'],
                'product_tax'                => (double) $product_tax,
                'product_tax_type'           => $product_tax_type,
                'product_option'             => json_encode($option),
                'product_image'              => $this->apis_models1->file_view('product', $product_id, '', '',
                    'thumb', 'src', 'multi', 'one'),
                'calculated_price'           => (double) $subtotal_payable_Amt,
                'calculated_tax'             => (double) $calculated_tax,
                'calculated_discount'        => (double) $total_discount,
                'coupon_code'                => $coupon_code,
                'coupon_id'                  => $coupon_id,
                'coupon_specs'               => $coupon_detail,
                'calculated_coupon_discount' => (double) $calulated_discount_amount,
                'final_amount'               => ((double) $subtotal_payable_Amt + (double) $calculated_tax),
                'supplier'                   => $product_data_fromdb[0]['supplier'],
                'supplier_price'             => $product_data_fromdb[0]['supplier_price'],
                'delivery_date'              => null,
                'timeslot'                   => null,
            );

            $cart_data['user_id'] = $user_id;
            $cart_data['cart_session_id'] = md5($this->device_id);

            if (isset($_POST['cart_id']) && ! empty($_POST['cart_id'])) {
                $cart_id = $_POST['cart_id'];
                $updateCondition = "cart_id = ".$this->db->escape($cart_id);
                $cartCondition = ' cart_id = '.$this->db->escape($cart_id);
                $cartCondition .= " AND user_id = ".$this->db->escape($user_id);

                if ($user_id == 0) {
                    $cartCondition .= " AND cart_session_id = ".$this->db->escape(md5($this->device_id));
                }
                $cartExist = $this->apis_models1->getData('cart_id', 'cart', $cartCondition);
                if(is_array($cartExist)){
                    $this->apis_models1->updateRecord('cart', $cart_data, $updateCondition);
                    $success_msg = 'product_updated_successfully';
                }else{
                    $this->errorMessage('data_not_found', $msg_data, $userLanguage, array());
                }
            } else {
                $cartCondition = ' product_id = '.$this->db->escape($product_id);
                $cartCondition .= ' AND variation_id = '.$this->db->escape($variation_id);
                $cartCondition .= " AND user_id = ".$this->db->escape($user_id);
                $cartCondition .= " AND cart_session_id = ".$this->db->escape(md5($this->device_id));
                $cartExist = $this->apis_models1->getData('cart_id', 'cart', $cartCondition);

                if(is_array($cartExist)){
                    $updateCondition = "cart_id = ".$this->db->escape($cartExist[0]['cart_id']);
                    $this->apis_models1->updateRecord('cart', $cart_data, $updateCondition);
                    $success_msg = 'product_updated_successfully';
                }else{
                    $cart_data['created_on'] = date('Y-m-d H:i:s');
                    $this->db->insert('cart', $cart_data);
                    $cart_id = $this->db->insert_id();
                    $cart_array[] = $cart_id;
                    $success_msg = 'product_added_successfully';
                }
            }

            $this->successMessage($success_msg, $msg_data, $userLanguage);
        } else {
            $this->errorMessage('no_such_product_found', $msg_data, $userLanguage, array());
        }
    }

    function deleteCart($post_data = array())
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data) && ! empty($data)) {
            $_POST = $data;
        }
        if (!empty($post_data) && empty($data)) {
            $_POST = $post_data;
        }
        $msg_data = array();
        $table_name = 'cart';
        $cart_id = 0;
        $userLanguage = 'ar';
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['userLanguage']) && ! empty($_POST['userLanguage'])) {
            $userLanguage = $_POST['userLanguage'];
        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        $this->read_header();
        $user_id = $token_array['uid'] ?? 0;
        if (isset($_POST['cart_id']) && ! empty($_POST['cart_id'])) {
            $cart_id = $_POST['cart_id'];
            $cartCondition = ' cart_id = '.$this->db->escape($cart_id);
            $cartCondition .= " AND user_id = ".$this->db->escape($user_id);

            if ($user_id == 0) {
                $cartCondition .= " AND cart_session_id = ".$this->db->escape(md5($this->device_id));
            }
            $cartExist = $this->apis_models1->getData('cart_id', 'cart', $cartCondition);
            
            if (! is_array($cartExist)) {
                $this->errorMessage('product_not_found', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('please_select_product_to_delete', $msg_data, $userLanguage);
        }
        
        $meter_condition = "user_id = ".$this->db->escape($user_id);
        $meter_condition .= " AND cart_session_id = ".$this->db->escape(md5($this->device_id));
        $meter_condition .= 'AND cart_id = '.$this->db->escape($cart_id);
        $delete_status = $this->apis_models1->delrecord($table_name, $meter_condition);
        if (! empty($delete_status)) {
            $this->successMessage('product_deleted_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
        }
    }

    private function getDeliveryCharge($user_id)
    {
        $delivery_charge = 0;
        $user_address = $this->db->get_where('user_address', array('user_id' => $user_id, 'default_address' => 'yes'))->row_array();

        if (!empty($user_address)) {
            $area_id = $user_address['area_id'];

            $delivery_charge = $this->db->get_where('area', array('area_id' => $area_id))->row()->delivery_charge;
        }

        return $delivery_charge;
    }

    function cartListing()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && ! empty($data)) {
            $_POST = $data;
        }

        $table_name = "cart";
        $select = "cart_id,cart_session_id,user_id,vendor_id,product_id,variation_id,sku_code,product_type,supplier,supplier_price, delivery_date,timeslot,cart_added_from,product_data,product_name,qty,price,purchase_price,product_discount,product_discount_type,product_tax,product_tax_type,product_option,product_image,shipping_amount,coupon_code,coupon_specs,coupon_validity_date,calculated_price,calculated_tax,calculated_discount,calculated_shipping,calculated_coupon_discount
,final_amount";
        $msg_data = array();

        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = 'ar';
        if (isset($_POST['userLanguage']) && ! empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        $this->read_header();
        $token_array = $this->read_header_token($userLanguage);


        $user_id = $token_array['uid'] ?? 0;
        if (! empty($user_id)) {
            $condition = 'user_id = '.$this->db->escape($user_id);
        } else {
            $condition = 'cart_session_id = '.$this->db->escape(md5($this->device_id));
            $condition .= ' AND user_id = 0';
        }
        $condition .= " AND i.sale_id = 0";
        $data = $this->apis_models1->getData($select, $table_name, $condition);

        if (is_array($data) && ! empty($data[0])) {
            $bag_total = 0;
            $product_discount = 0;
            $total_product_discount = 0;
            $coupon_discount = 0;
            $grand_total = 0;
            $total_product = 0;
            $resultData = [];

            foreach ($data as $key => $row) {
                $subtotal_amount = 0;
                $product_id = $row['product_id'];
                $variation_id = $row['variation_id'];
                $product_data_fromdb = $this->apis_models1->getProductVariations($product_id, $variation_id,
                    $userLanguage);
                if (is_array($product_data_fromdb) && isset($product_data_fromdb[0])) 
                {
                    $qty = $row['qty'];
                    $title = $product_data_fromdb[0]['title'];
                    $unit = "";
                    $product_discount_type = $product_data_fromdb[0]['discount_type'];
                    //User Type check value
                    $unit = $product_data_fromdb[0]['unit'];
                    $product_discount = (double) $product_data_fromdb[0]['discount'];
                    $rate = $product_data_fromdb[0]['variation_price'];
                    $variation_purchase_price = $product_data_fromdb[0]['variation_purchase_price'];

                    $rowid = md5($variation_id);
                    $subtotal = $qty * $rate;
                    
                    //calculations part start
                    $calculated_price = (double) $rate * (int) $qty;
                    $discount_per_piece = 0;
                    $total_discount = 0;

                    if ($product_discount_type == 'percent' && $product_discount > 0 && $product_discount < 100) {
                        // $total_discount = $calculated_price * $product_discount / 100;
                        $total_discount = (double)($rate - $variation_purchase_price) * (int) $qty;
                        $discount_per_piece = (double)($rate - $variation_purchase_price) ;
                    } elseif ($product_discount_type == 'amount' && $product_discount > 0 && $product_discount < (double) $product_data_fromdb[0]['sale_price']) {
                        $total_discount = $product_discount * $qty;
                        $discount_per_piece = (double) $product_data_fromdb[0]['discount'];
                    }
                    $payable_amount_after_discount = $calculated_price = $rate - $discount_per_piece;

                    //SET AS 0 as no product tax here direct service tax 
                    //$price = $product_data_fromdb[0]['variation_purchase_price'];
                    $product_tax = 0;
                    $product_tax_type = 'percent';
                    $total_tax = 0;
                    $payable_amt_final = 0;
                    $tax_final = 0;
                    if ($product_tax_type == 'percent' && $product_tax > 0 && $product_tax < 100) {
                        $total_tax = $payable_amount_after_discount * $product_tax / 100;
                        $tax_value_in_crn = ((double) $product_tax * (double) $payable_amount_after_discount) / 100;
                    } elseif ($product_tax_type == 'amount' && $product_tax > 0) {
                        $total_tax = $product_tax * (int) $qty;
                    }

                    $subtotal_payable_Amt = $payable_amount_after_discount * $qty;
                    $payable_amt_final += $subtotal_payable_Amt;
                    $calculated_tax = $total_tax * $qty;
                    $tax_final += $calculated_tax;
                    //calculations part end
                    
                    $calculated_price = round($calculated_price, 3);
                    $productData = array(
                        'product_id'         => $row['product_id'],
                        'variation_id'       => $row['variation_id'],
                        'product_type'       => $product_data_fromdb[0]['product_type'],
                        'name'               => $product_data_fromdb[0]['product_name_en'],
                        'variation_title'    => $product_data_fromdb[0]['variation_title_en'],
                        'name_ar'            => $product_data_fromdb[0]['product_name_ar'],
                        'unit'               => $unit,
                        'weight'             => $product_data_fromdb[0]['weight'],
                        'brand'              => $this->apis_models1->get_type_name_by_id('brand',
                            $product_data_fromdb[0]['brand'], 'name'),
                        'image'              => $this->apis_models1->file_view('product', $product_id, '', '', 'thumb',
                            'src', 'multi', 'one'),
                    );
                    $rowd_id_array[$rowid] = $productData;
                    $cart_data = array(
                        'product_id'                 => $product_id,
                        'variation_id'               => $variation_id,
                        'variation_stock'            => $product_data_fromdb[0]['variation_stock'],
                        'product_type'               => $product_data_fromdb[0]['product_type'],
                        'product_data'               => json_encode($productData, JSON_UNESCAPED_UNICODE),
                        'product_name'               => $title,
                        'qty'                        => (int) $qty,
                        'price'                      => (double) $rate,
                        'purchase_price'             => (double) $product_data_fromdb[0]['purchase_price'],
                        'product_discount'           => (double) $product_discount,
                        // $product_data_fromdb[0]['discount'],
                        'product_discount_type'      => $product_data_fromdb[0]['discount_type'],
                        'product_tax'                => (double) $product_tax,
                        'product_tax_type'           => $product_tax_type,
                        'product_option'             => $row['product_option']??[],
                        'product_image'              => $this->apis_models1->file_view('product', $product_id, '', '',
                            'thumb', 'src', 'multi', 'one'),
                        'calculated_price'           => (double) $subtotal_payable_Amt, //$calculated_price,
                        'calculated_tax'             => (double) $calculated_tax,
                        'calculated_discount'        => (double) $total_discount,
                        'final_amount'               => ((double) $subtotal_payable_Amt + (double) $calculated_tax),
                    );
                    
                    $resultData[$key] = [
                        'cart_id'                => $row['cart_id'],
                        'product_id'             => $cart_data['product_id'],
                        'product_name'           => $cart_data['product_name'],
                        'variation_id'           => $row['variation_id'],
                        'variation_stock'        => (double)$cart_data['variation_stock'],
                        'sale_price'             => get_converted_currency($cart_data['price'], $currency_code),
                        'purchase_price'         => get_converted_currency($cart_data['purchase_price'], $currency_code),
                        'qty'                    => $cart_data['qty'],
                        'product_discount'       => get_converted_currency($cart_data['calculated_discount'], $currency_code),
                        'product_discount_type'  => $cart_data['product_discount_type'],
                        'product_image'          => $cart_data['product_image'],
                        'final_amount'           => get_converted_currency($cart_data['final_amount']),
                        'products_details'       => json_decode($cart_data['product_data'] ?? []),
                    ];
                    $bag_total += ($cart_data['price'] * $cart_data['qty']);
                    $total_product_discount += $cart_data['calculated_discount'] ?? 0;
                    $grand_total += $cart_data['final_amount'] ?? 0;
                    ++$total_product;
                }
            }
            if (isset($_POST['coupon_code']) && !empty($_POST['coupon_code'])) {
                $coupon = $this->check_coupon_used(trim($_POST['coupon_code']), $user_id, $grand_total);
                $coupon_details = $coupon['coupon_details'][0];

                if ($coupon_details) {
                    $coupon_code = $coupon_details['code'];
                    $coupon_discount = $coupon['final_discount'];
                }
            }
            // $condition = "general_settings_id = 88";
            // $data = $this->apis_models1->getData('value', 'general_settings', $condition);
            // $delivery_free_above = $data[0]['value'];
            $general_settings_ids = array('88','108');
            $this->db->select('*');
            $this->db->from('general_settings');
            $this->db->where_in('general_settings_id', $general_settings_ids);
            $query = $this->db->get()->result_array();
            
            $delivery_free_above = $query[0]['value'];
            $min_order_amount = $query[1]['value'];
            
            if (($bag_total - $total_product_discount)>= $delivery_free_above) {
                $deliveryData['for_free_delivery_add'] = 0;
                $deliveryData['delivery_charges'] = 0;
            } else {
                $deliveryData['delivery_charges'] = 0;
                $deliveryData['for_free_delivery_add'] = ($delivery_free_above - ($bag_total - $total_product_discount));
            }
            $subtotal_amount = $grand_total;
            $grand_total += $deliveryData['delivery_charges'];
            $grand_total -= $coupon_discount;

            $responseData = array(
                'result'                => $resultData,
                'total_product'         => $total_product,
                'bag_total'             => get_converted_currency($bag_total, $currency_code),
                'subtotal_amount'       => get_converted_currency($subtotal_amount, $currency_code),
                'product_discount'      => get_converted_currency($total_product_discount, $currency_code),
                'coupon_discount'       => get_converted_currency($coupon_discount, $currency_code),
                'delivery_charges'      => get_converted_currency($deliveryData['delivery_charges'], $currency_code),
                'min_order_amount'      => get_converted_currency($min_order_amount, $currency_code),
                'delivery_free_above'   => get_converted_currency($delivery_free_above, $currency_code),
                'for_free_delivery_add' => get_converted_currency($deliveryData['for_free_delivery_add'], $currency_code),
                'grand_total'           => get_converted_currency($grand_total, $currency_code),
            );
            $this->successMessage('data_fetched_successfully', $msg_data, $userLanguage, $responseData);
        } else {
            $responseData = array(
                'data' => !empty($data) ? $data : array(),
            );
            $this->errorMessage('product_not_found', $msg_data, $userLanguage, $responseData);
        }
    }
    
    function checkout()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && ! empty($data)) {
            $_POST = $data;
        }
        $msg_data = array();
        $currency_code = "1";
        $userLanguage = 'ar';

        if (isset($_POST['userLanguage']) && ! empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        $token_array = $this->read_header_token($userLanguage);

        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];
            
            $table_name = "cart";
            $select = "cart_id,cart_session_id,user_id,vendor_id,product_id,variation_id,sku_code,product_type,supplier,supplier_price, delivery_date,timeslot,cart_added_from,product_data,product_name,qty,price,purchase_price,product_discount,product_discount_type,product_tax,product_tax_type,product_option,product_image,shipping_amount,coupon_code,coupon_specs,coupon_validity_date,calculated_price,calculated_tax,calculated_discount,calculated_shipping,calculated_coupon_discount
,final_amount";
            $condition = 'user_id = '.$this->db->escape($user_id);
            $condition .= " AND i.sale_id = 0";

            $data = $this->apis_models1->getData($select, $table_name, $condition);

            if (is_array($data) && ! empty($data[0])) {
                $bag_total = 0;
                $subtotal_amount = 0;
                $product_discount = 0;
                $calculated_coupon_discount = 0;
                $grand_total = 0;
                $total_product = 0;
                $resultData = [];
                foreach ($data as $key => $row) {
                    $product_id = $row['product_id'];
                    $variation_id = $row['variation_id'];
                    $product_data_fromdb = $this->apis_models1->getProductVariations($product_id, $variation_id,
                        $userLanguage);
                    if (is_array($product_data_fromdb) && isset($product_data_fromdb[0])) {
                        $qty = $row['qty'];
                        $title = $product_data_fromdb[0]['title'];
                        //product price handling : START 
                        if (empty($product_data_fromdb[0]['variation_stock']) || $product_data_fromdb[0]['variation_stock'] < $qty) {
                            //$err_msg = $title . ' is OUT OF STOCK';
                            $this->errorMessage('out_of_stock_product', $msg_data, $userLanguage, array(), $title);
                        }

                        $unit = "";
                        $product_discount_type = $product_data_fromdb[0]['discount_type'];
                        //User Type check value
                        $unit = $product_data_fromdb[0]['unit'];
                        $product_discount = (double) $product_data_fromdb[0]['discount'];
                        $rate = $product_data_fromdb[0]['variation_price'];
                        $variation_purchase_price = $product_data_fromdb[0]['variation_purchase_price'];

                        $rowid = md5($variation_id);
                        $subtotal = $qty * $rate;

                        //calculations part start
                        $calculated_price = (double) $rate * (int) $qty;
                        $discount_per_piece = 0;
                        $total_discount = 0;

                        if ($product_discount_type == 'percent' && $product_discount > 0 && $product_discount < 100) {
                            // $total_discount = $calculated_price * $product_discount / 100;
                            $total_discount = (double)($rate - $variation_purchase_price) * (int) $qty;
                            $discount_per_piece = (double)($rate - $variation_purchase_price) ;
                        } elseif ($product_discount_type == 'amount' && $product_discount > 0 && $product_discount < (double) $product_data_fromdb[0]['sale_price']) {
                            $total_discount = $product_discount * $qty;
                            $discount_per_piece = (double) $product_data_fromdb[0]['discount'];
                        }

                        $payable_amount_after_discount = $calculated_price = $rate - $discount_per_piece;
                        //SET AS 0 as no product tax here direct service tax 
                        $product_tax = 0;
                        $product_tax_type = 'percent';
                        $tax_value_in_crn = 0;
                        $total_tax = 0;
                        $calculated_tax = 0;
                        if ($product_tax_type == 'percent' && $product_tax > 0 && $product_tax < 100) {
                            $total_tax = $payable_amount_after_discount * $product_tax / 100;
                            $tax_value_in_crn = ((double) $product_tax * (double) $payable_amount_after_discount) / 100;
                        } elseif ($product_tax_type == 'amount' && $product_tax > 0) {
                            $total_tax = $product_tax * (int) $qty;
                            $tax_value_in_crn = (double) $product_tax;
                            $calculated_tax = $total_tax * $qty;
                        }

                        $subtotal_payable_Amt = $payable_amount_after_discount * $qty;
                        //calculations part end

                        $calculated_price = round($calculated_price, 3);
                        $productData = array(
                            'product_id'      => $row['product_id'],
                            'variation_id'    => $row['variation_id'],
                            'product_type'    => $product_data_fromdb[0]['product_type'],
                            'name'            => $product_data_fromdb[0]['product_name_en'],
                            'variation_title' => $product_data_fromdb[0]['variation_title_en'],
                            'name_ar'         => $product_data_fromdb[0]['product_name_ar'],
                            'unit'            => $unit,
                            'weight'          => $product_data_fromdb[0]['weight'],
                            'brand'           => $this->apis_models1->get_type_name_by_id('brand',
                                $product_data_fromdb[0]['brand'], 'name'),
                            'image'           => $this->apis_models1->file_view('product', $product_id, '', '', 'thumb',
                                'src', 'multi', 'one'),
                        );
                        $rowd_id_array[$rowid] = $productData;
                        $cart_data = array(
                            'product_id'            => $product_id,
                            'variation_id'          => $variation_id,
                            'product_type'          => $product_data_fromdb[0]['product_type'],
                            'product_data'          => json_encode($productData, JSON_UNESCAPED_UNICODE),
                            'product_name'          => $title,
                            'qty'                   => (int) $qty,
                            'price'                 => (double) $rate,
                            'purchase_price'             => (double) $product_data_fromdb[0]['purchase_price'],
                            'product_discount'      => (double) $product_discount,
                            // $product_data_fromdb[0]['discount'],
                            'product_discount_type' => $product_data_fromdb[0]['discount_type'],
                            'product_tax'           => (double) $product_tax,
                            'product_tax_type'      => $product_tax_type,
                            'product_option'        => $row['product_option'] ?? [],
                            'product_image'         => $this->apis_models1->file_view('product', $product_id, '', '',
                                'thumb', 'src', 'multi', 'one'),
                            'calculated_price'      => (double) $subtotal_payable_Amt, //$calculated_price,
                            'calculated_tax'        => (double) $calculated_tax,
                            'calculated_discount'   => (double) $total_discount,
                            'final_amount'          => ((double) $subtotal_payable_Amt + (double) $calculated_tax),
                        );

                        $resultData[$key] = [
                            'cart_id'               => $row['cart_id'],
                            'product_id'            => $cart_data['product_id'],
                            'product_name'          => $cart_data['product_name'],
                            'variation_id'          => $row['variation_id'],
                            'sale_price'            => number_format($cart_data['price'], 2, '.', ''),
                            'purchase_price'        => (double) $product_data_fromdb[0]['purchase_price'],
                            'qty'                   => $cart_data['qty'],
                            'product_discount'      => $cart_data['calculated_discount'],
                            'product_discount_type' => $cart_data['product_discount_type'],
                            'product_image'         => $cart_data['product_image'],
                            'final_amount'          => number_format($cart_data['final_amount'], 2, '.', ''),
                            'products_details'      => json_decode($cart_data['product_data'] ?? []),
                        ];
                        $bag_total += ($cart_data['price'] * $cart_data['qty']);
                        $total_product_discount += $cart_data['calculated_discount'] ?? 0;
                        $grand_total += $cart_data['final_amount'] ?? 0;
                        ++$total_product;
                    }
                }
                $calculated_coupon_discount = 0;
                if (isset($_POST['coupon_code']) && ! empty($_POST['coupon_code'])) {
                    $coupon_details = $this->check_coupon_used(trim($_POST['coupon_code']), $user_id,
                        $grand_total);
                    if ($coupon_details) {
                        $coupon_code = $coupon_details['coupon_code'];
                        $calculated_coupon_discount = $coupon_details['final_discount'];
                    }
                }
                $subtotal_amount = $grand_total;
                $grand_total -= $calculated_coupon_discount;
                $condition = "type = 'free_delivery_amount'";
                $data = $this->apis_models1->getData('value', 'general_settings', $condition);
                $delivery_free_above = $data[0]['value'];
                if (($bag_total - $total_product_discount) >= $delivery_free_above) {
                    $deliveryData['for_free_delivery_add'] = 0;
                    $deliveryData['delivery_charges'] = 0;
                } else {
                    $deliveryData['delivery_charges'] = $this->getDeliveryCharge($user_id);

                    if (!empty($_POST['area_id'])) {
                        $delivery_charge = $this->db->get_where('area', array('area_id' => $_POST['area_id']))->row()->delivery_charge;
                        $deliveryData['delivery_charges'] = get_converted_currency($delivery_charge, $currency_code);
                    }
                    $deliveryData['for_free_delivery_add'] = ($delivery_free_above - ($bag_total - $total_product_discount));
                }
                $grand_total += $deliveryData['delivery_charges'];
                
                //get next 2 days time slot data
                $total_days = 7;
                $time_slot_data_array = [];
                $days_count = 0;

                for ($day_number = 1; $day_number < $total_days && $days_count < 2; $day_number++) {
                    $timeslots = $this->apis_models1->getTimeSlotData($day_number);
                    if (is_array($timeslots)) {
                        $time_slot_data_array = array_merge($timeslots,$time_slot_data_array);
                        $days_count++;
                    }
                }
                $time_slot_data = array_values($time_slot_data_array);
                $responseData = array(
                    'result'                => array_values($resultData),
                    'total_product'         => $total_product,
                    'bag_total'             => get_converted_currency($bag_total, $currency_code),
                    'subtotal_amount'       => get_converted_currency($subtotal_amount, $currency_code),
                    'product_discount'      => get_converted_currency($total_product_discount, $currency_code),
                    'coupon_discount'       => get_converted_currency($calculated_coupon_discount, $currency_code),
                    'delivery_charges'      => get_converted_currency($deliveryData['delivery_charges'], $currency_code),
                    'for_free_delivery_add' => get_converted_currency($deliveryData['for_free_delivery_add'], $currency_code),
                    'grand_total'           => get_converted_currency($grand_total, $currency_code),
                    'payment_methods'       => PAYMENT_METHODS,
                    'time_slots'            => $time_slot_data ?? [],
                );
                $this->successMessage('data_fetched_successfully', $msg_data, $userLanguage, $responseData);
            } else {
                $data = array();
                $responseData = array(
                    'data' => $data,
                );
                $this->errorMessage('product_not_found', $msg_data, $userLanguage, $responseData);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }

    private function cart_update_after_checkout($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('cart');
    }

    public function getOrders() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $customers_id = null;
        $language_id = null;
        $limit = 10;
        $page = 0;
        $order_status = $order_time = 'all';
       
        $check_condition = " 1=1 ";
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        $address_1 = $address_2 = $landmark = "";
        
        //user choice : START
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
            
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        //user choice : END
        $token_array = $this->read_header_token($userLanguage);
        if ($this->read_header() && is_array($token_array)) {
            $user_id = $token_array['uid'];

            //order details
            if (isset($_POST['page_no'])) {
                $page = $_POST['page_no'];
            }
            if (isset($_POST['order_status'])) {
                $order_status = $_POST['order_status'];
            }
            if (isset($_POST['order_time'])) {
                $order_time = $_POST['order_time'];
            }
            $order = $this->apis_models1->getorderdata($user_id, $limit, $page, $order_status, $order_time);
            $orders_data = array();

            if (is_array($order)) {
                foreach ($order as $key => $value) {
                    $order_cancel_flag =  false;
                    $sale_id = $value['sale_id'];
                    //$product_id = 0;
                    $orders_data[$key]['sale_id'] = $value['sale_id'];
                    $orders_data[$key]['orders_id'] = $value['sale_code'];
                    $orders_data[$key]['customers_id'] = $value['buyer'];
                    //added by sagar : 14-08 START 
                    $orders_data[$key]['store_master_id'] = $store_master_id = $value['store_master_id'];
                    //added by sagar : 14-08 ENDs
                    $orders_data[$key]['date_purchased'] = date("Y-m-d H:i:s", $value['sale_datetime']);
                    $orders_data[$key]['last_modified'] = date("Y-m-d H:i:s", $value['sale_datetime']);
                    $orders_data[$key]['payment_type'] = $payment_type = $value['payment_type'];
                    $orders_data[$key]['delivery_type'] = $delivery_type = $value['delivery_type'];
                    $status = json_decode($value['delivery_status'], true);
                    $payment_status = json_decode($value['payment_status'], true);
                    $orders_data[$key]['payment_status'] = $payment_status[0]['status'];
                    $orders_data[$key]['delivery_status'] = $delivery_status = $status[0]['status'];
                    $product_Details = json_decode($value['product_details'], true);

                    $delivery_date_timeslots = json_decode($value['delivery_date_timeslot'], true);
                    $orders_data[$key]['delivery_date']  = $delivery_date_timeslots[0]['date'];
                    $orders_data[$key]['delivery_timeslot']  = $delivery_date_timeslots[0]['timeslot'];
                    $delivery_date_in_time = strtotime($delivery_date_timeslots[0]['date']);
                    $current_time =  strtotime(date('Y-m-d'));
                    
                    if( ($payment_type == 'payInCash' || $payment_type == 'payInCard' || $payment_type == 'trolleyCredit' || $payment_type ==  'ePaymentCard' || $payment_type ==  'ePaymentWallet')
                        &&  $delivery_status == 'pending' && $delivery_date_in_time >= $current_time){
                        $order_cancel_flag =  true;
                    }
                    if($value['order_status'] == 'cancelled'){
                        $order_cancel_flag =  false;
                        $orders_data[$key]['delivery_status'] = $value['order_status'];
                        $orders_data[$key]['payment_status'] = $value['order_status'];
                    }
                    $orders_data[$key]['order_cancel_flag'] = $order_cancel_flag;

                    $print_receipt_flag =  false;
                    if( ($payment_type == 'ePaymentCard' || $payment_type == 'ePaymentWallet') && $payment_status[0]['status']=='paid' ){
                        $print_receipt_flag = true;
                    }
                    $orders_data[$key]['print_receipt_flag'] = $print_receipt_flag;
                    $orders_data[$key]['verification_code'] = $value['verification_code'] ;
                    $rating = $this->db->get_where('reviews', array('user_id' => $user_id, 'order_id' => $sale_id))->row_array();
                    $orders_data[$key]['rating'] = (!empty($rating)) ? $rating['rating'] : '0';

                    //CONVERSION RATE FROM SALE ENTRY 
                    $user_choice = json_decode($value['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    //CONVERSION RATE FROM SALE ENTRY 
                    $product = array();
                    if (!empty($product_Details)) {
                        $product_key = 0;
                        $attr_arr = array();
                        $sub_total_final = $total_product_discount = $shipping_final = $tax_final = $coupon_discount_final = $final_amount = 0;
                        foreach ($product_Details as $key0 => $value1) {
                            $purchase_price = get_converted_currency($value1['price'], $currency_code,$sale_currency_conversion_rate);
                            $product[$product_key]['product_id'] = $value1['product_id'];
                            $product[$product_key]['variation_id'] = $value1['variation_id'];
                            $product[$product_key]['orders_products_id'] = $value1['id'];
                            $product[$product_key]['status'] = isset($value1['status']) ? $value1['status'] : 'accepted';
                            $product[$product_key]['product_type'] = $value1['product_type'];
                            $product[$product_key]['orders_id'] = $value['sale_id'];
                            $product[$product_key]['products_name'] = $value1['name'];
                            $product[$product_key]['variation_title'] = $value1['variation_title'];
                            $product[$product_key]['products_weight'] = $value1['weight'];
                            $product[$product_key]['sale_price'] = 
                                    !empty($value1['sale_price'])
                                        ? get_converted_currency($value1['sale_price'], $currency_code, $sale_currency_conversion_rate)
                                        : $purchase_price;

                            $product[$product_key]['purchase_price'] = 
                                    (!empty($value1['purchase_price']) && $value1['purchase_price'] != '0.00') 
                                        ? get_converted_currency($value1['purchase_price'], $currency_code, $sale_currency_conversion_rate)
                                        : $purchase_price;
                            $product[$product_key]['brand_name'] = $value1['brand'];
                            $product[$product_key]['products_price'] = get_converted_currency($value1['price'], $currency_code,$sale_currency_conversion_rate);

                            if ($value1['status'] != 'cancelled') {
                                $sub_total_final += ( $value1['price'] * $value1['qty'] );
                                $cancelled_product_price += $value1['purchase_price'];
                                // Further Process Will Be Done By Adnan - 2023-08-10 12:45PM
                            }
                            $product[$product_key]['products_tax'] = $value1['tax'];
                            $product[$product_key]['products_quantity'] = $value1['qty'];
                            $final_price = ( $value1['price'] * $value1['qty'] ) + $product[$product_key]['calculated_tax']; // + $product[ $product_key ]['calculated_shipping'];
                            $product[$product_key]['final_price'] = get_converted_currency($final_price, $currency_code,$sale_currency_conversion_rate);
                            $product[$product_key]['per_product_discount'] = ($product[$product_key]['sale_price'] - $product[$product_key]['purchase_price']) * $value1['qty'];
                            $product[$product_key]['image'] = $value1['image'];
                            //adding attributes of ordered data : start
                            $attr_arr = json_decode($value1['option'], true);
                            $attributes_array = array();
                            $i = 0;
                            if ($value1['product_type'] == 'variation') {
                                foreach ($attr_arr as $keyy => $vall) {
                                    $attributes_array[$i]['title'] = $vall['title'];
                                    $attributes_array[$i]['value'] = $vall['value'];
                                    //added by sagar : 14-08 START 
                                    $attributes_array[$i]['attribute_id'] = $vall['attribute_id'];
                                    $attributes_array[$i]['attribute_value_id'] = $vall['attribute_value_id'];
                                    //added by sagar : 14-08 END 
                                    $i ++;
                                }
                            }
                            $product[$product_key]['attributes'] = $attributes_array;
                            $total_product_discount += $product[$product_key]['per_product_discount'];
                            
                            //adding attributes of ordered data : End
                            $product_key += 1;
                                                        
                        }
                    }
                    
                    $cardDetails = array();
                    if($value['payment_type'] == 'ePayment'){
                        $card_details = json_decode($value['card_details'],true);
                        if(is_array($card_details) && !empty($card_details[0])){
                            $cardDetails = array(
                                'cardNo'=>$card_details[0]['PAN'],
                                'cardName'=>$card_details[0]['cName'],
                            );
                        }
                    }
                    
                    $orders_data[$key]['data'] = $product;
                    $orders_data[$key]['cardDetails'] = $cardDetails;
                    $delivery_street_address0 = json_decode($value['shipping_address']);
                  
                    if (isset($delivery_street_address0->phone_number) && !empty($delivery_street_address0->phone_number)) {
                        $phone_number = $delivery_street_address0->phone_number;
                    } else {
                        $phone_number = "";
                    }
                    if (isset($delivery_street_address0->email) && !empty($delivery_street_address0->email)) {
                        $email = $delivery_street_address0->email;
                    } else {
                        $email = "";
                    }
                    if (isset($delivery_street_address0->first_name) && !empty($delivery_street_address0->first_name)) {
                        $first_name = $delivery_street_address0->first_name;
                    } else {
                        $first_name = "";
                    }

                    if (isset($delivery_street_address0->second_name) && !empty($delivery_street_address0->second_name)) {
                        $second_name = $delivery_street_address0->second_name;
                    } else {
                        $second_name = "";
                    }
                    if (isset($delivery_street_address0->third_name) && !empty($delivery_street_address0->third_name)) {
                        $third_name = $delivery_street_address0->third_name;
                    } else {
                        $third_name = "";
                    }
                    if (isset($delivery_street_address0->fourth_name) && !empty($delivery_street_address0->fourth_name)) {
                        $fourth_name = $delivery_street_address0->fourth_name;
                    } else {
                        $fourth_name = "";
                    }
                    if (isset($delivery_street_address0->title) && !empty($delivery_street_address0->title)) {
                        $title = $delivery_street_address0->title;
                    } else {
                        $title = "";
                    }
                    if (isset($delivery_street_address0->address_1) && !empty($delivery_street_address0->address_1)) {
                        $address_1 = $delivery_street_address0->address_1;
                    }
                    if (isset($delivery_street_address0->address_2) && !empty($delivery_street_address0->address_2)) {
                        $address_2 = $delivery_street_address0->address_2;
                    }
                    if (isset($delivery_street_address0->landmark) && !empty($delivery_street_address0->landmark)) {
                        $landmark = $delivery_street_address0->landmark;
                    }
                    if (isset($delivery_street_address0->address_number) && !empty($delivery_street_address0->address_number)) {
                        $address_number = $delivery_street_address0->address_number;
                    } else {
                        $address_number = "";
                    }
                    if (isset($delivery_street_address0->city) && !empty($delivery_street_address0->city)) {
                        $city = $delivery_street_address0->city;
                    } else {
                        $city = "";
                    }
                    if (isset($delivery_street_address0->area) && !empty($delivery_street_address0->area)) {
                        $area = $delivery_street_address0->area;
                    } else {
                        $area = "";
                    }

                    if ($delivery_type == 'homeDelivery') {
                        $orders_data[$key]['phone_number'] = $phone_number;
                        $orders_data[$key]['email'] = $email;
                        $orders_data[$key]['first_name'] = $first_name;
                        $orders_data[$key]['second_name'] = $second_name;
                        $orders_data[$key]['third_name'] = $third_name;
                        $orders_data[$key]['fourth_name'] = $fourth_name;
                        $orders_data[$key]['address_1'] = $address_1;
                        $orders_data[$key]['address_2'] = $address_2;
                        $orders_data[$key]['landmark'] = $landmark;
                        $orders_data[$key]['title'] = $title;
                        $orders_data[$key]['number'] = $address_number;
                        $orders_data[$key]['city'] = $city;
                        $orders_data[$key]['area'] = $area;
                    }
                    $orders_data[$key]['coupons'] = json_decode($value['coupon_detail'], true);
//                                $orders_data[ $key ]['coupons']        = $value['coupon_code'];
                    $coupon_discount = get_converted_currency($value['discount_amount'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['coupon_amount'] = $coupon_discount;
                    $orders_data[$key]['sub_total_cost'] = get_converted_currency($sub_total_final, $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['products_tax'] = get_converted_currency($value['vat'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['order_price'] = get_converted_currency($value['grand_total'], $currency_code,$sale_currency_conversion_rate);
                    $orders_data[$key]['delivery_charge'] = get_converted_currency($value['delivery_charge'], $currency_code,$sale_currency_conversion_rate);
                    $total_savings = $total_product_discount + $coupon_discount;
                    $orders_data[$key]['total_savings'] = get_converted_currency($total_savings, $currency_code, $sale_currency_conversion_rate);
                }
                $responseData =  array(
                    'data'=>$orders_data,
                );
                $this->successMessage('returned_all_orders',array(), $userLanguage,$responseData);
            } else {
                $this->errorMessage('empty_order_list', $msg_data, $userLanguage,$orders_data);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function getCoupon()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $msg_data = array();
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        //user choice : START
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }

        if ($header) {
            $current_date = date('Y-m-d H:i:s');
            $select = "coupon_id, title, spec, discount_type, discount_value, start_date, till, code";
            $condition = "status = 'Active'";
            $condition .= " AND till >= '$current_date'";
            $coupon_details = $this->apis_models1->getData($select, 'coupon', $condition);
            $responseData = array(
                'data'   => $coupon_details,
            );
            if (is_array($coupon_details)) {
                $this->successMessage('data_fetched_successfully', $msg_data, $userLanguage,$responseData);
            } else {
                $this->errorMessage('coupons_not_available', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function validateCoupon() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $coupon_code = "";
        $msg_data = array();
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        //user choice : START
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        //user choice : END
        if ($header) {
            if (isset($_POST['coupon_code']) && !empty($_POST['coupon_code'])) {
                $coupon_code = $_POST['coupon_code'];
                $coupon_details = $this->apis_models1->getCoupondata($coupon_code);
                if (is_array($coupon_details)) {
                    $this->successMessage('coupon_is_applied', $msg_data, $userLanguage,$coupon_details);
                } else {
                    $this->errorMessage('invalid_coupon_code', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_coupon_code', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    private function check_coupon_used($coupon, $customers_id, $total) {
        if (!empty($coupon)) {
            $data = $this->db->query('Select * from coupon where code =' . $this->db->escape(trim($coupon)) . ' And start_date <=' . $this->db->escape(trim(date('Y-m-d H:i:s'))) . ' And till >' . $this->db->escape(trim(date('Y-m-d H:i:s'))));

            if ($data->num_rows() == 1) {
                $data = $data->result_array();
                $discount_type = $data[0]['discount_type'];
                $discount_value = $data[0]['discount_value'];
                $sales_data = $this->db->query('Select * from sale where (coupon_code =' . $this->db->escape(trim($coupon)) . ' OR coupon_id = ' . $this->db->escape(trim($data[0]['coupon_id'])) . ') And payment_status LIKE  \'%"status":"paid"%\' And buyer = ' . $this->db->escape($customers_id));
                if ($sales_data->num_rows() > 0) {
                    return false;
                }

                $discount = 0;
                $grand = $total;

                $final_discount = 0;
                if ($discount_type == 'percent') {
                    $final_discount = ($grand * $discount_value / 100);
                } elseif ($discount_type == 'amount') {
                    $final_discount = $discount_value;
                }
                if ($final_discount < $grand && $final_discount != 0) {
                    return array(
                        'final_discount' => $final_discount,
                        'coupon_details' => $data,
                        'coupon_id' => $data[0]['coupon_id'],
                        'coupon_code' => $coupon,
                    );
                } else {
                    return false;
                    exit;
                }
            } else {
                return false;
                exit;
            }
        } else {
            return false;
            exit;
        }
    }

    function reorderProduct() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        $msg_data= array();
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
            if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
                $currency_code = $_POST['currency_code'];
            }
        $token_array = $this->read_header_token($userLanguage);
         
        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
         
            if (isset($_POST['orders_id']) && !empty($_POST['orders_id'])) {
                $orders_id = $_POST['orders_id'];
                $orderData = $this->db->get_where('sale', array('sale_code' => $orders_id))->result_array();
                if (is_array($orderData) && !empty($orderData[0])) {

                    $product_details = json_decode($orderData[0]['product_details'], true);
                    $variation_id = "";
                    foreach ($product_details as $key => $val) {
                        $variation_id .= $val['variation_id'] . ',';
                    }
                    $variation_id = trim($variation_id, ',');

                    $data = $this->apis_models1->getReorderCartProducts($variation_id);

                    //added by sagar : start 2-08
                    $product_id_In = ' product_id IN (';
                    $product_id_vIn = ' v.product_id IN (';
                    $attr_mapp_data = $variation_attribute_values = array();
                    if (is_array($data[0]) && $data[1] > 0) {
                        foreach ($data[0] as $key => $value) {
                            $product_id_In .= $value['product_id'] . ',';
                            $product_id_vIn .= $value['product_id'] . ',';
                            // $final_array[$key]['product_code']=$value['product_code'];
                        }
                        $product_id_In = rtrim($product_id_In, ',');
                        $product_id_vIn = rtrim($product_id_vIn, ',');
                        $product_id_In .= ' )';
                        $product_id_vIn .= ' )';
                        $attr_mapp_data = $this->apis_models1->get_attmapp_data(0, $product_id_In);
                        $variation_attribute_values = $this->apis_models1->getVariationAttributeMapping(0, 0, $product_id_vIn);
                    }
                    //added by sagar : end 2-08

                    foreach ($data[0] as $key => $value) {
                        $key = $value['variation_id'];
                        $final_array[$key]['products_id'] = $value['product_id'];
                        $final_array[$key]['product_code'] = $value['product_code'];
                        $product_id = $value['product_id'];
                        $variation_id = $value['variation_id'];
                        $product_type = $value['product_type'];
                        // attributes added by sagar : start 2-08
                        $variation_count = $this->apis_models1->get_variation_stocks_maxcount($value['product_id']);

                        $final_array[$key]['products_quantity'] = $variation_count;
                        if ($product_type == "variation") {
                            //when product type is variation -> color ,size, length (multiple attribute)
                            $ATTR_CONDITION = " attribute_id in (";
                            $default_position = array_search($product_id, array_column($attr_mapp_data, 'product_id'));
                            if (isset($default_position) && $default_position >= 0) {
                                $ATTR_CONDITION .= $attr_mapp_data[$default_position]['group_attribute_id'];
                            }
                            $ATTR_CONDITION .= " ) ";
                            $attr_data = $this->apis_models1->getData('*', 'attribute', $ATTR_CONDITION);
                            $attr_id_arr = array();
                            $attr_name_arr = array();
                            if (is_array($attr_data)) {
                                foreach ($attr_data as $ad) {
                                    $attr_id_arr[] = $ad['attribute_id'];
                                    $attr_name_arr[] = $ad['attribute_name'];
                                }
                            }
                            //loop $attr_mapp_data here and form the condition for firing into attributes_values table
                            $ATTR_value_CONDITION = " attributevalue_id in (";
                            if (isset($default_position) && $default_position >= 0) {
                                $ATTR_value_CONDITION .= $attr_mapp_data[$default_position]['group_attributevalue_id'];
                            }
                            $ATTR_value_CONDITION = rtrim($ATTR_value_CONDITION, ',');
                            $ATTR_value_CONDITION .= " ) ";
                            $attr_value_data = $this->apis_models1->getData('*', 'attributevalue', $ATTR_value_CONDITION);
                            //fire the query and fetch the data from both master tables
                            $main_atttribute = array();
                            $var_main_key = 0;
                            foreach ($attr_id_arr as $key0 => $val0) {
                                $main_atttribute[$var_main_key] = array(
                                    "option" => array(
                                        "id" => $val0,
                                        "name" => $attr_name_arr[$key0]
                                    ),
                                );
                                foreach ($attr_value_data as $key00 => $val00) {
                                    if ($val0 == $val00['attribute_id']) {
                                        $main_atttribute[$var_main_key]['values'][] = array(
                                            'id' => $val00['attributevalue_id'],
                                            'value' => $val00['value'],
                                            'attribuite_id' => $val00['attribute_id'],
                                            'rgb' => $val00['rgb'],
                                            'is_color' => $val00['is_color'],
                                        );
                                    }
                                }
                                $var_main_key += 1;
                            }
                            $final_array[$key]['attributes'] = $main_atttribute;
                            //loop throgh data u got from attributes tables and by using the attribute id,use nested for looop to check for that attribute id in the data recd from attributes_vale table
                        } else {
                            $final_array[$key]['attributes'] = array();
                        }

                        //fethcing the all possible variations of the product
                        $is_any_default = '';
                        $variation_attribute_values_product = array();

                        foreach ($variation_attribute_values as $varKey => $varValue) {

                            if ($varValue['variation_id'] == $variation_id) {
                                if ($varValue['is_default'] == 'yes') {
                                    $is_any_default = $varKey;
                                }
                                //calculation discount
                                if (isset($varValue['discount']) && !empty($varValue['discount']) && $varValue['discount'] > 0) {
                                    $var_discount_amount = $this->apis_models1->get_discount_amount($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                                    $var_discount_price = $this->apis_models1->get_product_price($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                                    $variation_attribute_values[$varKey]['discount_amount'] = get_converted_currency($var_discount_amount, $currency_code);
                                    $variation_attribute_values[$varKey]['variation_discount_price'] = get_converted_currency($var_discount_price, $currency_code);
                                    $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                                }

                                $variation_attribute_values[$varKey]['sale_price'] = get_converted_currency($varValue['sale_price'], $currency_code);
                                $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                                $variation_attribute_values[$varKey]['b2b_sale_price'] = get_converted_currency($varValue['b2b_sale_price'], $currency_code);
                                $variation_attribute_values[$varKey]['b2b_variation_price'] = get_converted_currency($varValue['b2b_variation_price'], $currency_code);
                                $variation_attribute_values_product[] = $variation_attribute_values[$varKey];
                            }
                        }

                        //$variation_attribute_values_product;
                        $final_array[$key]['variations'] = $variation_attribute_values_product;
                        // attributes added by sagar : End 2-08
                        $image_src = $this->apis_models1->file_view('product', $value['product_id'], '', '', 'no', 'src', 'multi', 'all');
//                        $final_array[$key]['products_image'] = $image_src[0];
                        $final_array[$key]['products_image'] = $this->apis_models1->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');
                        $multi_image_array = array();
                        foreach ($image_src as $imagekey => $imgvalue) {
                            $multi_image_array[]['image'] = $imgvalue.'?d='.refreshedImage();
                        }
                        $final_array[$key]['images'] = $multi_image_array;
                        $final_array[$key]['products_weight_unit'] = $value['unit'];
                        //Title and Description On the basis of User language choice : START
                        if ($userLanguage == 'en') {
                            $final_array[$key]['products_name'] = $value['title'];
                            $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description']));
                        } else if ($userLanguage == 'ar') {
                            $final_array[$key]['products_name'] = $value['title_ar'];
                            $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description_ar']));
                        }
                        //Title and Description On the basis of User language choice : START$final_array[$key]['products_viewed'] = $value['number_of_view'];
                        $final_array[$key]['product_weight'] = $value['weight'];
                        $final_array[$key]['product_type'] = $value['product_type'];
                        $final_array[$key]['categories_id'] = $value['category'];
                        $final_array[$key]['categories_name'] = $value['category_name'];
                        $final_array[$key]['sub_category_id'] = $value['sub_category'];
                        $final_array[$key]['sub_category_name'] = $value['sub_category_name'];
                        $final_array[$key]['brand_id'] = $value['brand'];
                        $final_array[$key]['brand_name'] = $value['brand_name'];
                        $final_array[$key]['food_type'] = $value['food_type'];
                        //added by sagar : START ON 29-07
                        $final_array[$key]['is_offer'] = $value['is_offer'];
                        $final_array[$key]['is_galaxy_choice'] = $value['is_galaxy_choice'];
                        //added by sagar : END ON 29-07
                        $final_array[$key]['b2c_unit'] = $value['unit'];
                        $final_array[$key]['unit_link'] = $value['unit_link'];
                        $final_array[$key]['b2c_discount'] = $value['discount'];
                        $final_array[$key]['discount_type'] = $value['discount_type'];
                        //added by sagar : start -- additional field of product data
                        $additional_fields = json_decode(( $value['additional_fields']), true);
                        $name_1 = json_decode($additional_fields['name'], true);
                        $value_1 = json_decode($additional_fields['value'], true);
                        $main_additional_field = array();
                        if (is_array($name_1)) {
                            foreach ($name_1 as $add_field_key => $add_field_value) {
                                $nameVal = $add_field_value;
                                $main_additional_field[$add_field_key]['name'] = $nameVal;
                                if (isset($value_1[$add_field_key])) {
                                    $valueVal = $value_1[$add_field_key];
                                    $main_additional_field[$add_field_key]['value'] = $valueVal;
                                }
                            }
                        }
                        $final_array[$key]['additional_fields'] = $main_additional_field;
                        //added by sagar : end --additional field of product data
                    }
                    $responseData = array(
                         'data' => $final_array
                    );
                    $this->successMessage('products_for_reorder', $msg_data, $userLanguage,$responseData);
                } else {
                    $this->errorMessage('no_such_order_id_found', $msg_data, $userLanguage);
                }
            } else {
                 $this->errorMessage('provide_order_id_to_reorder', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    function updateCartProduct() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $currency_code = DEFAULT_CURRENCY;
        $user_id = "";
        $final_array = array();
        $userLanguage = 'ar';
        $msg_data= array();
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
            
        }
        //User choice Currency_code and userLanguage  : END
        
        if ($header) {
            
            $variation_ids = implode(',', $_POST['variation']);
            $data = $this->apis_models1->getReorderCartProducts($variation_ids);

            $product_id_In = ' product_id IN (';
            $product_id_vIn = ' v.product_id IN (';
            $attr_mapp_data = $variation_attribute_values = array();
            if (is_array($data[0]) && $data[1] > 0) {
                foreach ($data[0] as $key => $value) {
                    $product_id_In .= $value['product_id'] . ',';
                    $product_id_vIn .= $value['product_id'] . ',';
                    // $final_array[$key]['product_code']=$value['product_code'];
                }
                $product_id_In = rtrim($product_id_In, ',');
                $product_id_vIn = rtrim($product_id_vIn, ',');
                $product_id_In .= ' )';
                $product_id_vIn .= ' )';
                $attr_mapp_data = $this->apis_models1->get_attmapp_data(0, $product_id_In);
                $variation_attribute_values = $this->apis_models1->getVariationAttributeMapping(0, 0, $product_id_vIn);
            }
            foreach ($data[0] as $key => $value) {
                $key = $value['variation_id'];
                $final_array[$key]['products_id'] = $value['product_id'];
                $final_array[$key]['product_code'] = $value['product_code'];
                $product_id = $value['product_id'];
                $variation_id = $value['variation_id'];
                $product_type = $value['product_type'];
                // attributes added by sagar : start 2-08
                $variation_count = $this->apis_models1->get_variation_stocks_maxcount($value['product_id']);

                $final_array[(int) $key]['products_quantity'] = $variation_count;
                if ($product_type == "variation") {
                    //when product type is variation -> color ,size, length (multiple attribute)
                    $ATTR_CONDITION = " attribute_id in (";
                    $default_position = array_search($product_id, array_column($attr_mapp_data, 'product_id'));
                    if (isset($default_position) && $default_position >= 0) {
                        $ATTR_CONDITION .= $attr_mapp_data[$default_position]['group_attribute_id'];
                    }
                    $ATTR_CONDITION .= " ) ";
                    $attr_data = $this->apis_models1->getData('*', 'attribute', $ATTR_CONDITION);
                    $attr_id_arr = array();
                    $attr_name_arr = array();
                    if (is_array($attr_data)) {
                        foreach ($attr_data as $ad) {
                            $attr_id_arr[] = $ad['attribute_id'];
                            $attr_name_arr[] = $ad['attribute_name'];
                        }
                    }
                    //loop $attr_mapp_data here and form the condition for firing into attributes_values table
                    $ATTR_value_CONDITION = " attributevalue_id in (";
                    if (isset($default_position) && $default_position >= 0) {
                        $ATTR_value_CONDITION .= $attr_mapp_data[$default_position]['group_attributevalue_id'];
                    }
                    $ATTR_value_CONDITION = rtrim($ATTR_value_CONDITION, ',');
                    $ATTR_value_CONDITION .= " ) ";
                    $attr_value_data = $this->apis_models1->getData('*', 'attributevalue', $ATTR_value_CONDITION);
                    //fire the query and fetch the data from both master tables
                    $main_atttribute = array();
                    $var_main_key = 0;
                    foreach ($attr_id_arr as $key0 => $val0) {
                        $main_atttribute[$var_main_key] = array(
                            "option" => array(
                                "id" => $val0,
                                "name" => $attr_name_arr[$key0]
                            ),
                        );
                        foreach ($attr_value_data as $key00 => $val00) {
                            if ($val0 == $val00['attribute_id']) {
                                $main_atttribute[$var_main_key]['values'][] = array(
                                    'id' => $val00['attributevalue_id'],
                                    'value' => $val00['value'],
                                    'attribuite_id' => $val00['attribute_id'],
                                    'rgb' => $val00['rgb'],
                                    'is_color' => $val00['is_color'],
                                );
                            }
                        }
                        $var_main_key += 1;
                    }
                    $final_array[$key]['attributes'] = $main_atttribute;
                    //loop throgh data u got from attributes tables and by using the attribute id,use nested for looop to check for that attribute id in the data recd from attributes_vale table
                } else {
                    $final_array[$key]['attributes'] = array();
                }

                //fethcing the all possible variations of the product
                $is_any_default = '';
                $variation_attribute_values_product = array();

                foreach ($variation_attribute_values as $varKey => $varValue) {

                    if ($varValue['variation_id'] == $variation_id) {
                        if ($varValue['is_default'] == 'yes') {
                            $is_any_default = $varKey;
                        }
                        //calculation discount
                        if (isset($varValue['discount']) && !empty($varValue['discount']) && $varValue['discount'] > 0) {
                            $var_discount_amount = $this->apis_models1->get_discount_amount($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                            $var_discount_price = $this->apis_models1->get_product_price($varValue['product_id'], $varValue['variation_price'], $varValue['discount'], $varValue['discount_type']);
                            $variation_attribute_values[$varKey]['discount_amount'] = get_converted_currency($var_discount_amount, $currency_code);
                            $variation_attribute_values[$varKey]['variation_discount_price'] = get_converted_currency($var_discount_price, $currency_code);
                            $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                        }
                       
                        $variation_attribute_values[$varKey]['sale_price'] = get_converted_currency($varValue['sale_price'], $currency_code);
                        $variation_attribute_values[$varKey]['variation_price'] = get_converted_currency($varValue['variation_price'], $currency_code);
                        $variation_attribute_values_product[] = $variation_attribute_values[$varKey];
                    }
                }

                //$variation_attribute_values_product;
                $final_array[$key]['variations'] = $variation_attribute_values_product;
                // attributes added by sagar : End 2-08
                $image_src = $this->apis_models1->file_view('product', $value['product_id'], '', '', 'no', 'src', 'multi', 'all');
//               $final_array[$key]['products_image'] = $image_src[0];
                $final_array[$key]['products_image'] = $this->apis_models1->file_view('product', $product_id, '', '', 'thumb', 'src', 'multi', 'one');
                $multi_image_array = array();
                foreach ($image_src as $imagekey => $imgvalue) {
                    $multi_image_array[]['image'] = $imgvalue.'?d='.refreshedImage();
                }
                $final_array[$key]['images'] = $multi_image_array;
                $final_array[$key]['products_weight_unit'] = $value['unit'];
                //Title and Description On the basis of User language choice : START
                if ($userLanguage == 'en') {
                    $final_array[$key]['products_name'] = $value['title'];
                    $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description']));
                } else if ($userLanguage == 'ar') {
                    $final_array[$key]['products_name'] = $value['title_ar'];
                    $final_array[$key]['products_description'] = preg_replace('/<!--(.*)-->/Uis', '', stripslashes($value['description_ar']));
                }
                //Title and Description On the basis of User language choice : START
                $final_array[$key]['product_weight'] = $value['weight'];
                $final_array[$key]['product_type'] = $value['product_type'];
                $final_array[$key]['categories_id'] = $value['category'];
                $final_array[$key]['categories_name'] = $value['category_name'];
                $final_array[$key]['sub_category_id'] = $value['sub_category'];
                $final_array[$key]['sub_category_name'] = $value['sub_category_name'];
                $final_array[$key]['brand_id'] = $value['brand'];
                $final_array[$key]['brand_name'] = $value['brand_name'];
                $final_array[$key]['food_type'] = $value['food_type'];
                //added by sagar : START ON 29-07
                $final_array[$key]['is_offer'] = $value['is_offer'];
                $final_array[$key]['is_galaxy_choice'] = $value['is_galaxy_choice'];
                //added by sagar : END ON 29-07
                $final_array[$key]['b2c_unit'] = $value['unit'];
                $final_array[$key]['unit_link'] = $value['unit_link'];
                $final_array[$key]['b2c_discount'] = $value['discount'];
                $final_array[$key]['discount_type'] = $value['discount_type'];
                //added by sagar : start -- additional field of product data
                $additional_fields = json_decode(( $value['additional_fields']), true);
                $name_1 = json_decode($additional_fields['name'], true);
                $value_1 = json_decode($additional_fields['value'], true);
                $main_additional_field = array();
                if (is_array($name_1)) {
                    foreach ($name_1 as $add_field_key => $add_field_value) {
                        $nameVal = $add_field_value;
                        $main_additional_field[$add_field_key]['name'] = $nameVal;
                        if (isset($value_1[$add_field_key])) {
                            $valueVal = $value_1[$add_field_key];
                            $main_additional_field[$add_field_key]['value'] = $valueVal;
                        }
                    }
                }
                $final_array[$key]['additional_fields'] = $main_additional_field;
                //added by sagar : end --additional field of product data
            }
            
            $responseData = array(
                'data' => $final_array,
            );
            $this->successMessage('cart_update_successfully', $msg_data, $userLanguage,$responseData);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    
    private function processTransaction($user_card_id=0,$user_id=0,$card_pin='',$amount= 0,$uuid='',$userLanguage="ar",$sale_id=0) {
                $language_id = "002";
                $msg_data = array();
                $mobile_no = $description = $cipherText = $cardNumber = '';
                $txnAmount = $cashbackAmount = 0;
                $isPWCB = false;
                $bank_name = 'Central Bank of Sudan';
                $txnType = 'Purchase';
                $service_name = 'PURCHASE';
                $tran_fee = '0.20';
                $stan_id = '11';
                $reference_number = '';
                $response_message ='';
                $response_code = '000';
                $tranCurrencyCode = DEFAULT_CURRENCY_NAME;
                $tranDateTime = date('YmdHis');
                $ebs_response = '';

                $check_condition = " 1=1 And status='Active' ";
               
            if(is_numeric($user_card_id) && $user_card_id>0  && $amount > 0){
                $check_condition .= ' AND user_id = '.$this->db->escape($user_id);
                $check_condition .= ' AND user_card_id = '.$this->db->escape($user_card_id);
                
//                $cardDataArray = $this->apis_models1->getCardValidity($check_condition,'user_card_id');
                $cardDataArray = $this->apis_models1->getData( 'user_card_id,card_name,card_number,card_validity', 'user_card', $check_condition );
                if(!is_array($cardDataArray)){
                    $this->errorMessage('no_such_card_found', $msg_data, $userLanguage);
                }
               
                $cardValidity = $cardDataArray[0]['card_validity'];  
                $user_cardNumber = $cardDataArray[0]['card_number'];
                $user_cardName = $cardDataArray[0]['card_name'];
                $cardNumber = base64_decode($user_cardNumber);
                $formattedCardNumber = maskCardNumber($cardNumber);
                //Storing user card info 
                $card_details[] = array(
                    'PAN'=>$formattedCardNumber,
                    'cName'=>$user_cardName,
                );
                $updateUserData = array(
                    'card_details'=>json_encode($card_details),
                );
                if(!empty($sale_id )){
                    $this->db->where('sale_id', $sale_id);
                    $this->db->update('sale', $updateUserData);
                }
                
                $deviceID = $stan_id = $uuid;
                $cipherText = $card_pin;
                $txnAmount = $amount;
                
                $stan_id = 1;
                //From here POST it to MAAK bank and this integration part is still pending : start
                //$ebs_response = $this->mypcotmaak->makePurchaseCall($stan_id,$tranDateTime,$tranCurrencyCode,$cardNumber,$cipherText,$cardValidity,$txnAmount);  
                //NEW VALUES  PASSED --->
                $discount = 0;
                $ebs_response = $this->mypcotmaak->makePurchaseCall($uuid,$cardNumber,$cipherText,$cardValidity,$txnAmount,$discount);  
                
                //From here POST it to MAAK bank :: end
                $response_array = json_decode($ebs_response,TRUE);
                
                //if reponse is proper as per the EBS shared format
                 //if reponse is proper as per the EBS shared format
                if(isset($response_array) && is_array($response_array) && isset($response_array['responseCode'])){
                    if(isset($response_array['PAN']) && !empty($response_array['PAN'])){
                        $response_array['PAN'] = $formattedCardNumber;
                        $ebs_response = json_encode($response_array);
                    }
                }
                    return $ebs_response;
                
            }else{
                return $ebs_response;
            }    
    }
    //added by ritesh for firing transaction to maak backend :: end

    //added by ritesh for processing card data : start
        function processAddCards() {
            $data = json_decode( file_get_contents( 'php://input' ), true );
            if ( isset( $data ) && ! empty( $data ) ) {
                    $_POST = $data;
            }
            
            $language_id = "002";
            $msg_data = array();
            $type = 'paymentCard';
            $table_name = 'user_card';
            $randomNumber = '12323kjfnjnjk';
            $check_condition = " 1=1 And status='Active' ";
            
            $userLanguage = 'ar';
            $currency_code = DEFAULT_CURRENCY;
            //User choice Currency_code and userLanguage  : START
            if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
                $currency_code = $_POST['currency_code'];
            }
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
                $userLanguage = $_POST['userLanguage'];

            }
            //User choice Currency_code and userLanguage  : END
            $token_array = $this->read_header_token($userLanguage);
          
           if($this->read_header() && is_array($token_array)){
               $user_id = $token_array['uid'];
                $user_password = "";
               $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
                $merchantExist = $this->apis_models1->getData( 'user_id,password', 'user', $check_condition );
               if ( !is_array( $merchantExist ) ) {
                   $this->errorMessage('user_not_found', $msg_data, $userLanguage);
               }
                $user_password = $merchantExist[0]['password'];
                $merchantId = $merchantExist[0]['user_id'];
              
                if(isset($_POST['authPassword']) && !empty($_POST['authPassword'])){
                    if($user_password != md5($_POST['authPassword']) ){
                        $this->errorMessage('incorrect_authentication_password', $msg_data, $userLanguage);
                    }
                }else{
                    $this->errorMessage('please_enter_password', $msg_data, $userLanguage);
                }
                
                $type_lebel = '_wallet_';
                if(isset($_POST['type']) && !empty($_POST['type'])){
                    $type = $_POST['type'];
                    if($type != 'paymentCard' && $type != 'wallet'){
                        $this->errorMessage('invalid_card_type', $msg_data, $userLanguage);
                    }
                    if($type == 'paymentCard'){
                        $type_lebel = '_card_';
                    }
                }else{
                    $this->errorMessage('please_provide_card_type', $msg_data, $userLanguage);
                }
                
                $user_card_id = 0;
                if(isset($_POST['cardId']) && !empty($_POST['cardId'])){
                    $user_card_id = $_POST['cardId'];
                    $cardCondition = ' user_card_id = '.$this->db->escape($user_card_id);
                    $cardCondition .= ' AND user_id = '.$this->db->escape($user_id);
                    $cardCondition .= ' AND type = '.$this->db->escape($type);
                    $merchantCard = $this->apis_models1->getData( 'user_card_id', 'user_card', $cardCondition );
                    if (!is_array( $merchantCard ) ) {
                        $msg = "no_such".$type_lebel."found";
                        $this->errorMessage($msg, $msg_data, $userLanguage);
//                        $this->errorMessage('no_such_card_found', $msg_data, $userLanguage);
                    }
                }
                
                $dbCardNumber = "";
               if(isset($_POST['cardNumber']) && !empty($_POST['cardNumber'])){
                   $cardNumber = $_POST['cardNumber'];
                    $dbCardNumber = rtrim(base64_encode($cardNumber), '=');
                    $cardNumberCondition = ' user_id = '.$this->db->escape($user_id);
                    $cardNumberCondition .= ' AND card_number = '.$this->db->escape($dbCardNumber);
                    $cardNumberCondition .= ' AND type = '.$this->db->escape($type);
                    if(!empty($user_card_id)){
                    $cardNumberCondition .= ' AND user_card_id != '.$this->db->escape($user_card_id);
                    }
                    $merchantCard = $this->apis_models1->getData( 'user_card_id', 'user_card', $cardNumberCondition );
                    if (is_array( $merchantCard ) ) {
                        $msg = "user_cannot_have_multiple".$type_lebel."with_same".$type_lebel."number";
                        $this->errorMessage($msg, $msg_data, $userLanguage);
//                        $this->errorMessage('user_cannot_have_multiple_card_with_same_card_number', $msg_data, $userLanguage);
                    }
                    
               }else{
                    $msg = "please_enter".$type_lebel."number";
                    $this->errorMessage($msg, $msg_data, $userLanguage);
//                    $this->errorMessage('please_enter_card_number', $msg_data, $userLanguage);
               }

               if(isset($_POST['cardName']) && !empty($_POST['cardName'])){
                    $cardName= $_POST['cardName'];
                    $upperCardName = trim(strtoupper($cardName));
                    $cardNameCondition = ' user_id = '.$this->db->escape($user_id);
                    $cardNameCondition .= ' AND UPPER(card_name) = '.$this->db->escape($upperCardName);
                    $cardNameCondition .= ' AND type = '.$this->db->escape($type);
                    if(!empty($user_card_id)){
                    $cardNameCondition .= ' AND user_card_id != '.$this->db->escape($user_card_id);
                    }
                    $merchantCard = $this->apis_models1->getData( 'user_card_id', 'user_card', $cardNameCondition );
                    if (is_array( $merchantCard ) ) {
                        $msg = "user_cannot_have_multiple".$type_lebel."with_same".$type_lebel."name";
                        $this->errorMessage($msg, $msg_data, $userLanguage);
//                        $this->errorMessage('user_cannot_have_multiple_card_with_same_card_name', $msg_data, $userLanguage);
                    }
                    //check if the same user not has many card with same name , give it an error as user cannot have more than one cards with same name
                    //cehck is pending
               }else{
                
                    $msg = "please_enter".$type_lebel."name";
                    $this->errorMessage($msg, $msg_data, $userLanguage);
//                    $this->errorMessage('please_enter_card_name', $msg_data, $userLanguage);
               }
               
             
                $cardValidity = "";
                if($type == 'paymentCard'){
               if(isset($_POST['cardValidity']) && !empty($_POST['cardValidity'])){
                   $cardValidity = $_POST['cardValidity'];
               }else{
                   $this->errorMessage('please_enter_card_validity', $msg_data, $userLanguage);
               }
                }

               $txn_date =        date('Y-m-d H:i:s');
//               $dbCardNumber = $this->mcrypt->encryptInternal($cardNumber);
               $insert_array = array(
                                       'user_id'=>$merchantId,
                                       'card_name'=>$cardName,
                                       'card_number'=>$dbCardNumber,
                                       'card_validity'=>$cardValidity,
                                       'type'=>$type,
                                       'status'=>'Active',
                                       'created_by'=>'merchant',
                                   );
            
            if(empty($user_card_id)){
                $insert_array['created_on']= $txn_date;
               $transaction_id = $this->db->insert($table_name, $insert_array);
               if(!empty($transaction_id) && isset($transaction_id)){
                    $msg = "user".$type_lebel."added_successfully";
                    $this->successMessage($msg, $msg_data, $userLanguage);
//                    $this->successMessage('user_card_added_successfully', $msg_data, $userLanguage);
               }else{
                    $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
               }

           }else{
                $updateCondition =  " user_card_id = ". $this->db->escape($user_card_id);
                $update_result = $this->apis_models1->updateRecord($table_name, $insert_array, $updateCondition);
                if($update_result){
                    $msg = "user".$type_lebel."updated_successfully";
                    $this->successMessage($msg, $msg_data, $userLanguage);
//                    $this->successMessage('user_card_updated_successfully', $msg_data, $userLanguage);
                }else{
                     $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
                }
            }
                

           }else{
                $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
           }    
    }
        
    function processDeleteCards() {
            $data = json_decode( file_get_contents( 'php://input' ), true );
            if ( isset( $data ) && ! empty( $data ) ) {
                    $_POST = $data;
            }
            $msg_data = array();
            $table_name = 'user_card';
            $randomNumber = '12323kjfnjnjk';
            $check_condition = " 1=1 And status='Active' ";
            $meter_condition = " 1=1 ";
             
            $userLanguage = 'ar';
            $currency_code = DEFAULT_CURRENCY;
            //User choice Currency_code and userLanguage  : START
            if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
                $currency_code = $_POST['currency_code'];
            }
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
                $userLanguage = $_POST['userLanguage'];

            }
            //User choice Currency_code and userLanguage  : END
            $token_array = $this->read_header_token($userLanguage);
            if($this->read_header() && is_array($token_array)){
            $user_id =  $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }

            $merchantId = $merchantExist[0]['user_id'];
            $meter_condition .= " And user_id = ".$this->db->escape($merchantId);
            
            $type_lebel = '_wallet_';
            if(isset($_POST['type']) && !empty($_POST['type'])){
                $type = $_POST['type'];
                if($type != 'paymentCard' && $type != 'wallet'){
                    $this->errorMessage('invalid_card_type', $msg_data, $userLanguage);
                }
                if($type == 'paymentCard'){
                    $type_lebel = '_card_';
                }
            }else{
                $this->errorMessage('please_provide_card_type', $msg_data, $userLanguage);
            }
            
            if(isset($_POST['cardId']) && !empty($_POST['cardId'])){
                $cardId= $_POST['cardId'];
                $meter_condition .= " And user_card_id = ".$this->db->escape($cardId);
                $meter_condition .= " And type = ".$this->db->escape($type);
                $merchantCardExist = $this->apis_models1->getData( 'user_card_id', 'user_card', $meter_condition );
                if ( !is_array( $merchantCardExist ) ) {
                    $msg = "no_such".$type_lebel."found";
                    $this->errorMessage($msg, $msg_data, $userLanguage);
//                    $this->errorMessage('no_such_card_found', $msg_data, $userLanguage);
                }
            }else{
                $msg = "please_select".$type_lebel."for_to_delete";
                $this->errorMessage($msg, $msg_data, $userLanguage);
//                $this->errorMessage('please_select_card_for_to_delete', $msg_data, $userLanguage);
            }
            
            //From here POST it to EBS bank and this integration part is still pending : end
            $txn_date =        date('Y-m-d H:i:s');
            $delete_status = $this->apis_models1->delrecord($table_name,$meter_condition);

            if(!empty($delete_status) && isset($delete_status)){
                $msg = "user".$type_lebel."deleted_successfully";
                $this->successMessage($msg, $msg_data, $userLanguage);
//                $this->successMessage('user_card_deleted_successfully', $msg_data, $userLanguage);
            }else{
                $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
            }

        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }    
    }
    
    public function fetchUserCardData(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
            if ( isset( $data ) && ! empty( $data ) ) {
                    $_POST = $data;
            }

            $language_id = "002";
            $table_name = "user_card";
            $randomKey= "09898798Mhggvhv";
            $select = "user_card_id as id,card_name as name,card_number,type,card_validity";
            $msg_data = array();
            $keyType  =  '';
            $type = 'paymentCard';
            
            $userLanguage = 'ar';
            $currency_code = DEFAULT_CURRENCY;
            //User choice Currency_code and userLanguage  : START
            if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
                $currency_code = $_POST['currency_code'];
            }
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
                $userLanguage = $_POST['userLanguage'];

            }
            //User choice Currency_code and userLanguage  : END
            
            $token_array = $this->read_header_token($userLanguage);
            $condition = $check_condition = " 1=1 and status='Active' ";
        if($this->read_header() && is_array($token_array)){
            $user_id =  $token_array['uid'];
            $condition .= ' AND user_id = '.$this->db->escape($user_id);
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                 $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }

            $type_lebel = '_wallet_';
            if(isset($_POST['type']) && !empty($_POST['type'])){
                $type = $_POST['type'];
                $condition .= ' AND type = '.$this->db->escape($type);
                if($type != 'paymentCard' && $type != 'wallet'){
                    $this->errorMessage('invalid_card_type', $msg_data, $userLanguage);
                }
                if($type == 'paymentCard'){
                    $type_lebel = '_card_';
                }
            }else{
                $this->errorMessage('please_provide_card_type', $msg_data, $userLanguage);
            }

            $data = $this->apis_models1->getData($select,$table_name,$condition);
           
            if(is_array($data[0])){
                //user card data returned successfully
                $final_data = array();
                foreach($data as $key => $val){
                    $final_data[$key]['id'] =  $val['id'];
                    $final_data[$key]['name'] =  $val['name'];
                    $card_number = base64_decode($val['card_number']);
                    $final_data[$key]['card_validity'] =  $val['card_validity'];
                    $final_data[$key]['card_number'] =  $card_number;
                    //MASK card number -- check added by sagar
                    if($val['type'] == 'wallet'){
                        $final_data[$key]['no'] = ($card_number);
                    }else{
                        $final_data[$key]['no'] = maskCardNumber($card_number);
                    }
                    $final_data[$key]['type'] =  $val['type'];
                }
                $responseData = array(
                    'data'=>$final_data,
                );
                $msg = "fetch_user".$type_lebel."list";
                $this->successMessage($msg, $msg_data, $userLanguage,$responseData);
//                $this->successMessage('fetch_user_card_list', $msg_data, $userLanguage,$responseData);
            }else{
                $msg = "empty".$type_lebel."list";
                $this->errorMessage($msg, $msg_data, $userLanguage);
//                $this->errorMessage('empty_card_list', $msg_data, $userLanguage);
            }   
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }
    //added by ritesh for processing card data : End
    
    //FORGET PASSWORD OTP FLOW : START
    public function requestOtp(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        
        $msg_data = array();
        $mobile_no     = '';
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        if($this->read_header()){
            if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {
                if (( preg_match($this->mobile_check, $_POST['phone_number']))) {
                    $mobile_no = $_POST['phone_number'];
                    $mobileExist = $this->apis_models1->verify_if_unique('user', 'phone = ' . $this->db->escape($mobile_no));
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
            $current_time = time(); // Current timestamp
            $expiry_time = strtotime(OTP_EXPIRY_TIME, $current_time); // Add 15 minutes to current time
            $expiry_time_formatted = date('Y-m-d H:i:s', $expiry_time); // Format the expiry time
            //trigger this random number in sms to user
            $mobileLast9Digit = $mobile_no;
            if(strlen($mobile_no) > 9 ){
                $mobileLast9Digit = substr($mobile_no, -9);
            }
            $mobile_no_with_code = '249'.$mobileLast9Digit;
            $this->messaging_model->send_otp_sms($randomNumber,$mobile_no_with_code);
            $insert_array = array(
                'otp_code'=>$randomNumber,
                'mobile_no'=>$mobile_no,
                'mobile_no_with_code'=>$mobile_no_with_code,
                'expiry_time'=>$expiry_time_formatted
            );

            $MobileExistArray = $this->db->get_where('tbl_otp',array('mobile_no'=>$mobile_no))->row_array();
            
            if(is_array($MobileExistArray)){
                $last_count = $MobileExistArray['count'];
                $last_hitting_time = ($MobileExistArray['updated_on']);
//                $next_24_hour_time = (strtotime("$last_hitting_time +  1 day"));
                $next_24_hour_time = (strtotime("$last_hitting_time +  1 hour"));  //changed
                $current_time =  time();
                $new_count = 0;
                if($current_time > $next_24_hour_time || $last_count < 3){
                    $new_count = $last_count+1;
                    if($new_count > 3){
                        $new_count=1;
                    }
                }else{
                    $this->errorMessage('your_number_is_blocked_for_next_24_hours', $msg_data, $userLanguage);
                }
                $update_array = array('otp_code'=>$randomNumber,'updated_on'=>date('Y-m-d H:i:s'),'count'=>$new_count,'expiry_time'=>$expiry_time_formatted);
                $condition= 'mobile_no = '. $this->db->escape($mobile_no);
                $otp_id = $this->apis_models1->updateRecord('tbl_otp',$update_array,$condition);
            }else{
                $insert_array['count'] = 1;
                $otp_id = $this->db->insert('tbl_otp', $insert_array);
            }
            
            /* OLD FLOW 
            if(is_array($MobileExistArray)){
                $update_array = array('otp_code'=>$randomNumber,'updated_on'=>date('Y-m-d H:i:s'));
                $condition= 'mobile_no = '. $this->db->escape($mobile_no);
                $otp_id = $this->apis_models1->updateRecord('tbl_otp',$update_array,$condition);
            }else{
                $otp_id = $this->db->insert('tbl_otp', $insert_array);
            }
            */
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
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
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
                $expiry_date = new DateTime();
                $expiry_date->format('Y-m-d H:i:s');
                
                $otp_code = $_POST['otp_code'];
                $condition =  '1=1';
                $condition .=  ' AND i.otp_code = '. $this->db->escape($otp_code);
                $condition .=  ' AND i.mobile_no = '. $this->db->escape($mobile_no);
                $data = $this->apis_models1->getData('i.*','tbl_otp',$condition);
                if(is_array($data[0])){
                    if ($data[0]['expiry_time'] < date('Y-m-d H:i:s')){
                        $this->errorMessage('expiry_otp_message', $msg_data, $userLanguage);
                    }else{
                        $this->successMessage('otp_verified', $msg_data, $userLanguage);
                    }
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
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
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
                $password = md5($_POST['password']);
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
                $condition .=  ' AND i.otp_code = '. $this->db->escape($otp_code);
                $condition .=  ' AND i.mobile_no = '. $this->db->escape($mobile_no);
                $data = $this->apis_models1->getData('i.*','tbl_otp',$condition);
                if(!is_array($data[0])){
                    $this->errorMessage('invalid_otp', $msg_data, $userLanguage);
                }
            }else{
                 $this->errorMessage('please_enter_otp_code', $msg_data, $userLanguage);
            }
            
            $updatePasswordData = array('password' => $password);
            $check_condition = ' phone = ' . $this->db->escape($mobile_no);
            $this->apis_models1->updateRecord('user', $updatePasswordData, $check_condition);
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

    //FOR TIMESLOTS : START
    function getTimeSlots(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $msg_data = array();
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
    
            }
        //User choice Currency_code and userLanguage  : END
        $date = date('Y-m-d');
        if($this->read_header()){
            if(isset($_POST['date'])){
                if($_POST['date'] < $date){
                    $this->errorMessage('date_cannot_be_less_than_current_date', $msg_data, $userLanguage);
                }
                $date = $_POST['date'];
            }

            if(!$this->validateDate($date)) {
                $this->errorMessage('invalid_date', $msg_data, $userLanguage);
            }
            $timeslotDay =  strtolower(date("l", strtotime($date)));

            $nextDate = date('Y-m-d', strtotime($date. ' + 1 days'));
            $timeslotNextDay =  strtolower(date("l", strtotime($nextDate)));
            


            $currentTime = $addedCurrentTime = date('H:i:s');
            $timeslot_minute =  $this->db->get_where('general_settings',array('type' => 'timeslot_minute'))->row()->value;
            if(!empty($timeslot_minute)){
                $addedCurrentTime = date('H:i:s',strtotime($currentTime . " +$timeslot_minute minutes"));
            }
            //OLD query
            // $data_array =  $this->db->order_by('start_time', 'ASC')->get_where('timeslots', array('status'=>'ok','day'=>$timeslotDay ))->result_array();
            $data_array = $this->apis_models1->getDeliveryTimeSlots($timeslotDay,$timeslotNextDay);
            
             /*echo '<pre>';
            print_r($this->db->last_query());
            print_r('|||||||||||||||||');
            print_r($timeslotDay);
            print_r('|||||||||||||||||');
            print_r($timeslotNextDay);
            print_r('|||||||||||||||||');
            print_r($data_array);
            print_r('|||||||||||||||||');
            print_r($currentTime);
            print_r('|||||||||||||||||');
            print_r($timeslot_minute);
            print_r('|||||||||||||||||');
            print_r($addedCurrentTime);
            exit();*/

            $final_array = array();

            if(is_array($data_array) && !empty($data_array)) {
                foreach($data_array as $key => $val) {
                    $key_date = $date;

                    if ($timeslotNextDay == $val['day']) {
                        $key_date =  $nextDate;
                    }
                    $final_array[$key_date]['date'] = $key_date;
                    $final_array[$key_date]['day'] = $val['day'];
                    $final_array[$key_date]['timeslots'][] = array(
                        'timeslots_id' => $val['timeslots_id'],
                        'date' => $key_date,
                        'time' => date('h:i A',strtotime($val['start_time'])) . ' - '. date('h:i A',strtotime($val['end_time'])),
                        'week_day_no' => date('N', strtotime($val['day'])),
                        'start_time' =>  $val['start_time'],
                        'end_time' =>  $val['end_time'],
                    );
                }
                $responseData = array(
                    'data' => array_values($final_array),
                );
                $this->successMessage('returned_all_timeslots', $msg_data, $userLanguage,$responseData,$timeslotDay);
            } else {
                $responseData = array(
                    'data' => $data_array,
                );
                $this->errorMessage('no_timeslots_found', $msg_data, $userLanguage,$responseData);
            }
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    private function validateDate($date) {
        $tempDate = explode('-', $date);
        // validateDate(month, day, year)
        return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
    }  

    //FOR TIMESLOTS : END 

    //FOR CITY AND AREA : START
    function getCity(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $msg_data = array();
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $city_id = 0;
        if($this->read_header()){
            if(isset($_POST['city_id']) && !empty($_POST['city_id'])){
                $city_id = $_POST['city_id'];
                $cityExist = $this->apis_models1->verify_if_unique('city', 'city_id = ' . $this->db->escape($city_id));
                if (!is_array($cityExist)) {
                    $this->errorMessage('no_such_city_found', $msg_data, $userLanguage);
                }
            }
            
            $condition = " 1=1 and status = 'ok' ";
            if(!empty($city_id)) {
            $condition .= " AND city_id = ". $this->db->escape($city_id);
            }
            $orderBy = " city_name_en ASC";
            $data_array =  $this->apis_models1->getData('city_id,city_name_en,city_name_ar,status','city',$condition,null,$orderBy);
            $finalArray = array();
            if(is_array($data_array) && !empty($data_array)){
               $responseData = array(
                    'data'   => $data_array,
               );
                $this->successMessage('returned_city_data', $msg_data, $userLanguage,$responseData);
            }else{
                $responseData = array(
                    'data'   => $data_array,
                ); 
                $this->errorMessage('empty_city_list', $msg_data, $userLanguage,$responseData);
            }
            
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
        
    }
    
    function getArea(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $msg_data = array();
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $area_id = 0;
        if($this->read_header()){
            if(isset($_POST['area_id']) && !empty($_POST['area_id'])){
                $area_id = $_POST['area_id'];
                $areaExist = $this->apis_models1->verify_if_unique('area', 'area_id = ' . $this->db->escape($area_id));
                if (!is_array($areaExist)) {
                    $this->errorMessage('no_such_area_found', $msg_data, $userLanguage);
                }
            }
            if(isset($_POST['city_id']) && !empty($_POST['city_id'])){
                $city_id = $_POST['city_id'];
                $cityExist = $this->apis_models1->verify_if_unique('city', 'city_id = ' . $this->db->escape($city_id));
                if (!is_array($cityExist)) {
                    $this->errorMessage('no_such_city_found', $msg_data, $userLanguage);
                }
            }

            $condition = " 1=1 and status = 'ok' ";
            if(!empty($area_id)) {
            $condition .= " AND area_id = ". $this->db->escape($area_id);
            }
            if(!empty($city_id)) {
            $condition .= " AND city_id = ". $this->db->escape($city_id);
            }
            $orderBy = " area_name_en ASC";
            $data_array =  $this->apis_models1->getData('area_id,city_id,area_name_en,area_name_ar,delivery_charge,status','area',$condition,null,$orderBy);
            $finalArray = array();
            if(is_array($data_array) && !empty($data_array)){
               $responseData = array(
                    'data'   => $data_array,
               );
                $this->successMessage('returned_area_data', $msg_data, $userLanguage,$responseData);
            }else{
                $responseData = array(
                    'data'   => $data_array,
                ); 
                $this->errorMessage('empty_area_list', $msg_data, $userLanguage,$responseData);
            }
            
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
        
    }
    //FOR CITY AND AREA : END

    //FOR User Address List And Save Address: START
    public function fetchUserAddress(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }

        $table_name = "user_address";
        $select = "address_id, user_id, title, number,address_1,address_2,landmark, city_id, area_id, langlat,default_address";
        $msg_data = array();

        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        $check_condition = " 1=1 and status='Active' ";
        $condition = " 1=1 ";

        if($this->read_header() && is_array($token_array)){
            $user_id =  $token_array['uid'];
            $condition .= ' AND user_id = '.$this->db->escape($user_id);
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );

            if (!is_array( $merchantExist)) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            $data = $this->apis_models1->getData($select,$table_name,$condition);

            if(is_array($data) && !empty($data[0])){
                foreach ($data as $key => $val){
                    $address =  explode(',',$val['langlat']);
                    $locationPoints = array_reverse($address);
                    $data[$key]['langlat'] = implode(',',$locationPoints);
                }
                $responseData = array(
                    'data'   => $data,
                );
                $this->successMessage('fetched_user_address', $msg_data, $userLanguage,$responseData);
            }else{
                $responseData = array(
                    'data'   => $data,
                );
                $this->errorMessage('empty_user_address_list', $msg_data, $userLanguage,$responseData);
            }
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }
    
    function saveUserAddress() {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $table_name = 'user_address';
        $check_condition = " 1=1 And status='Active' ";
        $title = $number = "";
        $city_id = $area_id = 0;
        $address_id = 0;
        $landmark = '';
        $default_address = 'no';
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);

        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );
            //added by Arjun 03-07-2024 :Start
            $addressLimit = $this->db->get_where( 'user_address', array( 'user_id' => $user_id ) )->result_array();
            if(count($addressLimit) >= ADDRESS_LIMIT){
                $this->errorMessage('address_limit_reached', $msg_data, $userLanguage);
                exit();
            }
            //added by Arjun 03-07-2024 :End
            if ( !is_array( $merchantExist ) ) {
                 $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }

            if(isset($_POST['title']) && !empty($_POST['title'])){
                $title = $_POST['title'];
            }else{
                $this->errorMessage('please_provide_address_title', $msg_data, $userLanguage);
            }

            if(isset($_POST['address_1']) && !empty($_POST['address_1'])){
                $address_1 = $_POST['address_1'];
            }else{
                $this->errorMessage('please_provide_address_1', $msg_data, $userLanguage);
            }
            if(isset($_POST['address_2']) && !empty($_POST['address_2'])){
                $address_2 = $_POST['address_2'];
            }else{
                $this->errorMessage('please_provide_address_2', $msg_data, $userLanguage);
            }
            if(isset($_POST['landmark']) && !empty($_POST['landmark'])){
                $landmark = $_POST['landmark'];
            }

             if(isset($_POST['address_id']) && !empty($_POST['address_id'])){
                $address_id = $_POST['address_id'];
                $order_by = " created_on ASC ";
                $select = 'address_id,user_id,default_address';
                $addressCondition = ' address_id = '.$this->db->escape($address_id);
                $addressCondition .= ' AND user_id = '.$this->db->escape($user_id);

               $addressExist = $this->apis_models1->getData( $select, 'user_address', $addressCondition );
                if (!is_array( $addressExist ) ) {
                   $this->errorMessage('no_such_address_found', $msg_data, $userLanguage);
                }
            }

            if(isset($_POST['default_address']) && !empty($_POST['default_address'])){
                $default_address = $_POST['default_address'];
                // added by Arjun on 01-Jul-2023 : start
                if($default_address == 'yes'){
                    $updateDefaultCondition = ' user_id  = '.$this->db->escape($user_id);
                    $updateDefaultCondition .= ' AND address_id  != '.$this->db->escape($address_id);
                    $removeDefault = $this->apis_models1->updateRecord('user_address', array('default_address'=>'no'), $updateDefaultCondition);
                }

                 if($default_address == 'no'){
                    $fetchDefaultAddressCondition = " default_address = 'yes' ";
                    $fetchDefaultAddressCondition .= ' AND user_id  = '.$this->db->escape($user_id);
                    $isDefaultAddressNow = $this->apis_models1->get_type_name_by_primary('user_address', 'address_id',$address_id, 'default_address');

                    if($isDefaultAddressNow == 'yes'){
                        $this->errorMessage('make_default_address', $msg_data, $userLanguage);
                    }

                }

            }
            
            if(isset($_POST['number']) && !empty($_POST['number'])){
                $number = $_POST['number'];
            }

            if(isset($_POST['city_id']) && !empty($_POST['city_id'])){
                 $city_id= $_POST['city_id'];
                 $cityCondition = ' city_id = '.$this->db->escape($city_id);
                 $cityExist = $this->apis_models1->getData( 'city_id', 'city', $cityCondition );
                 if (!is_array( $cityExist ) ) {
                    $this->errorMessage('no_such_city_found', $msg_data, $userLanguage);
                 }
            }else{
                 $this->errorMessage('please_select_city', $msg_data, $userLanguage);
            }
            
            if(isset($_POST['area_id']) && !empty($_POST['area_id'])){
                 $area_id= $_POST['area_id'];
                 $areaCondition = ' area_id = '.$this->db->escape($area_id);
                 $areaCondition .= ' AND city_id  =  '.$this->db->escape($city_id);
                 $areaExist = $this->apis_models1->getData( 'area_id', 'area', $areaCondition );
                 if (!is_array( $areaExist ) ) {
                    $this->errorMessage('no_such_area_found', $msg_data, $userLanguage);
                 }
            }else{
                 $this->errorMessage('please_select_area', $msg_data, $userLanguage);
            }
            
            
            if (isset($_POST['longitude']) && !empty($_POST['longitude']) && 
                isset($_POST['latitude']) && !empty($_POST['latitude'])) {
                $langlat = $_POST['longitude'].','.$_POST['latitude'];
            }
         
                $data = array(
                            'user_id'=>$user_id,
                            'title'=>$title,
                            'number'=>$number,
                            'address_1'=>$address_1,
                            'address_2'=>$address_2,
                            'landmark'=>$landmark,
                            'city_id'=>$city_id,
                            'area_id'=>$area_id,
                            'langlat'=>$langlat,
                            'default_address'=>$default_address,
                );

            if(empty($address_id)){
                $data['created_on']= date('Y-m-d H:i:s');
                $result_id = $this->db->insert($table_name, $data);
            if(!empty($result_id) && isset($result_id)){
                $this->successMessage('user_address_saved_successfully', $msg_data, $userLanguage);
            }else{
                 $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
            }

        }else{
                $updateCondition =  " address_id = ". $this->db->escape($address_id);
                $update_result = $this->apis_models1->updateRecord('user_address', $data, $updateCondition);
                if($update_result){
                    $this->successMessage('user_address_updated_successfully', $msg_data, $userLanguage);
                }else{
                     $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
                }
            }

        }else{
             $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }    
    }
    
    function deleteUserAddress() {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $table_name = 'user_address';
        $check_condition = " 1=1 And status='Active' ";
        $address_id = 0;
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $meter_condition = " 1=1 ";
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);

        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                 $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            
            $meter_condition .= " And user_id = ".$this->db->escape($user_id);
            if(isset($_POST['address_id']) && !empty($_POST['address_id'])){
                $address_id = $_POST['address_id'];
                $meter_condition .= " And address_id = ".$this->db->escape($address_id);
                $addressCondition = ' address_id = '.$this->db->escape($address_id);
                $addressCondition .= ' AND user_id = '.$this->db->escape($user_id);
                $addressExist = $this->apis_models1->getData( 'address_id', 'user_address', $addressCondition );
                if (!is_array( $addressExist ) ) {
                   $this->errorMessage('no_such_address_found', $msg_data, $userLanguage);
                }
            }else{
                $this->errorMessage('please_select_address_to_delete', $msg_data, $userLanguage);
            }
            
            $delete_status = $this->apis_models1->delrecord($table_name,$meter_condition);
            if(!empty($delete_status) && isset($delete_status)){
                $this->successMessage('user_address_deleted_successfully', $msg_data, $userLanguage);
            }else{
                $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
            }

        }else{
             $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }    
    }
    //FOR User Address List And Save Address: END

    //FOR Change password once user logged in : START
    function changePassword() {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $table_name = 'user';
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $old_password = $new_password = $confirm_password = "";

        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        if($this->read_header() && is_array($token_array)){
            $existing_password = "";
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id,password', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            $existing_password = $merchantExist[0]['password'];
  
            if (isset($_POST['old_password']) && !empty($_POST['old_password'])) {
                $old_password = md5($_POST['old_password']);
                if($existing_password != $old_password){
                    $this->errorMessage('incorrect_old_password', $msg_data, $userLanguage);
                    exit();
                }
                
            } else {
                $this->errorMessage('please_enter_old_password', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
                 if(strlen($_POST['new_password']) < 4 ){
                    $this->errorMessage('password_length_error', $msg_data, $userLanguage);
                }
                $new_password = $_POST['new_password'];
            } else {
                $this->errorMessage('please_enter_new_password', $msg_data, $userLanguage);
                exit();
            }
            
            if (isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])) {
                if($new_password != $_POST['confirm_password']){
                    $this->errorMessage('password_not_match', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_confirm_password', $msg_data, $userLanguage);
                exit();
            }
            
            $updatepassworddata['password'] = md5($new_password);
            $updateCondition =  " user_id = ". $this->db->escape($user_id);
            $update_result = $this->apis_models1->updateRecord('user', $updatepassworddata, $updateCondition);
            
            if($update_result){
                $this->successMessage('password_changed_successfully', $msg_data, $userLanguage);
            }else{
                 $this->errorMessage('please_login_and_try_again', $msg_data, $userLanguage);
            }

        }else{
             $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }    
    } 
    //FOR Change password once user logged in : END 
    
    //FOR Order cancellation Flow : START
     function cancelOrder() {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $table_name = 'user';
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $orders_id = 0;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id,wallet_balance', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            //added by sagar - comment for cancel order  :START
            $comment = "";
            if (isset($_POST['comment']) && !empty($_POST['comment'])) {
                $comment = $_POST['comment'];
            /*
            }else{
                $this->errorMessage('reason_to_cancel_order', $msg_data, $userLanguage);
            */
            }
            //added by sagar - comment for cancel order :END
            
            if (isset($_POST['orders_id']) && !empty($_POST['orders_id'])) {
                $orders_id = $_POST['orders_id'];
                $orderData = $this->db->get_where('sale', array('sale_code' => $orders_id))->result_array();
                if (is_array($orderData) && !empty($orderData[0])) {
                    $sale_id =  $orderData[0]['sale_id'];
                    $sale_code =  $orderData[0]['sale_code'];
                    $order_status =  $orderData[0]['order_status'];
                    $paymentType =  $orderData[0]['payment_type'];
                    $grand_total_in_usd =  $orderData[0]['grand_total'];
                    if($order_status == 'cancelled'){
                        $this->errorMessage('order_already_gets_cancelled', $msg_data, $userLanguage);
                    }
                    
                    //commented by sagar -- as it not required
//                    if($paymentType != 'payInCard' &&  $paymentType != 'payInCash' && $paymentType != 'trolleyCredit'){
//                        $this->errorMessage('only_cash_or_swipe_card_allowed_to_cancel_order', $msg_data, $userLanguage);
//                    }
                   
                    $delivery_date_timeslots =  json_decode($orderData[0]['delivery_date_timeslot'],true);
                    $order_cancellation_time =  $this->db->get_where('general_settings',array('type' => 'order_cancellation_time'))->row()->value;
                    
                    $isUserCanCancelOrder = false;$isUserMoveMoneyToWallet = false;
                    if(is_array($delivery_date_timeslots[0]) && !empty($delivery_date_timeslots[0]['date']) 
                        && !empty($delivery_date_timeslots[0]['timeslot']) && !empty($order_cancellation_time) ){
                        
                        $delivery_date =  $delivery_date_timeslots[0]['date'];
                        $delivery_timeslots =  explode(' - ',$delivery_date_timeslots[0]['timeslot']);
                        $delivery_cancel_before_date_time = date("d-m-Y H:i:s", strtotime("$delivery_date $delivery_timeslots[0] - $order_cancellation_time Minutes"));
                        $current_date = date("d-m-Y H:i:s");
                        $delivery_time =  strtotime($delivery_cancel_before_date_time);
                        $current_time =  strtotime($current_date);
                        if($delivery_time > $current_time){
                            $isUserCanCancelOrder = true;
                        }
                    }
            $payment_status = json_decode($orderData[0]['payment_status'], true);
                    $user_choice = json_decode($orderData[0]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
             
                    if (is_array($payment_status) && isset($payment_status[0]['status']) && $payment_status[0]['status'] == 'paid') {
                         $isUserMoveMoneyToWallet = true;
                    }           

                    $shipping_details =  json_decode($orderData[0]['shipping_address'],true);
                    $userNumber = $shipping_details['phone_number'];
                    if($isUserCanCancelOrder){ 
                        $product_details = json_decode($orderData[0]['product_details'], true);
                        foreach ($product_details as $key => $val) {
                            $current_stock = $this->crud_model->get_type_name_by_id('variation', $val['variation_id'], 'current_stock');
                            $stock_data['type'] = 'add';
                            $stock_data['product'] = $val['product_id'];
                            $stock_data['variation_id'] = $val['variation_id'];
                            $stock_data['category'] = $this->crud_model->get_type_name_by_id('product', $val['product_id'], 'category');
                            $stock_data['sub_category'] = $this->crud_model->get_type_name_by_id('product', $val['product_id'], 'sub_category');
                            $stock_data['quantity'] = $val['qty'];
                            $stock_data['total'] = 0;
                            $stock_data['reason_note'] = 'Cancelled by App User';
                            $stock_data['sale_id'] = $sale_id;
                            $stock_data['datetime'] = time();
                            $this->db->insert('stock', $stock_data);
                            $update_stock = array('current_stock' => ( $current_stock + $val['qty'] ));
                            $this->db->where('variation_id', $val['variation_id']);
                            $this->db->where('product_id', $val['product_id']);
                            $this->db->update('variation', $update_stock);
                        }
                        
                        $payment_array_hardcoded[] = array(
                            'admin' => "",
                            'status' => 'cancelled',
                        );
                        
                        $delivery_status[] = array('admin' => '',
                            'status' => 'cancelled',
                            'comment' => 'cancel by user',
                            'delivery_time' => date('Y-m-d H:i:s'),
                        );
                        $update_sale = array(
                                    'order_status' => 'cancelled',
                                    'order_cancel_comment' => $comment,
                                    'payment_status' => json_encode($payment_array_hardcoded),
                                    'delivery_status' => json_encode($delivery_status),
                                        
                           );
                        $this->db->where('sale_id', $sale_id);
                        $this->db->update('sale', $update_sale);
                        //how amount would be refund to user.. -- NOT clear
                        
                        //if  true refund money back to wallet -- added by ritesh : start
                        //$isUserMoveMoneyToWallet =  false;
                        if($isUserMoveMoneyToWallet){
                            $vat_in_usd =  $orderData[0]['vat'];
                            $grand_total_in_sdg = get_converted_currency($grand_total_in_usd,2,$sale_currency_conversion_rate);
                            //Wallet insert part moved down as user current balance is required
                            
                            $check_condition = ' user_id = '.$this->db->escape($user_id);
                            $db_wallet_balance =  $this->db->get_where('user',array('user_id'=>$user_id))->row()->wallet_balance;
                            $updated_wallet = $db_wallet_balance + $grand_total_in_sdg;
                            $updated_wallet = round($updated_wallet,2);
                            $update_wallet_balance =  array(
                                'wallet_balance' =>$updated_wallet,
                            );
                            $this->db->where('user_id', $user_id);
                            $this->db->update('user', $update_wallet_balance);

                            $walletData = array(
                                    'user_id'=>$user_id,
                                    'amount'=>$grand_total_in_sdg,
                                    'type'=>'credit',
                                    'reason'=> 'added on cancellation of #'.$sale_code,
                                    'date_time'=>date('Y-m-d H:i:s'),
                                    'sale_id' =>$sale_id,
                                    'wallet_balance'=>$updated_wallet,
                                    'admin_id'=>0,
                                );
                            $this->db->insert('wallet', $walletData);
                            
                        }
                        //if  true refund money back to wallet -- added by ritesh : end
                        
                        //SMS TO USER - CANCEL ORDER
                        $this->messaging_model->sms_order_cancelled($orders_id,$userNumber);
                        //SMS TO USER - CANCEL ORDER
                        $this->successMessage('order_cancelled_successfully', $msg_data, $userLanguage);
                    }else{
                        $this->errorMessage('order_cannot_be_cancelled', $msg_data, $userLanguage);
                    }
                }else{
                    $this->errorMessage('no_such_order_id_found', $msg_data, $userLanguage);
                }
            }else{
                $this->errorMessage('provide_order_id_to_cancel_order', $msg_data, $userLanguage);
            }
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }    
    }
    //FOR Order cancellation Flow : END

    public function validateRegisterOtp()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $phone_code = DEFAULT_PHONE_CODE;
        $phone_number = $password = $email =  "";
        $first_name = $last_name = "";
        $gender = "";
        $job_type_id = $locality_id = $country_id = $state_id = 0;
        $dob = "0000-00-00";

        $userLanguage = "ar";
        $currency_code = DEFAULT_CURRENCY;
        $msg_data = array();

        //User choice Currency_code and userLanguage  : START
        if (!empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        //User choice Currency_code and userLanguage  : END

        $header = $this->read_header();

        if ($header) {
            if (!empty($_POST['phone_number'])) {
                $phone_number = $_POST['phone_number'];
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
            }
            $user_condition = " phone = " . $this->db->escape($phone_number);
            //added by sagar - as flow for registration  based on requestRegisterOtp - START
            $isUserExist = $this->apis_models1->verify_if_unique('user', $user_condition);

            if (!is_array($isUserExist)) {
                $this->errorMessage('something_went_wrong', $msg_data, $userLanguage);
            }
            if (is_array($isUserExist) && $isUserExist[0]['is_verified'] == 'Y') {
                $this->errorMessage('user_already_registered_with_given_phone_no', $msg_data, $userLanguage);
            }
            $phone_code = !empty($isUserExist[0]['phone_code']) ? $isUserExist[0]['phone_code'] : $phone_code;
            $phone_number = $isUserExist[0]['phone'];
            $user_id = $isUserExist[0]['user_id'];
            //added by sagar - as flow for registration  based on requestRegisterOtp - END

            //FOR OTP FLOW in register : START
            if (!empty($_POST['otp_code']) && isset($_POST['otp_code'])) {
                $otp_code = $_POST['otp_code'];
            } else {
                $this->errorMessage('please_enter_otp_code', $msg_data, $userLanguage);
                exit();
            }

            //otp_verified
            $otp_max_limit = WRONG_VERIFICATION_COUNTS;

            if (isset($otp_code) && !empty($otp_code)) {
                $mobileLast9Digit = (strlen($phone_number) > 9) ? substr($phone_number, -9) : $mobile_no;

                $mobile_no_with_code = $phone_code . $mobileLast9Digit;
                $condition =  '1=1';
                $condition .=  ' AND i.otp_code = ' . $this->db->escape($otp_code);
                $condition .=  ' AND i.mobile_no_with_code = ' . $this->db->escape($mobile_no_with_code);
                $condition .=  ' AND i.count <= ' . $this->db->escape($otp_max_limit);

                $data = $this->apis_models1->getData('i.*', 'tbl_otp', $condition);

                if (is_array($data[0])) {
                    $currentDateTime = date('Y-m-d H:i:s');

                    if (strtotime($currentDateTime) > strtotime($data[0]['expiry_time'])) {
                        $this->errorMessage('expiry_otp_message', $msg_data, $userLanguage);
                    }
                } else {
                    $this->errorMessage('invalid_otp', $msg_data, $userLanguage);
                }
            }
            //FOR OTP FLOW in register : END

            //Self Verified By User
            $verified_by['type'] = 'user';
            $verified_by['id'] = $user_id;
            $verified_by['date'] = date('Y-m-d h:i:s');

            $data_array = array(
                'status' => 'Active',
                'is_verified' => 'Y',
                'verified_by' => json_encode($verified_by),
                'updated_by' => $user_id
            );
            $update_condition = "user_id = " . $this->db->escape($user_id);
            $result = $this->apis_models1->updateRecord('user', $data_array, $update_condition);

            $this->successMessage('otp_verified', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
        }
    }

    public function processRegistration(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }

        $msg_data = array();
        $mobile_no     = '';
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        if($this->read_header()){
            if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {
                if (( preg_match($this->mobile_check, $_POST['phone_number']))) {
                    $mobile_no = $_POST['phone_number'];
//                    $numberCode = substr($mobile_no,0,3);
//                     if($numberCode != '249'){
//                        $this->errorMessage('phone_no_should_start_with_code_249', $msg_data, $userLanguage);
//                        exit();
//                    }
                    //Number Exist in Tbl then not allowed to otp
                    $mobileExist = $this->apis_models1->verify_if_unique('user', 'phone = ' . $this->db->escape($mobile_no));
                    if (is_array($mobileExist) && $mobileExist[0]['deleted_at'] == null) {
                        $this->errorMessage('user_already_registered_with_given_mobile_number', $msg_data, $userLanguage);
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
            $mobile_no_with_code = DEFAULT_PHONE_CODE . $mobileLast9Digit;
            $this->messaging_model->send_otp_sms($randomNumber,$mobile_no_with_code);
            $currentDateTime = date('Y-m-d H:i:s');
            $this->saveUserRegisterDetails();

            $insert_array = array(
                'otp_code'=>$randomNumber,
                'mobile_no'=>$mobile_no,
                'mobile_no_with_code'=>$mobile_no_with_code,
                'expiry_time' => date('Y-m-d H:i:s', (strtotime("$currentDateTime +  15 min"))),
            );
            $MobileExistArray = $this->db->get_where('tbl_otp',array('mobile_no'=>$mobile_no))->row_array();

            if(is_array($MobileExistArray)){
                $last_count = $MobileExistArray['count'];
                $last_hitting_time = ($MobileExistArray['updated_on']);
//                $next_24_hour_time = (strtotime("$last_hitting_time +  1 day"));
                $next_24_hour_time = (strtotime("$last_hitting_time +  1 hour"));
                $current_time =  time();
                $new_count = 0;
//                $next_24_hour_time = date('d-m-Y H:i:s',strtotime("$last_hitting_time +  1 day"));
//                $current_time = date("d-m-Y H:i:s", strtotime("$db_otp_time + 1 day"));
                if($current_time > $next_24_hour_time || $last_count < MAX_OTP_ATTEMPT){
                    $new_count = $last_count+1;
                    if($new_count > 3){
                        $new_count=1;
                    }
                }else{
                    $this->errorMessage('your_number_is_blocked_for_next_24_hours', $msg_data, $userLanguage);
                }
                $update_array = array('otp_code'=>$randomNumber,'updated_on'=>date('Y-m-d H:i:s'),'count'=>$new_count);
                $condition= 'mobile_no = '. $this->db->escape($mobile_no);
                $otp_id = $this->apis_models1->updateRecord('tbl_otp',$update_array,$condition);
            }else{
                $insert_array['count'] = 1;
                $otp_id = $this->db->insert('tbl_otp', $insert_array);
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

    public function collectionApi() {
        $msg_data = array();
        try {
            $page_no = 1;
            $limit = 10;
            $orderByCollection = true;
            $data = json_decode( file_get_contents( 'php://input' ), true );
            $header = $this->read_header();
            $token_array = $this->read_header_token($userLanguage);
            $user_id = $token_array['uid'] ?? 0;
            if ( isset( $data ) && ! empty( $data ) ) {
                    $_POST = $data;
            }

            if (isset($_POST['page_no']) && !empty($_POST['page_no'])) {
                $page_no = $_POST['page_no'];
            }
            if (isset($_POST['limit']) && !empty($_POST['limit'])) {
                $limit = $_POST['limit'];
            }

            $check_condition = " 1=1 And status='ok' ";
            if($orderByCollection){
                $order_by = " order_by_collection ASC ";
            }
            
            if (isset($_POST['collection_for']) && !empty($_POST['collection_for'])) {
                $collection_for = $_POST['collection_for'];
                if($collection_for == 'search'){
                    $check_condition .= " And visible_on_search_page ='yes' ";
                }elseif($collection_for == 'home'){
                    $check_condition .= " And visible_on_home_page ='yes' ";
                }else{
                    $check_condition = $check_condition;
                }
            }
            //User choice userLanguage  : START
            if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
                $userLanguage = $_POST['userLanguage'];

            }
            $offset = ($page_no - 1) * $limit;
            $data = $this->apis_models1->getData( '*', 'collection', $check_condition , '' , $order_by);

            $total_records = count($data);
            $data = array_slice($data, $offset, $limit);
            $i = 0;
            $j = 0;
            $collection_data = array();
            $collection_data['collection_image'] = array();
            $collection_data['product'] = array();
            $sequence = array();
            foreach ($data as $row) {
                $display_title = (in_array($row['type'], array('category'))) ? false : true;

                if ($row['is_offer'] == 'yes') {
                    $if_exist = in_array($row['order_by_collection'], array_column($sequence, 'seq_name'));
                    if ($if_exist) {
                        $index = array_search($row['order_by_collection'], array_column($sequence, 'seq_name'));
                        $ind = $sequence[$index]['ind'];
                        $collection_data['collection_image'][$ind]['type'] = 'multiple';
                        array_push($collection_data['collection_image'][$ind]['collection_data'], array(
                            "id" => $row['collection_id'],
                            "title" => $row['title'],
                            "display_name" => ($display_title) ? $row['title'] : '',
                            "collection_image" => $this->apis_models1->file_view('collection', $row['collection_id'], '', '' ,'no' ,'src'),
                            "upload_image" => (strtolower($row['is_offer']) == 'yes') ? '1' : '0' ,
                            "is_scrollable" => (strtolower($row['is_scrollable']) == 'yes') ? 'yes' : 'no' ,
                            "sequence" => $row['order_by_collection'],
                            "display_in_columns" => $row['display_in_columns'],
                            "display_data" => [],
                            "collection_type_detials" => [],
                        ));
                    } else {
                        array_push($sequence, array(
                            "seq_name" => $row['order_by_collection'], "ind" => $i
                        ));
                        array_push($collection_data['collection_image'], array(
                            "sequence" => $row['order_by_collection'],
                            "type" => $row['type'], // dynamic types
                            "collection_data" => array(
                                array(
                                    "id" => $row['collection_id'],
                                    "title" => $row['title'],
                                    "display_name" => ($display_title) ? $row['title'] : '',
                                    "collection_image" => $this->apis_models1->file_view('collection', $row['collection_id'], '', '','no','src'),
                                    "upload_image" => (strtolower($row['is_offer']) == 'yes') ? '1' : '0' ,
                                    "is_scrollable" => (strtolower($row['is_scrollable']) == 'yes') ? 'yes' : 'no' ,
                                    "sequence" => $row['order_by_collection'],
                                    "display_in_columns" => $row['display_in_columns'],
                                    "display_data" => [],
                                    "collection_type_detials" => [],
                                )
                            )
                        ));
                    }
                } else {
                    $j = 0;
                    $product_limit = 5;
                    $brand_limit = 5;
                    $product_data_arr = json_decode($row['product_for_collection'], true);
                    $brand_data_arr = json_decode($row['brand_for_collection'], true);
                    $display_data = array();

                    $collection_product_count = count($product_data_arr);
                    $collection_brand_count = count($brand_data_arr);

                    if ($row['type'] == "product") {
                    $product_data_arr = array_slice($product_data_arr,0,$product_limit);
                        if (!empty($product_data_arr)) {
                            $this->db->where_in('product_id', $product_data_arr);
                            $display_data = $this->db->get('product')->result_array();
                            //added by Arjun 05-07-2023 : Start
                            $this->db->where_in('product_id', $product_data_arr);
                            $this->db->where('user_id', $user_id);
                            $this->db->where('cart_session_id', md5($this->device_id));
                            $cartItem = $this->db->get('cart')->result_array();
                            $cartProductIds = array_column($cartItem, 'product_id');
                            //added by Arjun 05-07-2023 : End
                        }else{
                            $display_data = array();
                        }

                        foreach ($display_data as $value) {
                            if ($display_data[$j]['num_of_imgs'] > 0) {
                                $src_array = $this->apis_models1->file_view('product', $value['product_id'], '', '', 'no', 'src', 'multi', 'all');
                                if (!empty($src_array[0])) {
                                    $display_data[$j]['images'] = $src_array[0];
                                } else {
                                    $display_data[$j]['images'] = '';
                                }
                            }
                            $this->db->select('variation_id, current_stock')->where('product_id', $value['product_id']);
                            $variation = $this->db->get('variation')->row_array();

                            $display_data[$j]['rating_user'] = json_decode($value['rating_user'], true);
                            $display_data[$j]['attribute_ids'] = json_decode($value['attribute_ids'], true);
                            $display_data[$j]['options'] = json_decode($value['options'], true);
                            $display_data[$j]['added_by'] = json_decode($value['added_by'], true);
                            $display_data[$j]['additional_fields'] = json_decode($value['additional_fields'], true);
                            $display_data[$j]['variation_id'] = $variation['variation_id'];
                            $display_data[$j]['variation_stock'] = $variation['current_stock'];
                            //added by Arjun 05-07-2023 : Start
                            $display_data[$j]['InCartFlag'] = 0;
                            foreach ($cartItem as $item) {
                                if ($item['product_id'] == $value['product_id']) {
                                    $display_data[$j]['InCartFlag'] = 1;
                                    $display_data[$j]['cart_id'] = $item['cart_id'];
                                    $display_data[$j]['qty'] = $item['qty'];
                                    break;
                                }
                            }
                            //added by Arjun 05-07-2023 : End
                            $j++;
                        }
                    }

                    if ($row['type'] == "brands") {
                        $brand_data_arr = array_slice($brand_data_arr,0,$brand_limit);
                        if (!empty($brand_data_arr)) {
                            $this->db->where_in('brand_id', $brand_data_arr);
                            $display_data = $this->db->get('brand')->result_array();
                        }else{
                            $display_data = array();
                        }

                        foreach ($display_data as $key => $value) {
                            if(file_exists('uploads/brand_image/'.$value['logo'])){
                                $display_data[$key]['image'] =  base_url().'uploads/brand_image/'.$value['logo'];
                            }else{
                                $display_data[$key]['image'] =  base_url().'uploads/brand_image/default.jpg';
                            }
                        }
                    }    

                    if (!empty($row['extra_details'])) {
                        // below code updated by adnan to display new key collection_type_detials -- start
                        $detail_data_arr = json_decode($row['extra_details'], true);
                        unset($detail_data_arr['type']);

                        if ($row['type'] == "multiple") {
                            // over write img path key with the img source link
                            foreach ($detail_data_arr['extra_details'] as $keyone => $abc) {
                                $ext_id = $detail_data_arr['extra_details'][$keyone]['extra_id'];
                                $detail_data_arr['extra_details'][$keyone]['img_path'] = base_url() . 'uploads/collection_image/collection_'.$ext_id.'.jpg';
                            }
                        }

                        if ($row['type'] == "category") {
                            // creating two keys in the array itself when the type is category
                            foreach ($detail_data_arr['extra_details'] as $keyone => $abc) {
                                    $category_id = $detail_data_arr['extra_details'][$keyone]['category_id'];
                                    $detail_data_arr['extra_details'][$keyone]['category_image'] = $this->apis_models1->file_view('category', $category_id, '', '','no','src');
                                foreach ($detail_data_arr['extra_details'][$keyone]['sub_category'] as $key => $xyz) {
                                    $sub_cat_id = $detail_data_arr['extra_details'][$keyone]['sub_category'][$key]['sub_category_id'];
                                    $detail_data_arr['extra_details'][$keyone]['sub_category'][$key]['sub_category_image'] = $this->apis_models1->file_view('sub_category', $sub_cat_id, '', '','no','src');
                                    $detail_data_arr['extra_details'][$keyone]['sub_category'][$key]['sub_category_thumb_image'] = $this->apis_models1->file_view('sub_category', $sub_cat_id, '', '', 'thumb','src');
                                }
                            }
                        }
                        $detail_data_arr = $detail_data_arr['extra_details'];
                    } else {
                        $detail_data_arr = array();
                    }
                    // above code updated by adnan to display new key collection_type_detials -- end
                    array_push($collection_data['collection_image'], array(
                        "sequence" => $row['order_by_collection'],
                        "type" => $row['type'], // dynamic types
                        "collection_data" => array(
                            array(
                                "id" => $row['collection_id'],
                                "title" => $row['title'],
                                "display_name" => ($display_title) ? $row['title'] : '',
                                "is_scrollable" => (strtolower($row['is_scrollable']) == 'yes') ? 'yes' : 'no' ,
                                "sequence" => $row['order_by_collection'],
                                "collection_image" => null,
                                "total_product_records" => $collection_product_count,
                                "total_brand_records" => $collection_brand_count,
                                "display_in_columns" => $row['display_in_columns'],
                                "display_data" => $display_data,
                                "collection_type_detials" => $detail_data_arr,
                            )
                        )
                    ));
                }
                $i++;
            }
            $collection_data = $collection_data['collection_image'];
            if (empty($data)) {
                $this->errorMessage('collection_list_is_empty', $msg_data, $userLanguage);
            }

            $response['result'] = $collection_data;
            $response['total_records'] = $total_records;
            $responseData =  array(
                'data'=>$response,
            );
            $this->successMessage('data_fetched_successfully', array(), $userLanguage,$responseData);
        }
        catch (\Exception $e) {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    //FOR service_fees and delivery fees for payment flow :START
    public function getPaymentCharges(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $table_name = 'user';
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $user_card_id = $address_id = 0;
        $city_id = $area_id = 0;
        $payment_type = "";
        $cardNumber = "";

        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id,wallet_type', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            $userWalletType = $merchantExist[0]['wallet_type'];

            if (isset($_POST['payment_type']) && !empty($_POST['payment_type'])) {
                if ($_POST['payment_type'] == 'payInCash' || $_POST['payment_type'] == 'payInCard'   || 
                    $_POST['payment_type'] == 'ePaymentCard' || $_POST['payment_type'] == 'ePaymentWallet' || $_POST['payment_type'] == 'trolleyCredit') {
                    $payment_type = $_POST['payment_type'];
                    if($payment_type == 'ePaymentCard' || $payment_type == 'ePaymentWallet'){
                        if (isset($_POST['selectedCardId']) && !empty($_POST['selectedCardId'])) {
                            $user_card_id = $_POST['selectedCardId'];
                            $check_condition .= ' AND user_id = '.$this->db->escape($user_id);
                            $check_condition .= ' AND user_card_id = '.$this->db->escape($user_card_id);
                            $cardDataArray = $this->apis_models1->getData( 'user_card_id,card_name,card_number', 'user_card', $check_condition );
                            if(!is_array($cardDataArray)){
                                $this->errorMessage('no_such_card_found', $msg_data, $userLanguage);
                            }
                            $user_cardNumber = $cardDataArray[0]['card_number'];
                            $user_cardName = $cardDataArray[0]['card_name'];
                            $cardNumber = base64_decode($user_cardNumber);
                        }else{
                            $this->errorMessage('please_select_card_for_payment', $msg_data, $userLanguage);
                            exit();
                        }
                    }
                } else {
                    $this->errorMessage('invalid_payment_type', $msg_data, $userLanguage);
                    exit();
                }
            } else {
                $this->errorMessage('please_select_payment_type', $msg_data, $userLanguage);
                exit();
            }
            
            if(isset($_POST['address_id']) && !empty($_POST['address_id'])){
                $address_id = $_POST['address_id'];
                $addressCondition = ' address_id = '.$this->db->escape($address_id);
                $addressCondition .= ' AND user_id = '.$this->db->escape($user_id);
                $addressExist = $this->apis_models1->getData( 'address_id,city_id,area_id', 'user_address', $addressCondition );
                if (!is_array( $addressExist ) ) {
                   $this->errorMessage('no_such_address_found', $msg_data, $userLanguage);
                }
                $city_id = $addressExist[0]['city_id'];
                $area_id = $addressExist[0]['area_id'];
            }else{
                $this->errorMessage('please_select_address_for_delivery', $msg_data, $userLanguage);
            }
            
            //TIMESLOT CHECK :START
            $deliveryDate = date('Y-m-d');
            if(isset($_POST['delivery_date'])){
                if($_POST['delivery_date'] < $date){
                    $this->errorMessage('date_cannot_be_less_than_current_date', $msg_data, $userLanguage);
                }
                $deliveryDate = $_POST['delivery_date'];
            }else{
                $this->errorMessage('please_provide_delivery_date', $msg_data, $userLanguage);
            } 

            $checkDate =  $this->validateDate($deliveryDate);
            if(!$checkDate){
                $this->errorMessage('invalid_date', $msg_data, $userLanguage);
            }
            $timeslot = "";
            $timeslotDay =  date("l", strtotime($deliveryDate));
            $timeslots_id = 0;
            $orderLimitPerTimeslot = 0;
            if(isset($_POST['timeslots_id']) && !empty($_POST['timeslots_id'])){
                $timeslots_id = $_POST['timeslots_id'];
                $timeslot_condition = ' timeslots_id = '. $this->db->escape($timeslots_id);
                $timeslot_condition .= ' AND day  = '. $this->db->escape($timeslotDay);
                $timeslot_condition .= ' AND status = "ok" ';
                $timeslotExist = $this->apis_models1->verify_if_unique('timeslots',$timeslot_condition );
                if (!is_array($timeslotExist)) {
                    $this->errorMessage('invalid_timeslot_selection', $msg_data, $userLanguage);
                }
                $startTime = date('h:i A',strtotime($timeslotExist[0]['start_time']));
                $endTime = date('h:i A',strtotime($timeslotExist[0]['end_time']));
                $timeslot = $startTime .' - '. $endTime;
                
                $orderLimitPerTimeslot = $timeslotExist[0]['order_limit'];
            }else{
                $this->errorMessage('please_provide_delivery_timeslot', $msg_data, $userLanguage);
            }
            //TIMESLOT CHECK : END
            
            //per day timeslots order -- START
            $saleCount = $this->apis_models1->getSalesCountForTimeslots($deliveryDate,$timeslots_id);
            if($saleCount >= $orderLimitPerTimeslot){
                 $this->errorMessage('order_timeslot_limit_reached', $msg_data, $userLanguage);
            } 
            //per day timeslots order -- END
            
            
            $delivery_charge = $this->apis_models1->getDeliveryCharge($city_id,$area_id);
        if(!is_numeric($delivery_charge)){
                $this->errorMessage('no_such_address_found', $msg_data, $userLanguage);
            }
            
            
            $service_charge = $this->getServiceCharge($payment_type,$userWalletType,$cardNumber);
            //added by sagar 
            $delivery_charge = get_converted_currency($delivery_charge, $currency_code);
            
            //added by sagar : to handle service fees calculation - START 
            $sub_total = 0;
            if(isset($_POST['sub_total']) && !empty($_POST['sub_total']) && is_numeric($_POST['sub_total'])){
                $sub_total = $_POST['sub_total'];
            }
            $service_tax = ($sub_total * $service_charge)/100;
            $total_tax= 0;
            $total_tax =  round($service_tax,3);
            $currency_conversion =  $this->db->get_where('general_settings',array('type' => 'currency_conversion'))->row()->value;
            if($currency_code != DEFAULT_CURRENCY){
                if(is_numeric($currency_conversion) && !empty($currency_conversion) ){
                    $total_tax = $total_tax * $currency_conversion;
                }else{
                    $currency_conversion = 0;
                    $total_tax = $total_tax * $currency_conversion;
                }
            }
            //added by sagar : to handle service fees calculation - END
            
            //added by sagar : to handle coupon  calculation - START 
            $coupon_discount = 0;
            if(isset($_POST['coupon_code']) && !empty($_POST['coupon_code']) ){
                //Fetch Coupon D
                $coupon_code = $_POST['coupon_code'];
//                $coupon_details = $this->apis_models1->getCoupondata($coupon_code);
                $coupon_details = $this->db->select('coupon_id,discount_type,discount_value,title,code')->get_where('coupon',array('code'=>$coupon_code))->row_array();
                if(is_array($coupon_details) && !empty($coupon_details['discount_value'])){
                    $coupon_discount_actual_value = ($sub_total * $coupon_details['discount_value'])/100;
                    $coupon_discount =  round($coupon_discount_actual_value,3);
                    if($currency_code != DEFAULT_CURRENCY){
                        if(is_numeric($currency_conversion) && !empty($currency_conversion) ){
                            $coupon_discount = $coupon_discount * $currency_conversion;
                        }else{
                            $currency_conversion = 0;
                            $coupon_discount = $coupon_discount * $currency_conversion;
                        }
                    }
                }
            }
            //added by sagar : to handle coupon  calculation - END 
            
            $data = array(
                'service_charge'=>$service_charge,
                'delivery_charge'=>$delivery_charge,
                'total_tax'=>$total_tax,
                'coupon_discount'=>$coupon_discount,
            );
            $responseData = array(
                'data'   => $data,
            );
            $this->successMessage('fetched_payment_charges', $msg_data, $userLanguage,$responseData);
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
            exit();
            }
    }

    private function getServiceCharge($payment_type = "payInCash" ,$wallet_type='normal',$cardNumber=""){

        $service_charge =  0;
        $service_charge_id = 0;

        if (SERVICE_TAX_OFF) {
            return $service_charge;
        }
        if($wallet_type == 'vip'){
            $service_charge_id = 3;
        }else{
            $service_charge_id = 4;
        }
        switch ($payment_type) {
            case "payInCard":
                $service_charge_id = 2;
                $service_charge = $this->db->get_where('service_charge',array('service_charge_id'=>$service_charge_id))->row()->service_fees;
                break;
            case "ePaymentCard":
                $cardInitials = substr($cardNumber,0,6);
                $service_charge = $this->db->like('card_no', $cardInitials, 'both')->get('service_charge')->row()->service_fees;
                if(empty($service_charge)){
                  $service_charge = DEFAULT_ONLINE_CARD_FEES;  
                }
                break;
            case "ePaymentWallet":
                //change by sagar - START - 18-9
                if(strlen($cardNumber) > 9 ){
                    $$cardNumber9Digit = substr($cardNumber, -9);
                }
                $cardInitials = substr($$cardNumber9Digit,0,2);
                //change by sagar - END - 18-9
                $service_charge = $this->db->like('card_no', $cardInitials, 'both')->get('service_charge')->row()->service_fees;
                if(empty($service_charge)){
                  $service_charge = DEFAULT_ONLINE_WALLET_FEES;  
                }
                break;
            case "trolleyCredit":
                $service_charge = $this->db->get_where('service_charge',array('service_charge_id'=>$service_charge_id))->row()->service_fees;
                break;
            default:
                $service_charge_id = 1;
                $service_charge = $this->db->get_where('service_charge',array('service_charge_id'=>$service_charge_id))->row()->service_fees;

        }
        return $service_charge;
    }
    //FOR service_fees and delivery fees for payment flow :END

    //FOR Print receipt when online ePayment : START
     function printOrderReceipt() {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $orders_id = 0;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            
            if (isset($_POST['orders_id']) && !empty($_POST['orders_id'])) {
                $orders_id = $_POST['orders_id'];
                $orderData = $this->db->get_where('sale', array('sale_code' => $orders_id))->result_array();
                if (is_array($orderData) && !empty($orderData[0])) {
                    $sale_id =  $orderData[0]['sale_id'];
                    $order_status =  $orderData[0]['order_status'];
                    $paymentType =  $orderData[0]['payment_type'];
                    if($paymentType != 'ePaymentCard' &&  $paymentType != 'ePaymentWallet'){
                        $this->errorMessage('only_online_epayment_card_or_wallet_allowed_to_print_receipt', $msg_data, $userLanguage);
                    }
                    
                    $user_choice = json_decode($orderData[0]['user_choice'], true);
                    $sale_currency_conversion_rate =  $user_choice[0]['currency_conversion'];
                    
//                    $paymentResponse = $this->getRepsonse();
//                    $decodedPaymentResponse =  json_decode($paymentResponse,true);
                    
                    $decodedPaymentResponse = json_decode( $orderData[0]['payment_details'],true);
                  
                    $receiptData = array();
                    $i=0;
                    if(is_array($decodedPaymentResponse)){
                        $total_amount = $paid_amount = 0; 
                        if($decodedPaymentResponse['success'] == 1){
                            $total_amount = $decodedPaymentResponse['data']['totalAmount'];
                            //$total_amount = get_converted_currency($total_amount,$currency_code,$sale_currency_conversion_rate);
                            $paid_amount = $decodedPaymentResponse['data']['paidAmount'];
                            //$paid_amount = get_converted_currency($paid_amount,$currency_code,$sale_currency_conversion_rate);
                        }
                        
                        
                        $responseStatus = $decodedPaymentResponse['data']['responseStatus'];
                        if($userLanguage == 'ar'){
                            $responseMessage = $decodedPaymentResponse['data']['arabicResponseMessage'];
                        }else{
                            $responseMessage = $decodedPaymentResponse['data']['englishResponseMessage'];
                        }
                        
                        $transaction_id = $transaction_fees = '-';
                        if(isset($decodedPaymentResponse['data']['referenceNo'])){
                            $transaction_id = $decodedPaymentResponse['data']['referenceNo'];
                        }
                        if(isset($decodedPaymentResponse['data']['totalFees'])){
                            $transaction_fees = $decodedPaymentResponse['data']['totalFees'];
                        }
                        
                        $serviceProvider = "Trolley";
//                        if(isset($decodedPaymentResponse['data']['serviceProvider']) && !empty($decodedPaymentResponse['data']['serviceProvider'])){
//                            $serviceProvider = $decodedPaymentResponse['data']['serviceProvider'];
//                        }
                        $receiptData[$i]['time'] = $decodedPaymentResponse['data']['date'];
                        $receiptData[$i]['service_provider'] =  $serviceProvider; // "Trolley"; //Config set 
                        $receiptData[$i]['service_provider_id'] =  $decodedPaymentResponse['data']['serviceProviderId'];  //"352"; //Config set 
                        $receiptData[$i]['PAN'] = $decodedPaymentResponse['data']['PAN'];
                        $receiptData[$i]['total_amount'] = $total_amount;
                        $receiptData[$i]['paid_amount'] = $paid_amount;
                        $receiptData[$i]['transaction_fees'] = $transaction_fees;
                        $receiptData[$i]['status'] = $responseStatus;
                        $receiptData[$i]['message'] = $responseMessage;
                        $receiptData[$i]['transaction_id'] = $transaction_id;
                        $receiptData[$i]['currency_code'] = $currency_code;
                    }
                    $responseData = array(
                        'data'=>$receiptData
                    );
                    $this->successMessage('fetch_print_receipt_data', $msg_data, $userLanguage,$responseData);
                }else{
                    $this->errorMessage('no_such_order_id_found', $msg_data, $userLanguage);
                }
            }else{
                $this->errorMessage('provide_order_id_to_print_receipt', $msg_data, $userLanguage);
            }
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }    
    }
    //FOR Print receipt when online ePayment : END

    //FOR TROLLEY ADD MONEY AND TRANSACTION LISTING : START
    function addMoneyToWallet(){
         $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        $isHostCall = true;
        $discount = 0;
        $uuid= $sale_id = "";
        $isPaymentDone = false;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
 
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            
            $type_lebel = '_wallet_';
            $type="wallet";
            if(isset($_POST['type']) && !empty($_POST['type'])){
                $type = $_POST['type'];
                if($type != 'paymentCard' && $type != 'wallet'){
                    $this->errorMessage('invalid_card_type', $msg_data, $userLanguage);
                }
                if($type == 'paymentCard'){
                    $type_lebel = '_card_';
                }
            }else{
                $this->errorMessage('please_provide_card_type', $msg_data, $userLanguage);
            }
            
            $cardNumber = $cardPin = $maskCardNumber = "";
       
            if (isset($_POST['cardId']) && !empty($_POST['cardId'])) {
                $userCardId = $_POST['cardId'];
                $cardcondition = '  user_id = '.$this->db->escape($user_id);
                $cardcondition .= ' AND user_card_id = '.$this->db->escape($userCardId);
                $cardcondition .= ' AND type = '.$this->db->escape($type);
                $cardDataArray = $this->apis_models1->getData( 'user_card_id,card_name,card_number', 'user_card', $cardcondition );
                if(!is_array($cardDataArray)){
                    $msg = "no_such".$type_lebel."found";
                    $this->errorMessage($msg, $msg_data, $userLanguage);
                    //$this->errorMessage('no_such_card_found', $msg_data, $userLanguage);
                }
        $user_card_id= $cardDataArray[0]['user_card_id'];
                $user_cardNumber = $cardDataArray[0]['card_number'];
                $cardNumber = base64_decode($user_cardNumber);
                $maskCardNumber = maskCardNumber($cardNumber);
            }else{
                $msg = "please_select".$type_lebel."for_payment";
                $this->errorMessage($msg, $msg_data, $userLanguage);
//                $this->errorMessage('please_select_wallet_for_payment', $msg_data, $userLanguage);
            }

            if (isset($_POST['cardPin']) && !empty($_POST['cardPin'])) {
                $cardPin = $_POST['cardPin'];
            }else{
                $msg = "please_enter".$type_lebel."pin";
                $this->errorMessage($msg, $msg_data, $userLanguage);
//                $this->errorMessage('plese_enter_card_pin', $msg_data, $userLanguage);
                exit();
            }
            
            $amount = 0;
            if(isset($_POST['amount']) && !empty($_POST['amount'])){
                $amount = $_POST['amount'];
                if($amount <= 0){
                    $this->errorMessage('amount_should_be_greater_than_0', $msg_data, $userLanguage);
                }
                $amount = round($amount,2);
            }else{
                $this->errorMessage('please_enter_amount_to_add_in_trolley_wallet', $msg_data, $userLanguage);
            }
            
            //HOST CALL -- for add money from card or wallet will be here
            if($isHostCall){
                $db_wallet_balance =  $this->db->get_where('user',array('user_id'=>$user_id))->row()->wallet_balance;
                $update_wallet_balance = $db_wallet_balance + $amount;
                
                $walletData = array(
                    'user_id'=>$user_id,
                    'amount'=>$amount,
                    'type'=>'credit',
                    'reason'=> 'added by self',
                    'date_time'=>date('Y-m-d H:i:s'),
                    'wallet_balance'=>$update_wallet_balance,
                    
                );
                if($type == 'wallet'){
                    $walletData['reason'] = 'Added '.$amount. ' ' . DEFAULT_CURRENCY_NAME .' from wallet '.$cardNumber;
                }else{
                    $walletData['reason'] = 'Added '.$amount.' '. DEFAULT_CURRENCY_NAME .'from card '.$maskCardNumber;
                }
                
                
    //added by Ritesh : Start
                $paymentRespone = $this->processTransaction($user_card_id,$user_id,$cardPin,$amount,$uuid,$userLanguage,$sale_id);
                $response_array = json_decode($paymentRespone,true);
        //echo "<pre BY Ritesh after EBS Call>";
                //print_r($response_array);exit;
                if(is_array($response_array)){
                    $ebs_response_code = $response_array['success'];
                    if($ebs_response_code == 1){
                        $isPaymentDone = true;
                    }
                }
                if($isPaymentDone){
                    $this->db->insert('wallet', $walletData);

                    $update_wallet_balance =  array(
                        'wallet_balance' => $update_wallet_balance,
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('user', $update_wallet_balance);

                    $this->successMessage('amount_added_in_trolley_wallet', $msg_data, $userLanguage);
                }else{
                     $this->errorMessage('transaction_failed', $msg_data, $userLanguage);
                }
                
                 //added bY rItesh end
            }else{
                $this->errorMessage('transaction_failed', $msg_data, $userLanguage);
            }
            
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    
    function fetchWalletTransaction(){
         $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $msg_data = array();
        $table = 'wallet';
        $limit = 10;
        $page = 0;
        $sortby = " wallet_id ";
        $order = " DESC ";
        $check_condition = " 1=1 And status='Active' ";
        $userLanguage = 'ar';
        $currency_code = DEFAULT_CURRENCY;
        //User choice Currency_code and userLanguage  : START
        if (isset($_POST['currency_code']) && !empty($_POST['currency_code'])) {
            $currency_code = $_POST['currency_code'];
        }
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        //User choice Currency_code and userLanguage  : END
        $token_array = $this->read_header_token($userLanguage);
        if($this->read_header() && is_array($token_array)){
            $user_id = $token_array['uid'];
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $merchantExist = $this->apis_models1->getData( 'user_id,wallet_balance', 'user', $check_condition );
            if ( !is_array( $merchantExist ) ) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            
            $wallet_balance = $merchantExist[0]['wallet_balance'];
            $wallet_balance = round($wallet_balance,2);
            if(isset($_POST['page_no']) && !empty($_POST['page_no'])){
                $page=$_POST['page_no'];
            }
            if(isset($_POST['limit']) && !empty($_POST['limit']) ){
                $limit=$_POST['limit'];
            }
            $walletCondition ='  user_id = '.$this->db->escape($user_id); 
            $resultdata  = $this->apis_models1->fetchedLimitedData($table, $walletCondition,$sortby,$order,$limit,$page);
            if(is_array($resultdata) && count($resultdata)> 0){
                foreach($resultdata as $key=>$val){
                    $final_array[$key]['wallet_id']=$val['wallet_id'];
                    $final_array[$key]['user_id']=$val['user_id'];
                    $final_array[$key]['amount']=round($val['amount'],2);
                    $final_array[$key]['reason']=$val['reason'];
                    $final_array[$key]['type']=$val['type'];
                    $final_array[$key]['date_time']=$val['date_time'];
                } 
                $responseData = array(
                    'data'=>$final_array,
                    'current_wallet_balance'=>$wallet_balance,
                );
               $this->successMessage('fetched_trolley_wallet_transaction_list',$msg_data, $userLanguage,$responseData);
            }else{
               $this->errorMessage('empty_transaction_list', $msg_data, $userLanguage);
            }
            
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    //FOR TROLLEY ADD MONEY AND TRANSACTION LISTING : END
    
    private function processTransactionForWallet(){
        // Not Clear -- 
        return false;
    }

    public function refreshFcmToken() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $token_array = $this->read_header_token();
        $currency_code = DEFAULT_CURRENCY;
        $userLanguage = "ar";
        $fcm_id = "";

        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
            if (isset($_POST['fcm_id']) && !empty($_POST['fcm_id'])) {
                $fcm_id = $_POST['fcm_id'];
            }
            $data_array = array('fcm_id' => $fcm_id);
            $check_condition = 'user_id = ' . $this->db->escape($user_id);
            $this->apis_models1->updateRecord('user', $data_array, $check_condition);
            echo json_encode(array('success' => 1, 'message' => 'FCM Token Refresh Successfully.'));
            exit();
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    private function generateRandomString() {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < 20; ++$i) {
            $str .= $keyspace[rand(0, $max)];
        }
        return $str;
    }

////-------------------------Dummy Flows -------------------------///
    
    private function insertStockData() {
        $product_details = $this->crud_model->get_data('variation', '1=1', $select = '*');
        foreach ($product_details as $key => $val) {
            $current_stock = 0;
            //$addStockvalue = 10000;
            $stock_data['type'] = 'add';
            $stock_data['product'] = $val['product_id'];
            $stock_data['variation_id'] = $val['variation_id'];
            $stock_data['category'] = $this->crud_model->get_type_name_by_id('product', $val['product_id'], 'category');
            $stock_data['sub_category'] = $this->crud_model->get_type_name_by_id('product', $val['product_id'], 'sub_category');
            $stock_data['quantity'] = $addStockvalue;
            $stock_data['total'] = 0;
            $stock_data['reason_note'] = 'Added on Initial Insert';
            $stock_data['sale_id'] = 0;
            $stock_data['datetime'] = time();
            //$this->db->insert('stock', $stock_data);
            exit;
            echo "<br> ||| Update ID = ".$val['variation_id'];
            $this->db->where('variation_id',$val['variation_id']);
            $this->db->update('variation', array(
                'current_stock' => $addStockvalue,
            ));
        }
    }

    function phpInfo(){
        phpinfo();
    }

    function addSuggestedProducts(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $product_name = "";
        $userLanguage = "ar";
        $msg_data= array();
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        $token_array = $this->read_header_token($userLanguage);
        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
            
            if (isset($_POST['product_name']) && !empty($_POST['product_name'])) {
                $product_name = $_POST['product_name'];
            } else {
                $this->errorMessage('please_enter_product_name', $msg_data, $userLanguage);
            }

            $data_array = array(
                'from' => $user_id,
                'product_name' => $product_name,
                'status' => '1',
            );
                // print_r($data_array);exit;
            $this->apis_models1->insertData('suggested_products', $data_array, 1);
            $this->successMessage('suggestion_saved_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('login_to_add_suggestions', $msg_data, $userLanguage);
        }
    }

    function getBanner(){
        $data = json_decode( file_get_contents( 'php://input' ), true );
        if ( isset( $data ) && ! empty( $data ) ) {
                $_POST = $data;
        }
        $userLanguage = 'ar';
        $msg_data = array();

        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];

        }
        $slides_id = 0;
        if($this->read_header()){
        //Banner array
            $con1 = 'status = "ok" ';
            $con1 .= ' And slides_lang = ' . $this->db->escape($userLanguage);
            $data_array = $this->apis_models1->getData('slides_id as id,button_link as slide_image', 'slides', $con1);

            if (is_array($data_array)) {
                foreach ($data_array as $key => $val) {
                    if (isset($val['slide_image']) && !empty($val['slide_image'])) {
                        if (file_exists('uploads/slides_image/'.$val['slide_image'])) {
                            $data_array[$key]['image'] = base_url() . 'uploads/slides_image/'.$val['slide_image'].'?d='.refreshedImage();
                        } else {
                            $data_array[$key]['image'] = base_url() . 'uploads/slides_image/default.jpg?d='.refreshedImage();
                        }
                    } else {
                        $data_array[$key]['image'] = base_url() . 'uploads/slides_image/default.jpg?d='.refreshedImage();
                    }
                }
            }

            if(is_array($data_array) && !empty($data_array)){
               $responseData = array(
                    'data'   => $data_array,
               );
                $this->successMessage('returned_slides_data', $msg_data, $userLanguage,$responseData);
            }else{
                $this->errorMessage('empty_list_criteria', $msg_data, $userLanguage,array());
            }
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    function deleteAccount(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $userLanguage = "ar";
        $msg_data= array();
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $token_array = $this->read_header_token($userLanguage);
        
        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
          
            $currentDate = date('Y-m-d');
            $data_array = array(
                'deleted_at' => $currentDate
            );
            $check_condition = 'user_id = ' . $this->db->escape($user_id);
            $this->apis_models1->updateRecord('user', $data_array, $check_condition);
            $this->successMessage('account_deleted_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    //added by Adnan 05-07-2023 : Start
    public function viewCustomerProfile(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }

        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $msg_data = array();
        $check_condition = " 1=1 And status='Active'";
        $token_array = $this->read_header_token($userLanguage);
        if ($this->read_header() && is_array($token_array)) {
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $user_details = $this->apis_models1->getData('first_name,fourth_name,phone,email,sex,job_type,social_status', 'user', $check_condition);
            
            if(!is_array($user_details[0])){
                 $this->errorMessage('user_not_found', $msg_data, $userLanguage);
             }
 
            $data = array();
            if(is_array($user_details[0])){
                $data['first_name'] = $user_details[0]['first_name'];
                $data['fourth_name'] = $user_details[0]['fourth_name'];
                $data['email'] = $user_details[0]['email'];
                $data['phone'] = $user_details[0]['phone'];
                $data['job_type'] = $user_details[0]['job_type'];
                $data['sex'] = $user_details[0]['sex'];
                $data['social_status'] = $user_details[0]['social_status'];
                $data['image'] = base_url() . 'uploads/user_image/default.jpg?d=' . refreshedImage();
                $image_files = glob('uploads/user_image/user_' . $token_array['uid'] . '.*');

                if (!empty($image_files)) {
                    $image_path = $image_files[0];
                    $image_extension = pathinfo($image_path, PATHINFO_EXTENSION);
                    $data['image'] = base_url() . $image_path . '?d=' . refreshedImage();
                }
            }
                    
            $responseData =  array(
                'data'=>$data,
            );
            $this->successMessage('data_fetched_successfully', $msg_data, $userLanguage,$responseData);
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function updateCustomerProfile(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
            $first_name = $_POST['first_name'];
        }
        if (isset($_POST['fourth_name']) && !empty($_POST['fourth_name'])) {
            $fourth_name = $_POST['fourth_name'];
        }
        if (isset($_POST['job_type']) && !empty($_POST['job_type'])) {
            $job_type = $_POST['job_type'];
        }
        if (isset($_POST['sex']) && !empty($_POST['sex'])) {
            $sex = $_POST['sex'];
        }
        if (isset($_POST['social_status']) && !empty($_POST['social_status'])) {
            $social_status = $_POST['social_status'];
        }
        // Image Upload
        $image_path = '';
        $msg_data = array();
        $check_condition = " 1=1 And status='Active'";
        $token_array = $this->read_header_token($userLanguage);
        if ($this->read_header() && is_array($token_array)) {
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $user_details = $this->apis_models1->getData('first_name', 'user', $check_condition);

            if(!is_array($user_details[0])) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            if (!empty($_FILES['profile_image']['name'])) {
                $image_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                // allowed extension jpg, jpeg, png
                if (in_array($image_extension, ALLOWED_EXTENSIONS)) {
                    $image_path = 'uploads/user_image/user_' . $token_array['uid'] . '.' . $image_extension;
                    // Delete previous image if it exists
                    $previous_image_files = glob('uploads/user_image/user_' . $token_array['uid'] . '.*');
                    foreach ($previous_image_files as $previous_image_file) {
                        unlink($previous_image_file);
                    }
                    // Move the uploaded image to the desired path
                    move_uploaded_file($_FILES['profile_image']['tmp_name'], $image_path);
                } else {
                    // Handle invalid file type
                    $this->errorMessage('invalid_file_type', $msg_data, $userLanguage);
                }
            }
                         
            $data_array = array(
                'first_name' => $first_name,
                'fourth_name' => $fourth_name,
                'job_type' => $job_type,
                'sex' => $sex,
                'social_status' => $social_status,
            );
            $this->apis_models1->updateRecord('user', $data_array, $check_condition);

            $this->successMessage('user_info_update_successfully', $msg_data, $userLanguage);
        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    //added by Adnan 05-07-2023 : End
    
    //added by Adnan 06-07-2023 : Start
    public function orderAgain(){
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        if (isset($_POST['subCategoryId']) && !empty($_POST['subCategoryId']) && (is_numeric($_POST['subCategoryId']))) {
            $subCategoryId = $_POST['subCategoryId'];
        }
        

        $msg_data = array();
        $check_condition = " 1=1 And status='Active'";
        $token_array = $this->read_header_token($userLanguage);
        $user_id = $token_array['uid'] ?? 0;

        if ($this->read_header() && is_array($token_array)) {
            $check_condition .= ' AND user_id = '.$this->db->escape($token_array['uid']);
            $user_details = $this->apis_models1->getData('first_name', 'user', $check_condition);

            if(!is_array($user_details[0])) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            $product_condition = " 1=1 And buyer = " . $this->db->escape($token_array['uid']);

            $product_details = $this->apis_models1->getData('product_details', 'sale', $product_condition);
            // Assuming $array contains the provided array
            $productIds = [];

            foreach ($product_details as $level) {
                $productDetails = json_decode($level['product_details'], true);
                
                // Access individual product details
                foreach ($productDetails as $product) {
                    $productId = $product['product_id'];
                    
                    // Add the product ID to the $productIds array
                    $productIds[] = $productId;
                }
            }
            
            // Count the occurrences of each product ID
            $productCounts = array_count_values($productIds);
            
            // Create a new array with product IDs as keys and counts as values
            $topProductIds = [];
            foreach ($productCounts as $productId => $count) {
                $topProductIds[$productId] = $count;
            }
            arsort($topProductIds);
            $product_id_arr = array_keys($topProductIds);

            if (!empty($product_id_arr)) {
                $this->db->select('product_id,title,title_ar,weight,unit,discount,discount_type,sale_price,purchase_price');
                if(!empty($subCategoryId)){
                    $this->db->where('sub_category',$subCategoryId);
                }
                $this->db->where_in('product_id', $product_id_arr);
                // // $this->db->order_by('FIELD(product_id, ' . implode(',', $product_id_arr) . ')');
                $data = $this->db->get('product')->result_array();
                
                foreach ($data as $key => $value) {

                    $productID = $value['product_id'];
                    $imageNumber = 1;
        
                    $variation = $this->db->get_where('variation', array('product_id' => $productID))->row_array();
                    $cart = $this->db->get_where('cart', array('user_id'=>$user_id , 'cart_session_id' => md5($this->device_id) , 'product_id' => $productID))->row_array();
                    
                    $data[$key]['discount'] = (int)$value['discount'];
                    $data[$key]['sale_price'] = get_converted_currency((double)$value['sale_price'],DEFAULT_CURRENCY);
                    $data[$key]['purchase_price'] = get_converted_currency((double)$value['purchase_price'],DEFAULT_CURRENCY);
                    $data[$key]['InCartFlag'] = 0;
                    if(!empty($cart) && is_array($cart)){
                        $data[$key]['InCartFlag'] = 1;
                        $data[$key]['cart_id'] = $cart['cart_id'];    
                        $data[$key]['qty'] = $cart['qty'];
                    }
                    $data[$key]['variation_id'] = $variation['variation_id'];
                    $data[$key]['variation_stock'] = $variation['current_stock'];
                    //below calculation part is commented because of the purchase price is now being set by admin --adnan
                    // if($data[$key]['discount'] > 0){
                    //     $data[$key]['purchase_price'] = (double)$value['sale_price'] - ((double)$value['sale_price']*(int)$value['discount']/100);
                    // }else{
                    //     $data[$key]['purchase_price'] = $data[$key]['sale_price'];
                    // }
                    $data[$key]['image'] = base_url() . 'uploads/product_image/default.jpg?d=' . refreshedImage();
                    $image_files = glob('uploads/product_image/product_'.$productID.'_'.$imageNumber .'.*');
                    // checks for the product image present on the zero index to display first image in the cards --adnan
                    if (!empty($image_files)) {
                        $image_path = $image_files[0];
                        $image_extension = pathinfo($image_path, PATHINFO_EXTENSION);
                        $data[$key]['image'] = base_url() . $image_path . '?d=' . refreshedImage();
                    }
                }
                
                $this->db->select('sub_category');
                $this->db->where_in('product_id', $product_id_arr);
                $sub_category_ids_from_products = $this->db->get('product')->result();
                $sub_category_ids = array_column($sub_category_ids_from_products, 'sub_category');
                $sub_category_ids = array_unique($sub_category_ids);

                $this->db->select('sub_category_id,sub_category_name,sub_category_name_ar,banner');
                $this->db->where_in('sub_category_id', $sub_category_ids);
                $sub_category_data = $this->db->get('sub_category')->result_array();
                foreach ($sub_category_data as $key => $value) {
                    $sub_cat_id = (int)$value['sub_category_id'];
                    $sub_category_data[$key]['sub_category_id'] = $sub_cat_id;
                    $sub_category_data[$key]['sub_category_name'] = $value['sub_category_name'];
                    // sub_category_1.jpg
                    $sub_category_data[$key]['image'] = base_url() . 'uploads/sub_category_image/default.jpg?d=' . refreshedImage();
                    $image_files = glob('uploads/sub_category_image/sub_category_'.$sub_cat_id.'.*');
                    // checks for the product image present on the zero index to display first image in the cards --adnan
                    if (!empty($image_files)) {
                        $image_path = $image_files[0];
                        $image_extension = pathinfo($image_path, PATHINFO_EXTENSION);
                        $sub_category_data[$key]['image'] = base_url() . $image_path . '?d=' . refreshedImage();
                    }

                }
                
                $mainData = array();
                $mainData['subCateogry'] = $sub_category_data;
                $mainData['buyAgain'] = $data;

                $responseData = array(
                    'data' => array($mainData),
                );

                $this->successMessage('data_fetched_successfully', $msg_data, $userLanguage, $responseData);
            } else {
                $this->errorMessage('empty_order_list', $msg_data, $userLanguage);
            }

        }else{
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    //added by Adnan 05-07-2023 : End

    //added by Adnan 10-07-2023 : Start
    public function similarProducts()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $userLanguage = "ar";
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $msg_data = array();
        $token_array = $this->read_header_token($userLanguage);
        $user_id = $token_array['uid'] ?? 0;

        if (isset($_POST['product_id']) && !empty($_POST['product_id']) && is_numeric($_POST['product_id'])) {
            $product_id = $_POST['product_id'];
    
            // Fetch the similar products based on the given product_id
            $this->db->select('similar_products');
            $this->db->where(array('product_id' => $product_id, 'status' => 'ok'));
            $query = $this->db->get('product');

            if ($query->num_rows() > 0 && !empty($query->row()->similar_products)) {
                $row = $query->row();
                $similar_products_csv = $row->similar_products;
    
                $similar_products = explode(',', $similar_products_csv);
                $this->db->select('product_id,title,title_ar,weight,unit,discount,discount_type,sale_price,purchase_price');
                $this->db->where_in('product_id', $similar_products);
                $this->db->where('status', 'ok');
                
                $data = $this->db->get('product')->result_array();

                foreach ($data as $key => $value) {

                    $productID = $value['product_id'];
                    $imageNumber = 1;
        
                    $variation = $this->db->get_where('variation', array('product_id' => $productID))->row_array();
                    $cart = $this->db->get_where('cart', array('user_id'=>$user_id , 'cart_session_id' => md5($this->device_id) , 'product_id' => $productID))->row_array();
                    $data[$key]['discount'] = (int)$value['discount'];
                    $data[$key]['sale_price'] = get_converted_currency((double)$value['sale_price'],DEFAULT_CURRENCY);
                    $data[$key]['purchase_price'] = get_converted_currency((double)$value['purchase_price'],DEFAULT_CURRENCY);
                    $data[$key]['InCartFlag'] = 0;
                    if(!empty($cart) && is_array($cart)){
                        $data[$key]['InCartFlag'] = 1;
                        $data[$key]['cart_id'] = $cart['cart_id'];    
                        $data[$key]['qty'] = $cart['qty'];
                    }
                    $data[$key]['variation_id'] = $variation['variation_id'];
                    $data[$key]['variation_stock'] = $variation['current_stock'];
                    //below calculation part is commented because of the purchase price is now being set by admin --adnan
                    // if($data[$key]['discount'] > 0){
                    //     $data[$key]['purchase_price'] = (double)$value['sale_price'] - ((double)$value['sale_price']*(int)$value['discount']/100);
                    // }else{
                    //     $data[$key]['purchase_price'] = $data[$key]['sale_price'];
                    // }
                    $data[$key]['image'] = base_url() . 'uploads/product_image/default.jpg?d=' . refreshedImage();
                    $image_files = glob('uploads/product_image/product_'.$productID.'_'.$imageNumber .'.*');
                    // checks for the product image present on the zero index to display first image in the cards --adnan
                    if (!empty($image_files)) {
                        $image_path = $image_files[0];
                        $image_extension = pathinfo($image_path, PATHINFO_EXTENSION);
                        $data[$key]['image'] = base_url() . $image_path . '?d=' . refreshedImage();
                    }
                }                
                $responseData = array(
                    'data' => $data,
                );
                
                $this->successMessage('data_fetched_successfully', $msg_data, $userLanguage, $responseData);
            } else {
                $this->errorMessage('no_similar_product_found', $msg_data, $userLanguage);
            }
        } else {
            $this->errorMessage('no_such_product_found', $msg_data, $userLanguage);
        }
    }
    //added by Adnan 10-07-2023 : End

    //added by Arjun 03-07-2023 : Start
    public function logout() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data) && !empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $userLanguage = "ar";
        $msg_data= array();
        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $token_array = $this->read_header_token($userLanguage);
        
        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];
            $updateArray = array(
                'access_token' => null, // Set the access token to null or an empty value
            );
            $tokenCondition = 'user_id = ' . $this->db->escape($user_id);
            $this->apis_models1->updateRecord('user', $updateArray, $tokenCondition);
            $this->successMessage('logout_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
    //added by Arjun 03-07-2023 : End

    public function footers()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userLanguage = "ar";

        if (!empty($data)) {
            $_POST = $data;
        }

        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $response = array();
        $footers = $this->apis_models1->appPageData();

        if (is_array($footers) && !empty($footers)) {
            foreach ($footers as $key => $value) {
                $response[$key]['type'] = $value['type'];
                $response[$key]['language'] = $value['language'];

                if ($value['type'] == 'about_us_en' || $value['type'] == 'about_us_ar') {
                    $response[$key]['name'] = 'About Us';
                }
                if ($value['type'] == 'terms_conditions_en' || $value['type'] == 'terms_conditions_ar') {
                    $response[$key]['name'] = 'Terms And Condition';
                }
                if ($value['type'] == 'privacy_policy_en' || $value['type'] == 'privacy_policy_ar') {
                    $response[$key]['name'] = 'Privacy Policy';
                }
                $response[$key]['description'] = $value['value'];
            }
        }
        $responseData = array(
            'data' => $response,
        );
        $this->successMessage('data_fetched_successfully', array(), $userLanguage,$responseData);
    }

    public function getContactDetails()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $userLanguage = "ar";

        if (!empty($data)) {
            $_POST = $data;
        }

        if (isset($_POST['userLanguage']) && !empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $types = array('contact_phone', 'contact_email');
        $this->db->select('type, value');
        $this->db->where_in('type', $types);
        $data = $this->db->get('general_settings')->result_array();
        $responseData = array(
            'data' => $data
        );
        $this->successMessage('data_fetched_successfully', array(), $userLanguage,$responseData);
    }

    public function saveContactDetails()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $_POST = $data;
        }
        $header = $this->read_header();
        $name = $email = $phone = $reason = "";
        $userLanguage = "ar";
        $msg_data = array();
        $token_array = $this->read_header_token($userLanguage);

        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];

            if (!empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
            }
            if (!empty($_POST['name'])) {
                $name = $_POST['name'];
            } else {
                $this->errorMessage('please_enter_your_name', $msg_data, $userLanguage);
            }

            if (!empty($_POST['email'])) {
                $email = strtolower($_POST['email']);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->errorMessage('please_enter_valid_email_id', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_email_id', $msg_data, $userLanguage);
            }

            if (!empty($_POST['phone'])) {
                if (( preg_match($this->mobile_check, $_POST['phone']))) {
                    $phone = $_POST['phone'];
                } else {
                    $this->errorMessage('please_enter_valid_phone_no', $msg_data, $userLanguage);
                }
            } else {
                $this->errorMessage('please_enter_phone_no', $msg_data, $userLanguage);
            }

            if (!empty($_POST['reason'])) {
                $reason = $_POST['reason'];
            } else {
                $this->errorMessage('please_enter_text_in_msg_box', $msg_data, $userLanguage);
            }
            $data_array = array(
                'name' => $name,
                'mobile' => $phone,
                'email' => $email,
                'msg' => $reason,
                'created_on' => date('Y-m-d h:i:s'),
            );
            $this->apis_models1->insertData('enquiry', $data_array, 1);
            $this->successMessage('enquired_successfully', $msg_data, $userLanguage);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function profile_options()
    {
        $header = $this->read_header();
        $userLanguage = "ar";
        $currency_code = DEFAULT_CURRENCY;
        $msg_data = array();
        $token_array = $this->read_header_token($userLanguage);

        if ($header && is_array($token_array)) {
            $user_id = $token_array['uid'];

            if (!empty($_POST['userLanguage'])) {
                $userLanguage = $_POST['userLanguage'];
            }

            $data_array = array(
                'result' => PROFILE_ACTIONS
            );
            $this->successMessage('data_fetched_successfully', $msg_data, $userLanguage, $data_array);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    function updateFcmId()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $_POST = $data;
        }
        $userLanguage = 'ar';

        if (!empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $token_array = $this->read_header_token($userLanguage);
        $check_condition = " 1=1 and status='Active' ";
        $msg_data = array();

        if ($this->read_header() && is_array($token_array)) {
            $device_id = !empty($_POST['device_id']) ? $_POST['device_id'] : $this->device_id;
            $user_id =  $token_array['uid'];
            $check_condition .= ' AND user_id = ' . $this->db->escape($user_id);
            $user_exist = $this->apis_models1->getData('user_id', 'user', $check_condition);

            if (empty($user_exist)) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            $data_array['user_id'] = $user_id;

            if (!empty($_POST['fcm_id'])) {
                $data_array['fcm_id'] =  $_POST['fcm_id'];
            } else {
                $this->errorMessage('please_enter_fcm_id', $msg_data, $userLanguage);
            }
            $data_array['device_id'] =  $device_id;
            $data_array['datetime'] = date('Y-m-d H:i:s');

            $user_fcm_count = $this->db->get_where('user_fcm', array('user_id' => $user_id, 'device_id' => $device_id))->num_rows();

            if ($user_fcm_count == 0) {
                $this->apis_models1->insertData('user_fcm', $data_array);
            } else {
                $unique_condition = 'user_id = ' . $this->db->escape($user_id);
                $unique_condition .= ' AND device_id = ' . $this->db->escape($device_id);
                $is_updated = $this->changeDataInDB('user_fcm', $operation, $data_array, $unique_condition);
            }
            $this->successMessage('data_saved_successfully', $msg_data, $userLanguage, $responseData);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }

    public function saveRating()
    {
        $msg_data = array();
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data)) {
            $_POST = $data;
        }
        $userLanguage = 'ar';

        if (!empty($_POST['userLanguage']) && $_POST['userLanguage'] == 'en') {
            $userLanguage = $_POST['userLanguage'];
        }
        $token_array = $this->read_header_token($userLanguage);

        if ($this->read_header() && is_array($token_array)) {
            $user_id =  $token_array['uid'];
            $data_array['user_id'] = $user_id;

            $check_condition = 'user_id = ' . $this->db->escape($user_id);
            $user_exist = $this->apis_models1->getData('user_id', 'user', $check_condition);

            if (empty($user_exist)) {
                $this->errorMessage('user_not_found', $msg_data, $userLanguage);
            }
            if (!empty($_POST['order_id'])) {
                $data_array['order_id'] =  $_POST['order_id'];
            } else {
                $this->errorMessage('please_enter_order_id', $msg_data, $userLanguage);
            }
            if (!empty($_POST['rating'])) {
                $data_array['rating'] =  $_POST['rating'];
            } else {
                $this->errorMessage('please_enter_order_rating', $msg_data, $userLanguage);
            }
            $review_exist = $this->db->get_where('reviews', array('user_id' => $user_id, 'order_id' => $data_array['order_id']))->num_rows();

            if ($review_exist > 0) {
                $this->errorMessage('review_already_submitted', $msg_data, $userLanguage);
            }
            $this->db->insert('reviews', $data_array);
            $this->successMessage('data_saved_successfully', $msg_data, $userLanguage, $responseData);
        } else {
            $this->errorMessage('authentication_failed', $msg_data, $userLanguage);
        }
    }
}
