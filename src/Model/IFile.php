<?php
namespace Sellastica\Core;

interface IFile
{
	/**
	 * @return string
	 */
	function getFileName(): string;

	/**
	 * @param string $fileName
	 */
	function setFileName(string $fileName);

	/**
	 * @return \Sellastica\Http\FileUrl
	 */
	function getUrl(): \Sellastica\Http\FileUrl;

	/**
	 * @param \Sellastica\Http\FileUrl $url
	 */
	function setUrl(\Sellastica\Http\FileUrl $url);
}
