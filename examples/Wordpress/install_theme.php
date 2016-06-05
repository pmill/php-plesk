<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'id' => 1,
    'theme_id' => 'emmet-lite',
];

$request = new \pmill\Plesk\Wordpress\InstallTheme($config, $params);

$info = $request->process();
var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
