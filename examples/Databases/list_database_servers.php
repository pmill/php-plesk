<?php

require_once("../config.php");

/*
 * Lists all database servers
 */
$request = new \pmill\Plesk\ListDatabaseServers($config);
$info = $request->process();

var_dump($info);