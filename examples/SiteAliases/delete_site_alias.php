<?php

require_once("../config.php");

$params = array(
	'alias'=>'testalias2.example.com',
);

$request = new \pmill\Plesk\DeleteSiteAlias($config, $params);
$info = $request->process();

var_dump($info);