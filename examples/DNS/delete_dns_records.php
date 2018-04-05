<?php

require_once("../config.php");

$params = array(
    'id'=>1,
    //'domain'=>'example.com'
);

$request = new \pmill\Plesk\DeleteDNSRecords($config, $params);
$info = $request->process();

var_dump($info);
