<?php
namespace Sellastica\Core\Model;

use Nette\Http\IRequest;
use Nette\Utils\Strings;

class Environment
{
	private const SELLASTICA = 'sellastica',
		INTEGROID = 'integroid';

	private const SELLASTICA_CRM = 'crm',
		INTEGROID_CRM = 'crm_integroid';

	/** @var string */
	private $internalProject;
	/** @var bool */
	private $debugMode;
	/** @var bool */
	private $productionMode;
	/** @var IRequest */
	private $request;


	/**
	 * @param string $internalProject
	 * @param bool $debugMode
	 * @param bool $productionMode
	 * @param IRequest $request
	 */
	public function __construct(
		string $internalProject,
		bool $debugMode,
		bool $productionMode,
	 	IRequest $request
	)
	{
		if (!in_array($internalProject, [self::SELLASTICA, self::INTEGROID])) {
			throw new \UnexpectedValueException(sprintf('Unknown internal project "%s"', $internalProject));
		}

		$this->internalProject = $internalProject;
		$this->debugMode = $debugMode;
		$this->productionMode = $productionMode;
		$this->request = $request;
	}

	/**
	 * @return bool
	 */
	public function isSellastica(): bool
	{
		return $this->internalProject === self::SELLASTICA;
	}

	/**
	 * @return bool
	 */
	public function isIntegroid(): bool
	{
		return $this->internalProject === self::INTEGROID;
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

	/**
	 * @return string
	 */
	public function getCrmDatabaseName(): string
	{
		if ($this->isIntegroid()) {
			return self::INTEGROID_CRM;
		} else {
			return self::SELLASTICA_CRM;
		}
	}
}