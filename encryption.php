<?php

class Encryption{

 function __construct()
	{
		# code...
	}

 public function encrypt($data) {   
    $encryption_key = openssl_digest(php_uname(), 'SHA512', TRUE); 
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc')); 
    $encrypted = openssl_encrypt($data, 'aes-128-cbc', $encryption_key, 0, $iv); 
    return base64_encode($encrypted . '::' . $iv); 
  } 

  public function decrypt($data) {  
  $encryption_key = openssl_digest(php_uname(), 'SHA512', TRUE); 
  list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null); 
  return openssl_decrypt($encrypted_data, 'aes-128-cbc', $encryption_key, 0, $iv); 
  } 
  	}
  


  ?>