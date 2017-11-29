<?php
namespace Sellastica\Core\Model;

class Collection implements \Countable, \ArrayAccess, \IteratorAggregate
{
	/** @var array */
	protected $items = [];


	/**
	 * @param array $items
	 */
	public function __construct(array $items = [])
	{
		$this->items = $items;
	}

	/**
	 * Immutable
	 *
	 * @return static
	 */
	public function clear(): Collection
	{
		$return = clone $this;
		$return->items = [];
		return $return;
	}

	/**
	 * Immutable setter
	 *
	 * @param $item
	 * @return Collection
	 */
	public function add($item): Collection
	{
		$return = clone $this;
		$return->offsetSet(null, $item);
		return $return;
	}

	/**
	 * Immutable unsetter, unsets by KEY
	 *
	 * @param $key
	 * @return Collection
	 */
	public function unset($key): Collection
	{
		$return = clone $this;
		$return->offsetUnset($key);
		return $return;
	}

	/**
	 * Immutable unsetter, unsets by VALUE
	 *
	 * @param $item
	 * @return Collection
	 */
	public function remove($item): Collection
	{
		$return = $this->filter(function ($v) use ($item) {
			return $v !== $item;
		});
		return $return;
	}

	/**
	 * @param callable $function
	 * @return array
	 */
	public function walk(callable $function): array
	{
		$return = [];
		foreach ($this->items as $key => $item) {
			$return[$key] = $function($item);
		}

		return $return;
	}

	/**
	 * @param callable $function
	 * @return Collection
	 * @throws \RuntimeException
	 */
	public function filter(callable $function): Collection
	{
		$return = new static();
		foreach ($this->items as $key => $value) {
			$result = $function($value);
			if (true === $result) {
				$return[$key] = $value;
			} elseif (!is_bool($result)) {
				throw new \RuntimeException('Filter callback must return boolean');
			}
		}

		return $return;
	}

	/**
	 * @param callable $function
	 * @return bool
	 */
	public function has(callable $function): bool
	{
		return $this->filter($function)->count() > 0;
	}

	/**
	 * @param callable $function
	 * @return mixed|null
	 */
	public function get(callable $function)
	{
		$result = $this->filter($function)->toArray();
		return sizeof($result) ? current($result) : null; //current returns false, not null
	}

	/**
	 * Returns first item or null if no items exist
	 * @return mixed|null
	 */
	public function first()
	{
		if (!sizeof($this->items)) {
			return null;
		}

		reset($this->items);
		return current($this->items);
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return $this->items;
	}

	/**
	 * @param string $separator
	 * @return string
	 */
	public function toString($separator = ','): string
	{
		$array = [];
		foreach ($this->items as $item) {
			$array[] = (string)$item;
		}

		return implode($separator, $array);
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/****************************************************************
	 ******************* Interface implementations ******************
	 ****************************************************************/

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->items);
	}

	/**
	 * @param int|null $key
	 * @param mixed $value
	 */
	public function offsetSet($key, $value)
	{
		if (!isset($key)) {
			$this->items[] = $value;
		} else {
			$this->items[$key] = $value;
		}
	}

	/**
	 * @param mixed $key
	 */
	public function offsetUnset($key)
	{
		if (isset($this->items[$key])) {
			unset($this->items[$key]);
		}
	}

	/**
	 * @param int $key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		if (isset($this->items[$key])) {
			return $this->items[$key];
		}

		return null;
	}

	/**
	 * @param int $key
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return isset($this->items[$key]);
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->items);
	}
}