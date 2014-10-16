<?php

require_once("../config.php");

$params = array(
	'subdomain'=>'testsubdomain.example.com',
);

$request = new \pmill\Plesk\DeleteSubdomain($config, $params);
$info = $request->process();

var_dump($info);