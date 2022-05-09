<?php

use Symfony\Component\Dotenv\Dotenv;
use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';
// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

if ($_ENV['APP_ENV'] !== 'devel') {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Build PHP-DI Container instance
return $containerBuilder->build();
