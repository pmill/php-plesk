<?php

require_once("../config.php");

$params = [
    'domain-name' => 'example.org',
    'package-id' => 1,
];

$request = new \pmill\Plesk\APS\InstallApplication($config, $params);
$info = $request->process();

var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
