<?php


set_include_path(DOC_ROOT_FRONT . '/application/libraries/phpseclib');
include(DOC_ROOT_FRONT . '/application/libraries/phpseclib/Crypt/RSA.php');
class Mcrypt
{
    private $iv = 'fedcba9876543210'; #This is used on the mobile apps
    private $key = '0123456789abcdef'; #This is used on the mobile apps
    private $ivInternal = '1593786240feecba'; #This is used on the database
    private $keyInternal = 'abbdef0124578369'; #This is used on the database
    function __construct() {
        $this->CI = & get_instance();
    }
    
	function decryptCipherText($ciphertext="",$generatedPrivateKey=""){
            $decodedPlainText = "";
            if(isset($ciphertext) && !empty($ciphertext)){
                
                $private_key = new Crypt_RSA();
                $private_key->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
                
                if(empty($generatedPrivateKey)){
                     $generatedPrivateKey = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAJl1egXq62NBg7DlXoB6m/wwBYArGE4Omg+V9iHNQSZ6FZF5xbxe9qch8Ez0zdMAa1euEJm5spqfF/lkPvKMmk+16YHvZem4GOmCHy/iVsZwIeqNOGm9q209eGdsIPs0RGSLHy4x8LuIGbpgdy+pfKR2LDdnzzriJjpyJTgoQ9LlAgMBAAECgYEAkX/7NEUq7a8eZ8jyMysNXq1BaeZyYwAhPonXFvF/xjWW4Znty87WWl0pdC3gQSFypW2au8Z/+27A0msj6+E4JMI+pE5bT3kS+BEK/+zmcVxl3AZfkSQt2oMXzK7YN83uju/lYt0wpXygebcL/LG0QFxGmWq1aa63HdOCe6NrTQECQQDrnmADU+HJQMSWWFZWAD9FsV+Or8fOlowYpKPjhZGMz/Ya0jeBWfJP/lrQ8QXfbwl4zYlbD322Zkr9GysZJgQhAkEApru54AJRi4uqqPChI7NBvAF8d3hHWPECVF5/OUE8B7fP8hmFGDa7ld3toca7FOQC0xZ+MCbTFG2TvPy9N232RQJASTHW89GwF3wGVgo1L9w9y6GxZLsYoAXGmbUzsG+C6rKD6osZcRaHHvCON9BYGbP9xkhbfi1OyUX3z68L8XEM4QJAOBuYMz/UfSck6PEA6OibyE6fufxp1g5UgFvEaFoBf39lpMzdswZIHeiu3O/paOBJ6wr4r++AvLEbNE/AxPRmGQJAVpigBtZtTpQpVyJALXLh3eUNjShzCQ9yboegvRzhaCx6eTM1c3g1y3t0KDgACL432Xy7CYeEO9GBlfYI1B+vOw==";
                }
                
//                $private_key->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
                $private_key->loadKey($generatedPrivateKey);
                $decodedCiphertext = base64_decode($ciphertext);
                $decodedPlainText = $private_key->decrypt($decodedCiphertext);
                
            }
            return $decodedPlainText;
        }
        
        function encryptPlainText($plaintext="",$generatedPublicKey=""){
            $encodedPlainText = "";
            if(isset($plaintext) && !empty($plaintext)){
                $public_key =  new Crypt_RSA();
                $public_key->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
                
                if(empty($generatedPublicKey)){
                    $generatedPublicKey =  "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCZdXoF6utjQYOw5V6Aepv8MAWAKxhODpoPlfYhzUEmehWRecW8XvanIfBM9M3TAGtXrhCZubKanxf5ZD7yjJpPtemB72XpuBjpgh8v4lbGcCHqjThpvattPXhnbCD7NERkix8uMfC7iBm6YHcvqXykdiw3Z8864iY6ciU4KEPS5QIDAQAB";
                }
                $public_key->loadKey($generatedPublicKey);
                $encodedPlainText = $public_key->encrypt($plaintext);
                
                $finalCipherText  = $encodedPlainText = base64_encode($encodedPlainText);
               // $finalCipherText = urlencode($secondCipherText);
            }
            
            return $finalCipherText;
        }
        
}
