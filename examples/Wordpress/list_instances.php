<?php

require_once("../config.php");

/*
 * Lists all sites, or if subscription_id is supplied lists only that subscriptions sites
 */
$request = new \pmill\Plesk\Wordpress\ListInstances($config);
$info = $request->process();

var_dump($info);
