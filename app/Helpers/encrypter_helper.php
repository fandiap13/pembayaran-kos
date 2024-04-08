<?php

function encryptID($id)
{
     $encrypter = \Config\Services::encrypter();
     $encryptedId = $encrypter->encrypt($id);

     return bin2hex($encryptedId);

     // $hashedId = md5($encryptedId);
     // return $hashedId;
}

function decryptID($encryptedId)
{
     // $encryptedId = hex2bin($encryptedId);

     // $encrypter = \Config\Services::encrypter();
     // return $encrypter->decrypt($encryptedId);
     // Reverse the MD5 hash
     $decryptedId = hex2bin($encryptedId);

     $encrypter = \Config\Services::encrypter();
     $decryptedId = $encrypter->decrypt($decryptedId);

     return $decryptedId;
}
