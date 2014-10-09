<?php

require_once("SplClassLoader.php");
$classLoader = new SplClassLoader('pmill\Plesk', '../src');
$classLoader->register();

$config = array(
	'host'=>'example.com',
	'username'=>'',
	'password'=>'',
);

$params = array(
	'domain'=>'example.com',
	'alias'=>'testalias.example.com',
);

$request = new \pmill\Plesk\DeleteDomainAlias($config, $params);
$info = $request->process();

var_dump($info);