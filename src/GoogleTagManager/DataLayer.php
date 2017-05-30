<?php

namespace Inkl\GoogleTagManager\GoogleTagManager;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class DataLayer
{
	private static $instance;
	private $data = [];
	private $mustache;

	public function __construct()
	{
		$this->mustache = new Mustache_Engine([
			'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../../templates/')
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

	public function addVariable($name, $value)
	{
		$this->data[$name] = $value;

		return $this;
	}

	public function render()
	{
		return $this->mustache->render('tag.dataLayer', ['data' => json_encode($this->data)]);
	}
}


