<?php

require_once("../config.php");

$params = array(
	'subdomain'=>'testsubdomain.example.com',
	'www_root'=>'/subdomains/testsubdomainrename',
	'ftp_username'=>'username',
	'ftp_password'=>'password',
);

$request = new \pmill\Plesk\UpdateSubdomain($config, $params);
$info = $request->process();

var_dump($info);