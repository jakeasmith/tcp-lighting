<?php

use GuzzleHttp\Client;
use Turk\TcpLighting\Connection;

require __DIR__ . '/vendor/autoload.php';

$connection = new Connection(new Client());

/** @var \Turk\TcpLighting\Room[] $rooms */
$rooms = $connection->getRooms();

$rooms['Living Room 2']->setBrightness(1)->setPower(true);
