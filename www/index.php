<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

App\Bootstrap::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();

