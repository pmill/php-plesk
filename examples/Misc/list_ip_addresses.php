<?php

require_once("../config.php");

$request = new \pmill\Plesk\ListIPAddresses($config);
$info = $request->process();

var_dump($info);