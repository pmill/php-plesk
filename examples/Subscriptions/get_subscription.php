<?php

require_once("../config.php");

/*
 * Get subscription details
 */
$params = array(
	'subscription_id'=>'1',
	//'name'=>'example.com',
	//'username'=>'',
);

$request = new \pmill\Plesk\GetSubscription($config, $params);
$info = $request->process();

var_dump($info);

if ($info === false) {
    var_dump($request->error);
}
