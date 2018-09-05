<?php
namespace Sellastica\Core\Model;

use Nette\Http\IRequest;
use Nette\Utils\Strings;

class Environment
{
	private const SELLASTICA = 'sellastica',
		INTEGROID = 'integroid',
		NAPOJSE = 'napojse';

	private const SELLASTICA_CRM = 'crm',
		INTEGROID_CRM = 'crm_integroid',
		NAPOJSE_CRM = 'crm_napojse';

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
		if (!in_array($internalProject, [self::SELLASTICA, self::INTEGROID, self::NAPOJSE])) {
			throw new \UnexpectedValueException(sprintf('Unknown internal project "%s"', $internalProject));
		}

		$this->internalProject = $internalProject;
		$this->debugMode = $debugMode;
		$this->productionMode = $productionMode;
		$this->request = $request;
	}

	/**
	 * @return string
	 */
	public function getInternalProject(): string
	{
		return $this->internalProject;
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
	public function isNapojSe(): bool
	{
		return $this->internalProject === self::NAPOJSE;
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
		} elseif ($this->isNapojSe()) {
			return self::NAPOJSE_CRM;
		} else {
			return self::SELLASTICA_CRM;
		}
	}

	/**
	 * @return bool
	 */
	public function isCli(): bool
	{
		return in_array(PHP_SAPI, ['cli', 'cgi', 'fcgi', 'cgi-fcgi']);
	}
}