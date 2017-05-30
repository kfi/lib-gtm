<?php

namespace Inkl\GoogleTagManager;

use Inkl\GoogleTagManager\GoogleTagManager\DataLayer;
use Inkl\GoogleTagManager\Schema\Id;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class GoogleTagManager
{
	private static $instance;

	/** @var Mustache_Engine */
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

	public function getTag(Id $id)
	{
		return $this->mustache->render('tag', ['id' => $id]);
	}

	public function getNoScriptTag(Id $id)
	{
		return $this->mustache->render('tag.noscript', ['id' => $id]);
	}

	public function getDataLayer()
	{
		return $this->mustache->render('tag.dataLayer', ['data' => json_encode($this->dataLayer)]);
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
