<?php

require_once("../config.php");

$params = [
    'name' => 'test-ssl',
    'admin' => true,
    'csr' => '',
    'cert' => '',
    'pvt' => '',
    'ip-address' => '127.0.0.1',
];

$request = new \pmill\Plesk\SSL\InstallCertificate($config, $params);
$info = $request->process();

var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
