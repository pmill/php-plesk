<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("../lib/plesk/create_email_address.php");

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$params = array(
	'email'=>'test1365695330@example.com',
	'password'=>'areallylongstringwithsomenumbers1',
);

$request = new Create_Email_Address_Request($config, $params);
$info = $request->process();
var_dump($info);