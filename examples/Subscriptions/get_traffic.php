<?php

require_once("../config.php");

/*
 * Get subscription details
 */
$params = array(
	'id'=>'1',
//  'name' => 'demo.parallels.com',
//  'owner-login' => 'customer',
//  'since_date' => '2016-06-01',
//  'to_date'    => '2016-06-10'
);

$request = new \pmill\Plesk\GetTraffic($config, $params);
$info = $request->process();

var_dump($info);

if ($info === false) {
    var_dump($request->error);
}
