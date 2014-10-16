<?php

require_once("../config.php");

$request = new \pmill\Plesk\ListClients($config);
$info = $request->process();

var_dump($info);