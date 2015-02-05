<?php

require_once("../config.php");

$params = array(
    'name'=>'database name',
	'subscription_id'=>0,
    'server_id'=>0,
    'type'=>'mysql',
);

$request = new \pmill\Plesk\CreateDatabase($config, $params);
$info = $request->process();

var_dump($info);
echo "<BR>Created database id: ".$request->id;