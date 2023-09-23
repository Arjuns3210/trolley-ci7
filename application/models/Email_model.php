<?php
if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}


class Email_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
        
        //this function called when admin team approve b2b customer 
        function account_approval($user_id=''){
            $CI =& get_instance();
            $CI->load->model( 'crud_model' );
            $from_name = $this->db->get_where( 'general_settings', array( 'type' => 'system_name' ) )->row()->value;
            $protocol  = $this->db->get_where( 'general_settings', array( 'type' => 'mail_status' ) )->row()->value;
            if ( $protocol == 'smtp' ) {
                    $from = $this->db->get_where( 'general_settings', array( 'type' => 'smtp_user' ) )->row()->value;
            } else if ( $protocol == 'mail' ) {
                    $from = $this->db->get_where( 'general_settings', array( 'type' => 'system_email' ) )->row()->value;
            }
            $username =  $CI->crud_model->get_type_name_by_id( 'user', $user_id, 'username' );
         
            $email_body = $this->db->get_where( 'email_template', array( 'subject' => 'registration approval') )->row()->body;
            if(!empty($email_body)){
                $email_body = str_replace( '[[to]]', $username, $email_body );
                $email_body = str_replace( '[[from]]', $amount, $email_body );
                $to = $CI->crud_model->get_type_name_by_id( 'user', $user_id, 'email' );
                $subject = $sub = $this->db->get_where( 'email_template', array( 'email_template_id' => 6 ) )->row()->subject;
                $send_mail = $this->do_email( $from, $from_name, $to, $subject, $email_body );
                return $send_mail;
            }else{
                return false;
            }
        }
        
        function authenticate_b2c_account($user_id='',$userLanguage='en'){
            
            $CI =& get_instance();
            $CI->load->model( 'crud_model' );
//            $CI->load->library('mcrypt');
            
//            $string =  $user_id.'|'.date('Y-m-d', strtotime('+1 year'));
//            $encoded_string = $CI->mcrypt->encrypt($string);
            
            $random = generateRandomString();
            $string =  $random.'------pqrst)))))'.$user_id.'|'.date('Y-m-d', strtotime('+1 year'));
            $encoded_string = base64_encode($string);
            $encoded_string=str_replace('=','',$encoded_string);
            
            $from_name = $this->db->get_where( 'general_settings', array( 'type' => 'system_name' ) )->row()->value;
            $protocol  = $this->db->get_where( 'general_settings', array( 'type' => 'mail_status' ) )->row()->value;
            if ( $protocol == 'smtp' ) {
                    $from = $this->db->get_where( 'general_settings', array( 'type' => 'smtp_user' ) )->row()->value;
            } else if ( $protocol == 'mail' ) {
                    $from = $this->db->get_where( 'general_settings', array( 'type' => 'system_email' ) )->row()->value;
            }
            $username =  $CI->crud_model->get_type_name_by_id( 'user', $user_id, 'username' );
         
            if($userLanguage == 'fr'){
                $email_body = $this->db->get_where( 'email_template', array( 'subject' => 'User Authentication','language'=>'fr') )->row()->body;
            }else{    
                $email_body = $this->db->get_where( 'email_template', array( 'subject' => 'User Authentication','language'=>'en') )->row()->body;
            }
            if(!empty($email_body)){
                $url     = base_url() . "userAuthentication/checkUser/$encoded_string";
                $email_body = str_replace( '[[to]]', $username, $email_body );
                $email_body = str_replace( '[[from]]', $from_name, $email_body );
                $email_body = str_replace( '[[url]]', $url, $email_body );
                $to = $CI->crud_model->get_type_name_by_id( 'user', $user_id, 'email' );
                $subject = $sub = $this->db->get_where( 'email_template', array( 'email_template_id' => 7 ) )->row()->subject;
                $send_mail = $this->do_email( $from, $from_name, $to, $subject, $email_body );
                return $send_mail;
            }else{
                return false;
            }
        }
        
	function password_reset_email( $account_type = '', $id = '', $url = '' ,$userLanguage = 'en') {
		//$this->load->database();
            
		$from_name = $this->db->get_where( 'general_settings', array( 'type' => 'system_name' ) )->row()->value;
		$protocol  = $this->db->get_where( 'general_settings', array( 'type' => 'mail_status' ) )->row()->value;
		if ( $protocol == 'smtp' ) {
			$from = $this->db->get_where( 'general_settings', array( 'type' => 'smtp_user' ) )->row()->value;
		} else if ( $protocol == 'mail' ) {
			$from = $this->db->get_where( 'general_settings', array( 'type' => 'system_email' ) )->row()->value;
		}

		$query = $this->db->get_where( $account_type, array( $account_type . '_id' => $id ) );
		if ( $query->num_rows() > 0 ) {

			$sub = $this->db->get_where( 'email_template', array( 'email_template_id' => 1 ) )->row()->subject;
			$to  = $query->row()->email;
			if ( $account_type == 'user' ) {
				$to_name = $query->row()->username;
			} else {
				$to_name = $query->row()->name;
			}
//                        $encode_url     = "<a href='".$url."'>Click Here</a>";
                        $encode_url     = $url;
                        if($userLanguage == 'fr'){
                            $email_body = $this->db->get_where( 'email_template', array( 'subject' => 'Password Reset Link','language'=>'fr') )->row()->body;
                        }else{    
                            $email_body = $this->db->get_where( 'email_template', array( 'subject' => 'Password Reset Link','language'=>'en') )->row()->body;
                        }
//			$email_body = $this->db->get_where( 'email_template', array( 'email_template_id' => 1 ) )->row()->body;
			$email_body = str_replace( '[[to]]', $to_name, $email_body );
			$email_body = str_replace( '[[url]]', $encode_url, $email_body );
			$email_body = str_replace( '[[from]]', $from_name, $email_body );
                       
			$background = $this->db->get_where( 'ui_settings', array( 'type' => 'email_theme_style' ) )->row()->value;
			if ( $background !== 'style_1' ) {
				$final_email = $this->db->get_where( 'ui_settings', array( 'type' => 'email_theme_' . $background ) )->row()->value;
				$final_email = str_replace( '[[body]]', $email_body, $final_email );
				$send_mail   = $this->do_email( $from, $from_name, $to, $sub, $final_email );
			} else {
				$send_mail = $this->do_email( $from, $from_name, $to, $sub, $email_body );
			}

			return $send_mail;
		} else {
			return false;
		}
	}
        
	function account_opening( $account_type = '', $email = '', $pass = '' ) {
		$from_name = $this->db->get_where( 'general_settings', array( 'type' => 'system_name' ) )->row()->value;
		$protocol  = $this->db->get_where( 'general_settings', array( 'type' => 'mail_status' ) )->row()->value;
		if ( $protocol == 'smtp' ) {
			$from = $this->db->get_where( 'general_settings', array( 'type' => 'smtp_user' ) )->row()->value;
		} else if ( $protocol == 'mail' ) {
			$from = $this->db->get_where( 'general_settings', array( 'type' => 'system_email' ) )->row()->value;
		}

		$to    = $email;
		$query = $this->db->get_where( $account_type, array( 'email' => $email ) );

		if ( $query->num_rows() > 0 ) {
			if ( $account_type == 'admin' ) {
				$to_name = $query->row()->name;
				$url     = "<a href='" . base_url() . "index.php/admin/'>" . base_url() . "index.php/admin</a>";

				$sub        = $this->db->get_where( 'email_template', array( 'email_template_id' => 6 ) )->row()->subject;
				$email_body = $this->db->get_where( 'email_template', array( 'email_template_id' => 6 ) )->row()->body;
			}
			if ( $account_type == 'user' ) {
				$to_name = $query->row()->username;
				$url     = "<a href='" . base_url() . "index.php/home/login_set/login'>" . base_url() . "index.php/home/login_set/login</a>";

				$sub        = $this->db->get_where( 'email_template', array( 'email_template_id' => 5 ) )->row()->subject;
				$email_body = $this->db->get_where( 'email_template', array( 'email_template_id' => 5 ) )->row()->body;
			}

			$email_body = str_replace( '[[to]]', $to_name, $email_body );
			$email_body = str_replace( '[[sitename]]', $from_name, $email_body );
			$email_body = str_replace( '[[account_type]]', $account_type, $email_body );
			$email_body = str_replace( '[[email]]', $to, $email_body );
			$email_body = str_replace( '[[password]]', $pass, $email_body );
			$email_body = str_replace( '[[url]]', $url, $email_body );
			$email_body = str_replace( '[[from]]', $from_name, $email_body );

			$background = $this->db->get_where( 'ui_settings', array( 'type' => 'email_theme_style' ) )->row()->value;
			if ( $background !== 'style_1' ) {
				$final_email = $this->db->get_where( 'ui_settings', array( 'type' => 'email_theme_' . $background ) )->row()->value;
				if ( $background == 'style_4' ) {
					$home_top_logo = $this->db->get_where( 'ui_settings', array( 'type' => 'home_top_logo' ) )->row()->value;
					$logo          = base_url() . 'uploads/logo_image/logo_' . $home_top_logo . '.png';
					$final_email   = str_replace( '[[logo]]', $logo, $final_email );
				}
				$final_email = str_replace( '[[body]]', $email_body, $final_email );
				$send_mail   = $this->do_email( $from, $from_name, $to, $sub, $final_email );
			} else {
				$send_mail = $this->do_email( $from, $from_name, $to, $sub, $email_body );
			}

			return $send_mail;
		} else {
			return false;
		}
	}


	function newsletter( $title = '', $text = '', $email = '', $from = '' ) {
		$from_name = $this->db->get_where( 'general_settings', array( 'type' => 'system_name' ) )->row()->value;
		$this->do_email( $from, $from_name, $email, $title, $text );
	}

	
	/***custom email sender****/
	function do_email( $from = '', $from_name = '', $to = '', $sub = '', $msg = '' ) {
		$this->load->library( 'email' );
		$this->email->set_newline( "\r\n" );
		$this->email->from( $from, $from_name );
		$this->email->reply_to( $from, $from_name );
		$this->email->to( $to );
		$this->email->subject( $sub );
		$this->email->message( $msg );
                return true;
		 /* if ( $this->email->send() ) {
			return true;
		} else {

			return false;
		} */ 

	}
         

	function do_sms( $number, $msg ) {
		$this->load->library( 'SmsPortal' );
		return $this->smsportal->sendSms( $number, $msg );
	}
        
        //FCM NOTIFICATION FLOW : START 
        function sendNotification($notification_array = array(),$device_array=array()){
            $data_array = array();
            $auth_token = array
            (
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );
        
            $data_array['title']    = $notification_array['title'];  //
            $data_array['body']   = $notification_array['body'];//  //
            $data_array['icon']	= 'myicon';/*Default Icon*/
            $data_array['sound']    = "default";
//            $data_array['image']    = $notification_array['image_path']; //"https://dummyimage.com/600x400/000/fff"; //
            $data_array['image']    = "https://dummyimage.com/600x400/000/fff"; //
            $data_array['click_action']="FCM_PLUGIN_ACTIVITY";
//             $user_id = 1;
//            $device_array[] =  $this->db->get_where('user',array('user_id'=>$user_id))->row()->fcm_id;
            $fields = array
            (
                    'registration_ids'	=> $device_array,
                    'notification'	=> $data_array,
                    //'data'             => $data_array,  >>not in use
             );
           
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $auth_token );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
            $data_res = json_decode($result, TRUE);

            $send_array = array
            (
                'success'	=> $data_res['success'],
                'failure'	=> $data_res['failure']
            );
        
    }
    //FCM NOTIFICATION FLOW : END
    
    


}
