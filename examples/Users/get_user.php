<?php

require_once("../config.php");

$params = array(
    'guid'=>'',
    //'username'=>'',
);

$request = new \pmill\Plesk\GetUser($config, $params);
$info = $request->process();

var_dump($info);
