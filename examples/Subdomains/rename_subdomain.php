<?php

require_once("../config.php");

$params = array(
	'subdomain'=>'testsubdomain.example.com',
	'name'=>'testsubdomin1',
);

$request = new \pmill\Plesk\RenameSubdomain($config, $params);
$info = $request->process();

var_dump($info);