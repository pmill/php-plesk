<?php

require_once("../config.php");

$params = [
    //'id'=>'',
    'guid'=>'130479c8-7792-b135-8b50-001faed5d517',
];

$request = new \pmill\Plesk\GetServicePlan($config, $params);
$info = $request->process();

var_dump($info);