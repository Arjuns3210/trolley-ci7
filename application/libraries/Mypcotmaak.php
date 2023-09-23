<?php

/**
 * @author Ritesh - Mypcot Team
 * reference https://www.mypcot.com/
 */
class Mypcotmaak {

    //details for testing envn
    var $CI;
    var $mobile = "0982018030";
    var $pubKeyUsed = "MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBANx4gKYSMv3CrWWsxdPfxDxFvl+Is/0kc1dvMI1yNWDXI3AgdI4127KMUOv7gmwZ6SnRsHX/KAM0IPRe0+Sa0vMCAwEAAQ==";
    var $password = "Z6SMKXkg48fvN+clVQBQOapSJs0+7HenrTbGiCvUetmfy74f8VXRKB34uik6JWEZ4mdsCejoCn16FN4gxH6hSwTWUAE3qth3nw2tsCa1rgHYVf2ikp0h2+pZXOhH6xOJ0eSofrGPFseoRvjqp+k+XSF3UBJj6LhyKy0l3zN5IgM=";
//    var $base_url = "https://www.maak-sd.com/api/Payment/";
    var $base_url = "https://www.maakmobiles.com:3443/api/";
   // var $base_url = "https://192.168.13.12:3443/api/";


    var $port = "8181";

    function __construct() {
        $this->CI = & get_instance();
    }
    
    function makePurchaseCall($uuid="550e8400-e29b-41d4-a716-44665544",$PAN="", $PIN ="1234" ,$expDate="",$totalAmount=0,$discount = 0){
        $serviceName = 'Payment/PublicServicePayment';
        
        $headerToken = $this->getSafeToken($this->mobile,$this->password);
        $headerValue = array('Authorization' => $headerToken);
        $uuid = $this->generateRandomUUID();
        $plainText = $uuid.$PIN;
        $IPIN = $this->generateCiperText($plainText);
  
        $post_array = array();
        $post_array['ServiceProviderId'] = '0010050308';  //DONT KNOW
        $post_array['Currency'] = 'SDG';
        $post_array['Discount'] = $discount;
        $afterDiscountAmount = $totalAmount - $discount;
        $paidAmount = $afterDiscountAmount;
        $post_array['AfterDiscountAmount'] = $afterDiscountAmount;
        $post_array['PaidAmount'] = $paidAmount;
        $post_array['TotalAmount'] = $totalAmount;
        
        $post_array['PAN'] = $PAN;
        $post_array['expiryDate'] = $expDate;

	//$IPIN = "JipWQe8oblE5Zgkmr%2F%2FMiUCKVW5MnFvHD%2FN14nViLubZAsRaPSAUQ4HaoY6p%2BFf5u8rfkfCDL5ueLXonAPBR0w%3D%3D"; 	
        //$uuid = "0637fdc0-3e71-4801-8756-ec4c1a7eba00";
        $post_array['IPIN'] = $IPIN;
        $post_array['UUID'] = $uuid;
        
     
        $fields_array_json = json_encode($post_array);
        $ebs_response = $this->invokePostRequest($fields_array_json, $serviceName ,$headerValue);
	//echo "<Inside Library File after Post request>";
	//print_r($headerValue);
	//print_r('@@@@@@@@@@@@@@');
	//print_r($post_array);
	//print_r('||||||||||||||||||||');
	//print_r($ebs_response);exit;
//        $ebs_response = $this->invokeStubGetPurchaseRequest($fields_array_json, $serviceName,$headerValue);
         return $ebs_response;
        
    }
    
    function makeCardBalanceCall($uuid="550e8400-e29b-41d4-a716-44665544",$PAN="", $PIN ="1234" ,$currency = "SDG"){
        $serviceName = 'Balance';
        $plainText = $uuid.$PIN;
        $IPIN = $this->generateCiperText($plainText);
        
        $post_array = array();
        $post_array['PAN'] = $PAN;
        $post_array['IPIN'] = $IPIN;
        $post_array['UUID'] = $uuid;
        $post_array['currency'] = $currency;
        $fields_array_json = json_encode($post_array);
//       $ebs_response = $this->invokePostRequest($fields_array_json, $serviceName);
        $ebs_response = $this->invokeStubGetBalanceRequest($fields_array_json, $serviceName);
        return $ebs_response;
    }
    
