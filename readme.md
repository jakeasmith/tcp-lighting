# TCP Lighting API Package for PHP

A PHP package for controlling the TCP Lighting system on a local network.

## Install

Add to composer.json:

```json
{
    "require": {
        "turk/tcp-lighting": "~1.0"
    }
}
```

## Usage

```php
<?php

use GuzzleHttp\Client;
use Turk\TcpLighting\Connection;

$connection = new Connection(new Client());

// Get all rooms (and with it all devices)
$rooms = $connection->getRooms();

// Get a specific room
$office = $rooms['Office'];

// Set room brightness and ensure power is on
$office->setBrightness(50)->setPower(true);

// Get a specific device
$lamp = $office->getDevice('Desk Lamp');

// Turn it up
$lamp->setBrightness(80)->setPower(true);
``
