<?php

require_once("../config.php");

$params = array(
	'domain'=>'example.com',
);

$request = new \pmill\Plesk\ListSiteAliases($config, $params);
$info = $request->process();

var_dump($info);