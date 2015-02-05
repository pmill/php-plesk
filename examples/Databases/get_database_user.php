<?php

require_once("../config.php");

$params = array(
	'database_id'=>'0',
);

$request = new \pmill\Plesk\GetDatabaseUser($config, $params);
$info = $request->process();

var_dump($info);