<?php

require_once("../config.php");

$params = [
	'key' => '6621a74e-e626-72d0-d3e7-8c9eb61b20da',
];

$request = new \pmill\Plesk\DeleteSecretKey($config, $params);
$info = $request->process();

var_dump($info);