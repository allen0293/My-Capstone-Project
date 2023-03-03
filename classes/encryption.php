<?php

class Encryption{

 function __construct()
	{
		# code...
	}
 public function encrypt($data) {   
      $encryption_key = 'qkwjdiw239&&jdafweihbrhnan&^%$ggdnawhd4njshjwuuO'; 
      $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')); 
      $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv); 
      return base64_encode($encrypted . '::' . $iv); 
  } 

  public function decrypt($data) {  
  $encryption_key = 'qkwjdiw239&&jdafweihbrhnan&^%$ggdnawhd4njshjwuuO'; 
  list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null); 
  return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv); 
  } 
  	}

  ?>