<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'id' => 2,
];

$request = new \pmill\Plesk\Wordpress\RemoveInstance($config, $params);

$info = $request->process();
var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
