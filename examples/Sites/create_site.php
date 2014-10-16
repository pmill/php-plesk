<?php

require_once("../config.php");

$params = array(
    'domain'=>'example.com',
	'subscription_id'=>1,
);

$request = new \pmill\Plesk\CreateSite($config, $params);
$info = $request->process();

var_dump($info);
echo "<BR>Created site id: ".$request->id;