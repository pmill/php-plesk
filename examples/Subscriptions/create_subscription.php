<?php

require_once("../config.php");

$params = array(
    'domain_name'=>'example.com',
    'username'=>'username',
	'password'=>'password1!',
	'ip_address'=>'192.168.1.2',
	'owner_id'=>0,
	'service_plan_id'=>0,
);

$request = new \pmill\Plesk\CreateSubscription($config, $params);
$info = $request->process();

var_dump($info);