<?php

require_once("../config.php");

$params = array(
	'domain'=>'example.com',
	'alias'=>'testalias2.example.com',
);

$request = new \pmill\Plesk\CreateSiteAlias($config, $params);
$info = $request->process();

var_dump($info);