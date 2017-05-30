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

	/**
	 */
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
		return DataLayer::getInstance();
	}

}
