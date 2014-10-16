<?php

require_once("SplClassLoader.php");
$classLoader = new SplClassLoader('pmill\Plesk', '../../src');
$classLoader->register();

$config = array(
    'host'=>'example.com',
    'username'=>'username',
    'password'=>'password',
);
