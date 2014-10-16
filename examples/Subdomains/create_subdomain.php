<?php

require_once("../config.php");

$params = array(
	'domain'=>'example.com',
	'subdomain'=>'testsubdomain',
	'www_root'=>'/subdomains/testsubdomain',
	'fpt_username'=>'username',
	'fpt_password'=>'password',
);

$request = new \pmill\Plesk\CreateSubdomain($config, $params);
$info = $request->process();

var_dump($info);