    function getSafeToken($mobile,$password){
        $serviceName = 'Account/SafeLogin';
        $post_array= array();
        $post_array['MobileNo'] = $mobile;
        $post_array['Password'] = $password;
        $fields_array_json = json_encode($post_array);
        $ebs_response = $this->invokePostRequest($fields_array_json, $serviceName);
        $responseData = json_decode($ebs_response,true);
        if($responseData['success'] == 1){
            return  $responseData['data']['token']; 
        }else{
            return  "error";
        }
    }


    function generateCiperText($plaintext="1234"){
       
        $generatedPublicKey =  $this->getApisWorkingKey();
        if(empty($generatedPublicKey) && !isset($generatedPublicKey)){
            $generatedPublicKey = $this-> pubKeyUsed;
        }
        $ciphertext = $this->generateRSACiperText($plaintext,$generatedPublicKey);
        $finalCipherText = urlencode($ciphertext);
   
        return $finalCipherText;
    }
    
    
    function generateCiperTextOLD($plaintext="1234"){
        $public_key = new phpseclib\Crypt\RSA();
        extract($public_key->createKey());
        $generatedPublicKey =  $this->getApisWorkingKey();
//        $generatedPublicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCtLzyty+a3YgY3Gp49m5XbPAdIJbcRMed9erKOQ85NPckcypLToer9ka14DnxQeVO/dZkrlbygh0S4CZY9pBFgODqYHwNLz0+Y8wdmuAaMwqMMlx/ejW18tDmHjZzziRTmKRKED0ci+lGkElAbsa8fQsP/J35eiAbwS6/08Gin3QIDAQAB';
        
            
	if(empty($generatedPublicKey)){
            $generatedPublicKey = $this->pubKeyUsed;
        }
        $public_key->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	$public_key->loadKey($generatedPublicKey);
        $ciphertext = $public_key->encrypt($plaintext);
        $secondCipherText = base64_encode($ciphertext);
        $finalCipherText = urlencode($secondCipherText);          
        return $finalCipherText;
    }
    
    function getApisWorkingKey(){
        $method = 'Payment/GetPublicKey';
        $publicKeydata = $this->invokeGetRequest($method);
        $publicKeydata = json_decode($publicKeydata,true);
        if($publicKeydata['success'] == 1){
            return  $publicKeydata['data']; 
        }else{
            return "error";
        }
    }

