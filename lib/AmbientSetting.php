<?php

namespace Turk\TcpLighting;

class AmbientSetting
{
	/** @var DeviceControlInterface */
	private $device;

	/** @var string Ambient light sensor executable */
	private $lmutracker;

	/** @var int Defines the target range */
	private $range = 8728;

	/** @var int Device brightness step */
	private $step = 1;

	/** @var int Ambient target value between 0-67092480 */
	private $target;

	/**
	 * @param DeviceControlInterface $device     Control Interface
	 * @param string                 $lmutracker Ambient light sensor executable
	 * @param int                    $target     A target value between 0-67092480
	 */
	public function __construct(DeviceControlInterface $device, $lmutracker, $target)
	{
		if (!is_executable($lmutracker)) {
			throw new \InvalidArgumentException('lmutracker must be executable');
		}

		$this->device     = $device;
		$this->lmutracker = $lmutracker;
		$this->target     = $target;
	}

	public function run()
	{
		$continue = true;
		while ($continue) {
			// Unfortunately the TCP lights are not very responsive. Without
			// this sleep changes will be erratic.
			sleep(1);

			$current_level = $this->getAmbientLevel();
			if ($this->isWithinRange($current_level)) {
				echo sprintf('Within target range. Resting at %s.' . "\r", $this->device->getBrightness());
				sleep(10);
				continue;
			}

			$step       = $current_level < $this->target ? $this->step : -$this->step;
			$brightness = min(100, $this->device->getBrightness() + $step);

			if ($brightness < 0) {
				$this->device->setPower(false);
				echo 'Too bright. Off!' . "\r";
				sleep(10);
				continue;
			} elseif ($brightness <= 100) {
				echo sprintf(
					'Setting brightness: %s (%s, ^%s)' . "\r",
					$brightness,
					$current_level,
					abs($this->target - $current_level)
				);
				$this->device->setPower(true)->setBrightness($brightness);
			}

//			echo 'Range:          ' . abs($this->target - $current_level) . PHP_EOL;
//			echo 'Target level:   ' . $this->target . PHP_EOL;
//			echo 'Current level:  ' . $current_level . PHP_EOL;
//			echo 'New brightness: ' . $brightness . PHP_EOL . PHP_EOL;
		}
	}

	private function getAmbientLevel()
	{
		return (int)shell_exec($this->lmutracker);
	}

	private function isWithinRange($level)
	{
		$diff = abs($this->target - $level);

		return $this->range >= $diff;
	}
}
