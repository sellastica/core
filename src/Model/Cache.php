<?php
namespace Sellastica\Core\Model;

use Nette;

class Cache extends Nette\Caching\Cache
{
	use Nette\SmartObject;

	const EXPIRATION = '14 days',
		ENTITY_CACHE_NAMESPACE = 'entity',
		QUERY_CACHE_NAMESPACE = 'query',
		CONTENT_TRANSLATION_CACHE_NAMESPACE = 'translation';

	/** @var array */
	public $onSave = [];
	/** @var array */
	public $onRemove = [];
	/** @var array */
	public $onClean = [];


	/**
	 * @param $key
	 * @param $data
	 * @param array|null $dependencies
	 * @return mixed
	 */
	public function save($key, $data, array $dependencies = null)
	{
		$result = parent::save($key, $data, $dependencies);
		if (is_null($data)) {
			$this->onRemove($key);
		} else {
			$this->onSave($key, $data, $dependencies);
		}

		return $result;
	}

	/**
	 * Removes item from the cache.
	 * @param  mixed
	 * @return void
	 */
	public function remove($key)
	{
		parent::remove($key);
		$this->onRemove($key);
	}

	/**
	 * @param array|null $conditions
	 * @return void
	 */
	public function clean(array $conditions = null)
	{
		parent::clean($conditions);
		$this->onClean($conditions);
	}
}