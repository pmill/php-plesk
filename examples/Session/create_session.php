<?php

require_once("../config.php");

$userIP = '127.0.0.1';
$params = array(
    'username'=>'plesk_username',
	'user_ip'=> $userIP,
	'source_server' => gethostbyaddr($userIP)
);

$request = new \pmill\Plesk\CreateSession($config, $params);
$info = $request->process();

var_dump($info);
echo "<BR>Created session id: ".$request->id;
