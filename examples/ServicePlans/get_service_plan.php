<?php

require_once("../config.php");

$params = array(
    'id'=>'',
);

$request = new \pmill\Plesk\GetServicePlan($config, $params);
$info = $request->process();

var_dump($info);