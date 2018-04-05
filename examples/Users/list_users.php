<?php

require_once("../config.php");

$request = new \pmill\Plesk\ListUsers($config);
$info = $request->process();

var_dump($info);
