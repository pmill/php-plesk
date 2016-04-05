<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'id' => 1,
    'theme_id' => 'emmet-lite_1.4.0',
];

$request = new \pmill\Plesk\Wordpress\UninstallTheme($config, $params);

$info = $request->process();
var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
