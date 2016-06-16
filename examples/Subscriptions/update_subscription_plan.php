<?php

require_once("../config.php");

$params = [
	'filter' => ['id' => 1],
    'plan-guid' => '2',
    //'plan-external-id' => '',
    //'no-plan' => true,
];

$request = new \pmill\Plesk\UpdateSubscriptionPlan($config, $params);
$info = $request->process();

var_dump($info);