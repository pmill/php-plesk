<?php

require_once("../config.php");

$params = array(
	'subdomain'=>'test1.pmill.co.uk',
	'www_root'=>'/subdomains/testsubdomainrename',
);

$request = new \pmill\Plesk\UpdateSubdomain($config, $params);
$info = $request->process();

var_dump($info);