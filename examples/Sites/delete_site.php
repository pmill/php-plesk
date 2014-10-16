<?php

require_once("../config.php");

$params = array(
    'id'=>'',
);

$request = new \pmill\Plesk\DeleteSite($config, $params);
$info = $request->process();

var_dump($info);