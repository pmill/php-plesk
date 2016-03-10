<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'id' => 2,
];

$request = new \pmill\Plesk\Wordpress\EnableAutoUpdates($config, $params);

$info = $request->process();
var_dump($info);
