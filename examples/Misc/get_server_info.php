<?php

require_once("../config.php");

$request = new \pmill\Plesk\GetServerInfo($config);
$info = $request->process();

var_dump($info);