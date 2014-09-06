<?php

namespace Turk\TcpLighting;

use GuzzleHttp\Client;

class Connection
{
	/** @var Client Guzzle client */
	private $client;

	/** @var string Connection host */
	private $host = 'http://lighting.local';

	/** @var string API Endpoint */
	private $endpoint = '/gwr/gop.php';

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function call($command, $data)
	{
		$url = $this->host . $this->endpoint;
		$res = $this->client->post($url, ['body' => ['cmd' => $command, 'data' => $data, 'fmt' => 'json']]);

		return $res->xml();
	}

	/**
	 * @return Device[]
	 */
	public function getDevices()
	{
		$data     = '<gwrcmds><gwrcmd><gcmd>RoomGetCarousel</gcmd><gdata><gip><version>1</version><token>1234567890</token><fields>name,image,imageurl,control,power,product,class,realtype,status</fields></gip></gdata></gwrcmd></gwrcmds>';
		$response = $this->call('GWRBatch', $data);

		$devices = [];
		foreach ($response->gwrcmd->gdata->gip->room as $node) {
			foreach ($node->device as $device) {
				$devices[(string)$device->name] = new Device($this, (int)$device->did, (string)$device->name);
			}
		}

		return $devices;
	}

	/**
	 * @return Room[]
	 */
	public function getRooms()
	{
		$data     = '<gwrcmds><gwrcmd><gcmd>RoomGetCarousel</gcmd><gdata><gip><version>1</version><token>1234567890</token><fields>name,image,imageurl,control,power,product,class,realtype,status</fields></gip></gdata></gwrcmd></gwrcmds>';
		$response = $this->call('GWRBatch', $data);

		$rooms = [];
		$nodes = $response->gwrcmd->gdata->gip->room;
		foreach ($nodes as $node) {
			$room = new Room($this, (int)$node->rid, (string)$node->name);
			foreach ($node->device as $device) {
				$room->addDevice(new Device($this, (int)$device->did, (string)$device->name));
			}

			$rooms[(string)$node->name] = $room;
		}

		return $rooms;
	}
}
