<?php

require_once APPPATH. '/third_party/php-jwt-master/src/functions.php';
require_once APPPATH. '/third_party/php-jwt-master/src/ValidatesJWT.php';
require_once APPPATH. '/third_party/php-jwt-master/src/JWT.php';
require_once APPPATH. '/third_party/php-jwt-master/src/JWTException.php';

class Jwttoken {
    
    function createToken($uid){
        // Instantiate with key, algo, maxAge and leeway.
            $jwt = new Ahc\Jwt\JWT('secret', 'HS256', 3600, 10);
            //Only the key is required. Defaults will be used for the rest:
            
//            $jwt = new Ahc\Jwt\JWT('secret');
            // algo = HS256, maxAge = 3600, leeway = 0
            //For RS* algo, the key should be either a resource like below:

            $key = openssl_pkey_new([
                'digest_alg' => 'sha256',
                'private_key_bits' => 1024,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]);
            
            
            //OR, a string with full path to the RSA private key like below:

            $key = APPPATH. '/third_party/php-jwt-master/tests/stubs/priv.key';
        
            // Then, instantiate JWT with this key and RS* as algo:
            $jwt = new Ahc\Jwt\JWT($key, 'RS384', 43200, 10); //3600 sec = 1hr
            
            //Pro You dont need to specify pub key path, that is deduced from priv key.

            //Generate JWT token from payload array:

            $token = $jwt->encode([
                'uid'    => $uid,
                'aud'    => 'http://mypcot.com',
                'scopes' => ['user'],
                'iss'    => 'http://api.mypcot.com',
            ]);
       
            return $token;
        
    }
    
    function validateToken($token){
        $key = APPPATH. '/third_party/php-jwt-master/tests/stubs/priv.key';
        
        // Then, instantiate JWT with this key and RS* as algo:
        $jwt = new Ahc\Jwt\JWT($key, 'RS384');
        try{
        $payload = $jwt->decode($token);
//        echo "<pre>";
//        print_r(date('Y-m-d H:i:s',$payload['exp']));
//        print_r($payload);
//        exit;
        }
        catch (Exception $e){
            echo json_encode(array('success'=>4,'message'=>'Invalid Token'));
            exit;
        }
        
        return $payload;
        
    }
    
    function validateB2bToken($token){
        $key = APPPATH. '/third_party/php-jwt-master/tests/stubs/priv.key';
        // Then, instantiate JWT with this key and RS* as algo:
        $jwt = new Ahc\Jwt\JWT($key, 'RS384');
        try{
        $payload = $jwt->decode($token);
        }
        catch (Exception $e){
            return false;
        }
        return $payload;
        
    }
    
    
}


