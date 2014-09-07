<?php

namespace Turk\TcpLighting;

interface DeviceControlInterface
{
	/**
	 * @return int
	 */
	public function getBrightness();

	/**
	 * @param int $level
	 * @return $this
	 */
	public function setBrightness($level);

	/**
	 * @param bool $status
	 * @return $this
	 */
	public function setPower($status);
}
