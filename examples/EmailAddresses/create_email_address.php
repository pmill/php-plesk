<?php

require_once("../config.php");

$params = array(
	'email'=>'test1365695330@example.com',
	'password'=>'areallylongstringwithsomenumbers2',
);

$request = new \pmill\Plesk\CreateEmailAddress($config, $params);
$info = $request->process();

var_dump($info);