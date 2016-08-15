<?php

require_once("../config.php");

$params = array(
	'domain'=>'example.com',
);

$request = new \pmill\Plesk\ListDNSRecords($config, $params);
$info = $request->process();

var_dump($info);