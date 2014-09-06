# TCP Lighting API Package for PHP

A PHP package for controlling a TCP Lighting system on a local network.

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

This package allows you to manipulate Rooms and Devices that have already been setup in the TCP system. Both implement a `DeviceControllerInterface` that allows you to control the brightness (0-100) and power (off and on).

```php
<?php

use GuzzleHttp\Client;
use Turk\TcpLighting\Connection;

$connection = new Connection(new Client());

// Get all rooms
$rooms = $connection->getRooms();

// Get a specific room
$office = $rooms['Office'];

// Set room brightness and ensure power is on
$office->setBrightness(50)->setPower(true);


// Get a specific device in the room
$lamp = $office->getDevice('Desk Lamp');

// Turn it up
$lamp->setBrightness(80)->setPower(true);


// You can also get a flat array of all devices
$devices = $connection->getDevices();

// Turn off the desk lamp
$devices['Desk Lamp']->setPower(false);

```
