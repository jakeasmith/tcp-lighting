<?php

namespace Turk\TcpLighting;

class Device implements DeviceControlInterface
{
	/** @var int Current brightness level */
	private $brightness;

	/** @var Connection Connection object */
	private $connection;

	/** @var string Device ID */
	private $id;

	/** @var string Device name */
	private $name;

	public function __construct(Connection $connection, $id, $name = null, $brightness = null)
	{
		$this->brightness = $brightness;
		$this->connection = $connection;
		$this->id         = $id;
		$this->name       = $name;
	}

	public function getBrightness()
	{
		return $this->brightness;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setBrightness($level)
	{
		$data = '<gip><version>1</version><token>1234567890</token><did>216443150309790617</did><value>' . $level . '</value><type>level</type></gip>';
		$this->connection->call('DeviceSendCommand', $data);
		$this->brightness = $level;

		return $this;
	}

	public function setPower($status)
	{
		$data = '<gip><version>1</version><token>1234567890</token><did>' . $this->id . '</did><value>' . (int)$status . '</value></gip>';
		$this->connection->call('DeviceSendCommand', $data);

		return $this;
	}
}
