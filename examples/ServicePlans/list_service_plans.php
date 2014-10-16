<?php

require_once("../config.php");

$request = new \pmill\Plesk\ListServicePlans($config);
$info = $request->process();

var_dump($info);