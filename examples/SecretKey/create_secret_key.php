<?php

require_once("../config.php");

$params = [
    'ip_address' => '',
    'description' => 'Test Secret Key'
];

$request = new \pmill\Plesk\CreateSecretKey($config, $params);
$info = $request->process();

var_dump($info);