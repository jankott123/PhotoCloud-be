<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;


class Bootstrap
{
	public static function boot(): Configurator
	{
		$configurator = new Configurator;
		$appDir = dirname(__DIR__);

		$configurator->setDebugMode(TRUE); // enable for your remote IP
		
		$configurator->enableTracy($appDir . '/log');
	//	$configurator->enableTracy(sys_get_temp_dir());
	
		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(sys_get_temp_dir());
		//$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig($appDir . '/config/common.neon');
		$configurator->addConfig($appDir . '/config/credentialsDb.neon');
		$configurator->addConfig($appDir . '/config/local.neon');

		return $configurator;
	}
}
