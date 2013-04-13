<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("../lib/plesk/change_email_password.php");

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$params = array(
	'email'=>'test1365695330@example.com',
	'password'=>'anewreallylongstringwithsomenumbers1',
);

$request = new Change_Email_Password_Request($config, $params);
$info = $request->process();
var_dump($info);