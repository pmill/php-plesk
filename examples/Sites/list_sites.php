<?php

require_once("../config.php");

/*
 * Lists all sites, or if subscription_id is supplied lists only that subscriptions sites
 */
$params = array(
	'subscription_id'=>0,
);

$request = new \pmill\Plesk\ListSites($config, $params);
$info = $request->process();

var_dump($info);