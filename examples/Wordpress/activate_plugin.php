<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'id' => 1,
    'plugin_id' => 'wp-super-cache_1.4.8',
];

$request = new \pmill\Plesk\Wordpress\ActivatePlugin($config, $params);

$info = $request->process();
var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
