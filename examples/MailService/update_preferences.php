<?php

require_once("../config.php");

/*
 * Enables
 */
$params = [
    'site-id' => 1,
    'nonexistent-user' => \pmill\Plesk\Helper\MailPreferences::NONEXISTENT_USER_BOUNCE,
    'mailservice' => false,
    'webmail' => true,
    'spam-protect-sign' => true,
    'greylisting' => false,
];

$request = new \pmill\Plesk\MailService\UpdatePreferences($config, $params);

$info = $request->process();
var_dump($info);

if ($info === false) {
    var_dump($request->error->getMessage());
}
