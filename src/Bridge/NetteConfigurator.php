<?php
namespace Sellastica\Core\Bridge;

use Nette;

class NetteConfigurator extends Nette\Configurator
{
	/** @var Nette\Loaders\RobotLoader */
	private $loader;

	/**
	 * @return Nette\Loaders\RobotLoader
	 */
	public function getLoader()
	{
		if (!isset($this->loader)) {
			$this->loader = $this->createRobotLoader();
		}

		return $this->loader;
	}
}