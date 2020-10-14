<?php

$aesKey = "2B7E151628AED2A6ABF7158809CF4F3C";

function decryptAes($aesKey, $data)
{
    $explodeDecode = explode("%", $data . "%", -1);
    $ivB64 = $explodeDecode[0];
    $msgB64 = $explodeDecode[1];

    $plain_iv = bin2hex(base64_decode($ivB64));
    $iv = hex2bin($plain_iv);
    $key = hex2bin($aesKey);

    $bytes = openssl_decrypt($msgB64, "AES-128-CBC", $key, $options = 0, $iv);

    return $bytes;
}

function encryptAes($aesKey, $message)
{
    $ivlen = openssl_cipher_iv_length("AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen); // Generate IV bytes

    $b64iv = base64_encode($iv); // Encode IV to B64
    $key = hex2bin($aesKey); // Convert Key
    $ciphertext = openssl_encrypt($message, "AES-128-CBC", $key, $options = 0, $iv); // Encrypt b64data
    $concat = $b64iv . "%" . $ciphertext; // Concat
    return $concat;
}
