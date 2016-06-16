<?php

require_once("../config.php");

$params = [
	'filter' => ['id' => 1],
    'values' => [
        'hosting' => [
            'vrt_hst' => [
                'ftp_login' => 'username',
                'ftp_password' => 'password',
                'ip_address' => '127.0.0.1',
            ],
        ],
    ],
];

$request = new \pmill\Plesk\UpdateSubscription($config, $params);
$info = $request->process();

var_dump($info);