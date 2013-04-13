<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("../lib/plesk/site_info.php");

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$params = array(
	'domain'=>'example.com',
);

$request = new Site_Info_Request($config, $params);
$info = $request->process();
var_dump($info);