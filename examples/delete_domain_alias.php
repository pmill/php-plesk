<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("../lib/plesk/delete_domain_alias.php");

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$params = array(
	'domain'=>'example.com',
	'alias'=>'assets.demo.creativecentral.net',
);

$request = new Delete_Domain_Alias_Request($config, $params);
$info = $request->process();
var_dump($info);