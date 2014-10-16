<?php

require_once("../config.php");

$params = array(
    'username'=>'username',
    'phone'=>'phone',
    'email'=>'email@example.com',
);

$request = new \pmill\Plesk\UpdateClient($config, $params);
$info = $request->process();

var_dump($info);