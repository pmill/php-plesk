<?php

require_once("../config.php");

$params = array(
	'id'=>'',
	'php'=>TRUE,
	'php_handler_type'=>'module',
	'webstat'=>'awstats',
	'www_root'=>'',
	'domain'=>'example.com',
	'status'=>0,
);

$request = new \pmill\Plesk\UpdateSite($config, $params);
$info = $request->process();

var_dump($info);