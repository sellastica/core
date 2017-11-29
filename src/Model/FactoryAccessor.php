<?php
namespace Sellastica\Core\Model;

abstract class FactoryAccessor
{
	/** @var mixed */
	private $instance;
	/** @var bool */
	private $initialized = false;


	/**
	 * @return mixed
	 */
	abstract public function create();

	/**
	 * @return mixed
	 */
	public function get()
	{
		if (false === $this->initialized) {
			$this->instance = $this->create();
			$this->initialized = true;
		}

		return $this->instance;
	}
}