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
	private $scripts = [];

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

	/**
	 * Render tag
	 *
	 * @param Id $id
	 * @return string
	 */
	public function renderTag(Id $id)
	{
		return $this->mustache->render('tag', [
			'id' => $id,
			'customScripts' => implode(PHP_EOL, $this->getCustomScripts()),
			'dataLayerJson' => json_encode($this->getDataLayerVariables(), JSON_PRETTY_PRINT)
		]);
	}

	/**
	 * Render noscript ag
	 *
	 * @param Id $id
	 * @return string
	 */
	public function renderNoScriptTag(Id $id)
	{
		$queryParams = ['id' => (string)$id];
		$queryParams += $this->getDataLayerVariables();

		return $this->mustache->render('tag.noscript', ['query' => http_build_query($queryParams)]);
	}

	/**
	 * Add js script which is placed after datalayer and before tag
	 * $name is used as internal identifier
	 *
	 * @param string $name
	 * @param string $script
	 * @return $this
	 */
	public function addCustomScript($name, $script)
	{
		$this->scripts[$name] = $script;

		return $this;
	}

	/**
	 * Remove script by name
	 *
	 * @param string $name
	 * @return $this
	 */
	public function removeCustomScript($name)
	{
		unset($this->scripts[$name]);

		return $this;
	}

	/**
	 * Retrieve all scripts
	 *
	 * @return array
	 */
	public function getCustomScripts()
	{
		return $this->scripts;
	}

	/**
	 * Remove all scripts
	 */
	public function clearCustomScripts()
	{
		$this->scripts = [];

		return $this;
	}

	/**
	 * Add datalayer variable
	 *
	 * @param string $name
	 * @param string $value
	 * @return $this
	 */
	public function addDataLayerVariable($name, $value)
	{
		$this->dataLayer[$name] = $value;

		return $this;
	}

	/**
	 * Remove datalayer variable by name
	 *
	 * @param string $name
	 * @return $this
	 */
	public function removeDataLayerVariable($name)
	{
		unset($this->dataLayer[$name]);

		return $this;
	}

	/**
	 * Retrieve all datalayer variables
	 *
	 * @return array
	 */
	public function getDataLayerVariables()
	{
		return $this->dataLayer;
	}

	/**
	 * Remove all datalayer variables
	 *
	 * @return $this
	 */
	public function clearDataLayerVariables()
	{
		$this->dataLayer = [];

		return $this;
	}

}
