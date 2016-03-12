<?php

require_once("../config.php");

$params = [

];

$request = new \pmill\Plesk\ListAvailablePackages($config, $params);
$info = $request->process();

var_dump($info);