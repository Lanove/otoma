<?php

$aesKey = "2B7E151628AED2A6ABF7158809CF4F3C";

function decryptAes($aesKey,$data){
    $decode = base64_decode($data);
    $decode .= "%";
    $explodeDecode = explode("%",$decode,-1);
    $ivB64 = $explodeDecode[0]; 
    $msgB64 = $explodeDecode[1];

    $plain_iv = bin2hex(base64_decode($ivB64));
    $iv = hex2bin($plain_iv);
    $key = hex2bin($aesKey);
    
    $bytes = openssl_decrypt($msgB64, "AES-128-CBC", $key, $options=0, $iv);
    
    $receivedMessage = base64_decode($bytes, true);
    return $receivedMessage;
}

function encryptAes($aesKey,$message){
    $ivlen = openssl_cipher_iv_length("AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen); // Generate IV bytes
    
    $b64data = base64_encode($message);  // Encode Message to B64
    $b64iv = base64_encode($iv); // Encode IV to B64
    $key = hex2bin($aesKey); // Convert Key
    $ciphertext = openssl_encrypt($b64data, "AES-128-CBC", $key, $options=0, $iv); // Encrypt b64data
    $concat = $b64iv ."%". $ciphertext; // Concat
    $concatEncoded = base64_encode($concat); // Encode Concatted data
    return $concatEncoded;
}
?>