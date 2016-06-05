<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'query' => 'Lite',
];

$request = new \pmill\Plesk\Wordpress\SearchThemes($config, $params);

$info = $request->process();
var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
