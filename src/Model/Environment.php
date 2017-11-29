<?php
namespace Sellastica\Core\Model;

use Nette\Http\IRequest;
use Nette\Utils\Strings;

class Environment
{
	/** @var bool */
	private $debugMode;
	/** @var bool */
	private $productionMode;
	/** @var IRequest */
	private $request;


	/**
	 * @param bool $debugMode
	 * @param bool $productionMode
	 * @param IRequest $request
	 */
	public function __construct(
		bool $debugMode,
		bool $productionMode,
	 	IRequest $request
	)
	{
		$this->debugMode = $debugMode;
		$this->productionMode = $productionMode;
		$this->request = $request;
	}

	/**
	 * @return bool
	 */
	public function isFrontend(): bool
	{
		return !$this->isAdmin();
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool
	{
		return Strings::startsWith($this->request->getUrl()->getPath(), '/admin/');
	}

	/**
	 * @return bool
	 */
	public function isProductionMode(): bool
	{
		return $this->productionMode;
	}

	/**
	 * @return bool
	 */
	public function isDebugMode(): bool
	{
		return $this->debugMode;
	}
}