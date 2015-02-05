<?php

require_once("../config.php");

/*
 * Lists all database under a subscription
 */
$params = array(
	'subscription_id'=>0,
);

$request = new \pmill\Plesk\ListDatabases($config, $params);
$info = $request->process();

var_dump($info);