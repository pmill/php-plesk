<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("../lib/plesk/site_list.php");

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$request = new Site_List_Request($config);
$info = $request->process();
var_dump($info);