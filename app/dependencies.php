<?php
declare(strict_types=1);

use App\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Dibi\Connection;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Connection::class => function (ContainerInterface $c) {
            $env = new Dotenv();
            $env->loadEnv(__DIR__ . '/../.env');
            return new Connection([
                'driver'   => 'mysqli',
                'host'     => $_ENV['DB_HOST'],
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
                'database' => $_ENV['DB_NAME'],
            ]);
        }
    ]);
};
