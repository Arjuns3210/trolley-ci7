<?php
/**
 * The SMS gateway is used for sending sms to users.
 *
 * @author SAGAR - Mypcot Team
 * reference https://www.mypcot.com/
 */

class MypcotSMS {
 
    var $CI;
    var $username="MAAK";
    var $password="123321";
    var $senderName = "Trolley";
    var $host_url = "aHR0cDovLzE5Ni4yMDIuMTM0LjkwL1NNU2J1bGsvd2ViYWNjLmFzcHg";

    function __construct() {
        $this->CI = & get_instance();
    }
    
    function triggerOTPSMS($sms_msg='',$contacts=array()){
        $username = $this->username;
        $password = $this->password;
        if(isset($contacts) && count($contacts)>0 && !empty($sms_msg)){
            $contacts_semicolon_separated = implode(';', $contacts);
            $senderName = $this->senderName;
            $sms_text = urlencode($sms_msg);
            $url_params = "?user=".$username. "&pwd=" .$password. "&smstext=" .$sms_text.
                            "&Sender=".$senderName."&Nums=".$contacts_semicolon_separated;
            return $this->invokeGetRequest($url_params);
        }else{
            return 'error';
        }
    }
    
    function triggerPromoSMS($sms_msg='',$contacts=array()){
        $username = $this->username;
        $password = $this->password;
        if(isset($contacts) && count($contacts)>0 && !empty($sms_msg)){
            $contacts_semicolon_separated = implode(';', $contacts);
            $senderName = $this->senderName;
            $sms_text = urlencode($sms_msg);
            $url_params = "?user=".$username. "&pwd=" .$password. "&smstext=" .$sms_text.
                            "&Sender=".$senderName."&Nums=".$contacts_semicolon_separated;
            //return $this->invokeGetRequest($url_params);
        }else{
            return 'error';
        }
    }
    
    function invokeGetRequest($requestUrl){
        return true;
            global $base_url;
            $baseurl = base64_decode($this->host_url);
            $url = $baseurl.$requestUrl;
     
            try
            {
                $response = file_get_contents($url);
                //I'm getting Invalid in respons
		
                return $response;

            }catch(Exception $e1)
            {
                //$this->throwErrorScreen($e1);
                //echo "generic exception".$e1."<hr></br>";
		return false;
            }
    }

    
    function invokePostRequest($requestUrl){		
            global $base_url;
            $base_url = base64_decode($this->host_url);
         	
            try
            {
                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL, $base_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$requestUrl);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    return $response;
            }catch(Exception $e1){
                //$this->throwErrorScreen($e1);
                echo "URL is ".$base_url.$requestUrl."generic exception".$e1."<hr></br>";
            }
    }
}
