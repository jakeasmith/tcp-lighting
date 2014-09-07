<?php

use GuzzleHttp\Client;
use Turk\TcpLighting\Connection;

require __DIR__ . '/vendor/autoload.php';

$connection = new Connection(new Client());
$connection->setHost('192.168.0.21');

$items  = $connection->getDevices();
$device = $items['Right'];

$lmu      = __DIR__ . '/lmutracker';
$ambiance = new \Turk\TcpLighting\AmbientSetting($device, $lmu, 75000);

$ambiance->run();
