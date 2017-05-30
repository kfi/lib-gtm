<?php

namespace Inkl\GoogleTagManager;

use Inkl\GoogleTagManager\GoogleTagManager\DataLayer;
use Inkl\GoogleTagManager\Schema\Id;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class GoogleTagManager
{
	private static $instance;
	private $mustache;

	private $dataLayer = [];

	public function __construct()
	{
		$this->mustache = new Mustache_Engine([
			'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../templates/')
		]);
	}

	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function renderTag(Id $id)
	{
		return $this->mustache->render('tag', ['id' => $id, 'dataLayerJson' => json_encode($this->getDataLayerVariables(), JSON_PRETTY_PRINT)]);
	}

	public function renderNoScriptTag(Id $id)
	{
		$queryParams = ['id' => (string)$id];
		$queryParams += $this->getDataLayerVariables();

		return $this->mustache->render('tag.noscript', ['query' => http_build_query($queryParams)]);
	}

	public function getDataLayerVariables()
	{
		return $this->dataLayer;
	}

	public function addDataLayerVariable($name, $value)
	{
		$this->dataLayer[$name] = $value;

		return $this;
	}

	public function removeDataLayerVariable($name)
	{
		unset($this->dataLayer[$name]);

		return $this;
	}

	public function clearDataLayerVariables()
	{
		$this->dataLayer = [];

		return $this;
	}

}
