<?php

require_once("../config.php");

$params = array(
    'id'=>4,
    //'username'=>'',
);

$request = new \pmill\Plesk\GetClient($config, $params);
$info = $request->process();

var_dump($info);