    private function invokeStubGetPurchaseRequest($fields_array_json,$serviceName){		
            global $base_url;
            //$base_url = $this->base_url.$serviceName;
            //$port = $this->port;
            $stubs['response']['success'] = '{
                                                "success": true,
                                                "message": {
                                                    "code": "ServicePayment_Success_1",
                                                    "en": "request executed successfully",
                                                    "ar": "?? ????? ??????? ?????"
                                                },
                                                "data": {
                                                    "serviceProviderId": 10050111,
                                                    "serviceProvider": null,
                                                    "currency": "SDG",
                                                    "totalAmount": 20.0,
                                                    "afterDiscountAmount": 20.00,
                                                    "paidAmount": 20.0,
                                                    "discountRate": 0.00,
                                                    "balance": {
                                                        "available": 62.56,
                                                        "ledger": 0.0
                                                    },
                                                    "ebsResponse": {
                                                        "tranCurrency": "SDG",
                                                        "tranAmount": 20.0,
                                                        "serviceProviderId": "0010050111",
                                                        "PAN": "922216***4079",
                                                        "expDate": "0821",
                                                        "balance": {
                                                            "available": 62.56,
                                                            "ledger": 0.0
                                                        },
                                                        "acqTranFee": 0.0,
                                                        "issuerTranFee": -1.0,
                                                        "arabicResponseMessage": "?????? ?????",
                                                        "englishResponseMessage": "Approved",
                                                        "applicationId": "Osool",
                                                        "UUID": "637005b2-2b8b-4b41-b312-7d2a96cabd98",
                                                        "tranDateTime": "180420043208",
                                                        "responseCode": 0,
                                                        "responseStatus": "Successful",
                                                        "responseMessage": "Approved"
                                                    },
                                                    "date": "2020/04/18 - 16:32",
                                                    "arabicResponseMessage": "?????? ?????",
                                                    "englishResponseMessage": "Approved",
                                                    "PAN": "922216***4079",
                                                    "UUID": "637005b2-2b8b-4b41-b312-7d2a96cabd98",
                                                    "customerCardId": 0,
                                                    "referenceNo": 545022,
                                                    "acqTranFee": 0.0,
                                                    "issuerTranFee": 1.0,
                                                    "totalFees": 1.0,
                                                    "responseStatus": "Successful"
                                                },
                                                "returnToUrl": ""
                                            }';

            
            $stubs['response']['failure'] = '{
                                                "success": false,
                                                "message": {
                                                    "code": "ServicePayment_Fail_4",
                                                    "en": "Request Failed EBSGatway System error",
                                                    "ar": "???? ??????? ??? ?? ??????"
                                                },
                                                "data": {
                                                    "tranCurrency": "SDG",
                                                    "tranAmount": 830.0,
                                                    "serviceProviderId": "1060000001",
                                                    "PAN": "988819***6996",
                                                    "expDate": "0222",
                                                    "balance": null,
                                                    "acqTranFee": 0.0,
                                                    "issuerTranFee": 0.0,
                                                    "arabicResponseMessage": "???? ??????? ??? ?? ??????",
                                                    "englishResponseMessage": "Request Failed EBSGatway System error",
                                                    "applicationId": "OsoolDev",
                                                    "UUID": "626a3806-a10d-4aa3-add8-de4923972e87",
                                                    "tranDateTime": "180420090845",
                                                    "responseCode": 696,
                                                    "responseStatus": "Failed",
                                                    "responseMessage": "SYSTEM_ERROR"
                                                },
                                                "returnToUrl": ""
                                            }';
            
            $response =  $stubs['response']['success'] ;
            return $response;
                   
    }
    
    
    private function invokeStubGetBalanceRequest($fields_array_json,$serviceName){		
            global $base_url;
            //$base_url = $this->base_url.$serviceName;
            //$port = $this->port;
            $stubs['response']['success'] = '{
                                                "success": true,
                                                "message": {
                                                  "en": "message zab6a",
                                                  "ar": "",
                                                  "code": ""
                                                },
                                                "data": {
                                                  "payeeId": "0010010001",
                                                  "amount": 10.5,
                                                  "customerCardId": 1,
                                                  "currency": "SDG",
                                                  "paymentInfo": "meter=04123456789",
                                                  "referenceNo": 124253,
                                                  "billInfo": {
                                                    "token": "2378-3456-7645-45678-2345"
                                                  }
                                                }
                                              }';
            
            
            $stubs['response']['failure'] = '{
                                    
                                }';
            
            
            $response =  $stubs['response']['success'] ;
            return $response;
                   
    }
    
    private function invokeGetRequest($requestUrl){
            global $base_url;
            $baseurl = $this->base_url;
            $url = $baseurl.$requestUrl;
            try
            {
                $response = file_get_contents($url);
                return $response;
            }catch(Exception $e1)
            {
                return $e1;
                //$this->throwErrorScreen($e1);
            }
    }
    
    private function generateRandomUUID(){
            $data = openssl_random_pseudo_bytes(16);
            assert(strlen($data) == 16);
    
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    
     private function invokePostRequest($fields_array_json,$serviceName,$headerArray = ""){		
            global $base_url;
            $base_url = $this->base_url.$serviceName;
//            $port = $this->port;
            
            try
            {
                    $ch = curl_init();
                    $header_array = array(
                                        "Accept: application/json",
                                        "Cache-Control: no-cache",
                                        "Connection: keep-alive",
                                        "Content-Type: application/json"
                                    );
                    
                    if(is_array($headerArray) && isset($headerArray['Authorization'])){
                        $auth_token = $headerArray['Authorization'];
                        $token = "Authorization:$auth_token";
                        array_push($header_array,$token);
                    }
					
                    curl_setopt($ch,CURLOPT_PORT, $port);
                    curl_setopt($ch,CURLOPT_URL, $base_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_ENCODING, "");
                    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
                   // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_array_json);
                    
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array);
	
                    $response = curl_exec($ch);
                    $err = curl_error($ch);
                    curl_close($ch);
		

                    if ($err) {
//                        if(curl_errno($ch) == 'CURLE_OPERATION_TIMEDOUT'){
//                            $response='{"success":false,"message":{"code":"Custom Timeout Request Message 30001 ms","en":"Request Timeout EBSGatway System error","ar":"Request Timeout EBSGatway System error"},"returnToUrl":""}';
//                        }
                        $response='{"success":false,"message":{"code":'.$err.',"en":"Request Timeout EBSGatway System error","ar":"Request Timeout EBSGatway System error"},"returnToUrl":""}';
                        return $response;
                    }else {
                        return $response;
                    }
                    
            }catch(Exception $e1){
                return $e1;
            }
    }

    private function generateRSACiperText($plaintext,$generatedPublicKey){
        $this->CI->load->library('mcrypt');
        $cipherText = $this->CI->mcrypt->encryptPlainText($plaintext,$generatedPublicKey);
        return $cipherText;
    }
    

}
