<?php

require_once("SplClassLoader.php");
$classLoader = new SplClassLoader('pmill\Plesk', '../src');
$classLoader->register();

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$request = new \pmill\Plesk\ListSites($config);
$info = $request->process();

var_dump($info);