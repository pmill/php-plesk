<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'id' => 1,
];

$request = new \pmill\Plesk\Wordpress\GetThemeList($config, $params);

$info = $request->process();
var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
