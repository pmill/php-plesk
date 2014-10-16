<?php

require_once("../config.php");

$params = array(
	'contact_name'=>'name',
	'username'=>'username',
	'password'=>'password1!',
);

$request = new \pmill\Plesk\CreateClient($config, $params);
$info = $request->process();

var_dump($info);
echo "<BR>Created client id: ".$request->id;