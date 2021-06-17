<?php

spl_autoload_register(function($class) {
    $root = dirname(__DIR__);
    $classFile = $root . '/lib/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

// $instanceName is a part of the url where you access your loxopay installation.
// https://{$instanceName}.loxopay.ch
$instanceName = 'YOUR_INSTANCE_NAME';

// $secret is the loxopay secret for the communication between the applications
// if you think someone got your secret, just regenerate it in the loxopay administration
$secret = 'YOUR_SECRET';

$loxopay = new \Loxopay\Loxopay($instanceName, $secret);

$subscription = new \Loxopay\Models\Request\Subscription();
$subscription->setUserId(1);
$subscription->setPsp(4);
$subscription->setPurpose('Test');
$subscription->setAmount(100);
$subscription->setCurrency('CHF');
$subscription->setPaymentInterval('P1M');
$subscription->setPeriod('P1Y');
$subscription->setCancellationInterval('P1M');
try {
    $response = $loxopay->create($subscription);
    var_dump($response);
} catch (\Loxopay\LoxopayException $e) {
    print $e->getMessage();
}
