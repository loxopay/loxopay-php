<?php

use Loxopay\Models\Request\Design;
use Loxopay\Loxopay;
use Loxopay\LoxopayException;

spl_autoload_register(function ($class) {
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

$loxopay = new Loxopay($instanceName, $secret);

$design = new Design();
$design->setUuid('YOUR_UUID');

try {
    $response = $loxopay->getOne($design);
    var_dump($response);
} catch (LoxopayException $e) {
    print $e->getMessage();
}
