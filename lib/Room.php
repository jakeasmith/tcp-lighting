<?php

namespace Turk\TcpLighting;

class Room implements DeviceControlInterface
{
	/** @var int Brightness */
	private $brightness = null;

	/** @var Connection */
	private $connection;

	/** @var Device[] */
	private $devices = [];

	/** @var int Room ID */
	private $id;

	/** @var string|null Room name */
	private $name;

	public function __construct(Connection $connection, $id, $name = null)
	{
		$this->connection = $connection;
		$this->id         = $id;
		$this->name       = $name;
	}

	public function addDevice(Device $device)
	{
		$this->devices[$device->getName()] = $device;
	}

	public function getBrightness()
	{
		$brightness = $this->brightness;
		if ($this->brightness === null) {
			$brightness = 0;
			foreach ($this->devices as $device) {
				$brightness += $device->getBrightness();
			}

			$brightness = round($brightness / count($this->devices));
		}

		return $brightness;
	}

	/**
	 * @param string $name Device name
	 * @return Device
	 */
	public function getDevice($name)
	{
		if (!isset($this->devices[$name])) {
			throw new \InvalidArgumentException('Device does not exist: ' . $name);
		}

		return $this->devices[$name];
	}

	/**
	 * @return Device[]
	 */
	public function getDevices()
	{
		return $this->devices;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param int $level
	 * @return $this
	 */
	public function setBrightness($level)
	{
		$data = '<gip><version>1</version><token>1234567890</token><rid>' . $this->id . '</rid><value>' . $level . '</value><type>level</type></gip>';
		$this->connection->call('RoomSendCommand', $data);
		$this->brightness = $level;

		return $this;
	}

	/**
	 * @param bool $status
	 * @return $this
	 */
	public function setPower($status)
	{
		$data = '<gip><version>1</version><token>1234567890</token><rid>' . $this->id . '</rid><value>' . (int)$status . '</value></gip>';
		$this->connection->call('RoomSendCommand', $data);

		return $this;
	}
}
