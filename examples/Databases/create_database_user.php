<?php

require_once("../config.php");

/*
* These options will create a database user for the specified database
*/
$params = array(
    'database_id'=>'0',
	'username'=>'username',
    'password'=>'password',
);

/*
* These options will create a universal database user for the specified database server and subscription
*/
$params = array(
    'subscription_id'=>'0',
    'server_id'=>'0',
	'username'=>'username',
    'password'=>'password',
);

$request = new \pmill\Plesk\CreateDatabaseUser($config, $params);
$info = $request->process();

var_dump($info);
echo "<BR>Created database user id: ".$request->id;