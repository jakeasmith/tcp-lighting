<?php

use GuzzleHttp\Client;
use Turk\TcpLighting\Connection;

require __DIR__ . '/vendor/autoload.php';

$connection = new Connection(new Client());

$items = $connection->getRooms();

for ($i = 10; $i > 0; $i--) {
	$items['Living Room 2']->setBrightness(rand(1, 30));
}
