<?php
namespace Sellastica\Core;

interface IImage extends IFile
{
	/**
	 * @return string|null
	 */
	function getAlt(): ?string;

	/**
	 * @param string
	 */
	function setAlt(?string $alt);
}
