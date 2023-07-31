<?php

declare(strict_types=1);

namespace Zorachka\Framework\CommandBus;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\CommandBus as LeagueTacticianCommandBus;
use Zorachka\Framework\CommandBus\Tactician\Middleware\LoggingMiddleware;
use Zorachka\Framework\CommandBus\Tactician\TacticianCommandBus;
use Zorachka\Framework\Container\ServiceProvider;

final class CommandBusServiceProvider implements ServiceProvider
{
    /**
     * @inheritDoc
     */
    public static function getDefinitions(): array
    {
        return [
            LoggingMiddleware::class => static function (ContainerInterface $container) {
                return new LoggingMiddleware($container->get(LoggerInterface::class));
            },
            CommandBus::class => static function (ContainerInterface $container) {
                /** @var CommandBusConfig $config */
                $config = $container->get(CommandBusConfig::class);
                $handlers = $config->handlersMap();

                // Choose our method name
                $inflector = new InvokeInflector();

                // Choose our locator and register our command
                $locator = new InMemoryLocator();
                foreach ($handlers as $commandClassName => $handlerClassName) {
                    $locator->addHandler($container->get($handlerClassName), $commandClassName);
                }

                // Choose our Handler naming strategy
                $nameExtractor = new ClassNameExtractor();

                // Create the middleware that executes commands with Handlers
                $commandHandlerMiddleware = new CommandHandlerMiddleware($nameExtractor, $locator, $inflector);

                $middlewares = \array_map(function ($middlewareClassName) use ($container) {
                    return $container->get($middlewareClassName);
                }, $config->middlewares());
                $tacticianCommandBus = new LeagueTacticianCommandBus(
                    \array_merge($middlewares, [
                        $commandHandlerMiddleware,
                    ])
                );

                return new TacticianCommandBus($tacticianCommandBus);
            },
            CommandBusConfig::class => static fn() => CommandBusConfig::withDefaults(
                [],
                [
                    LoggingMiddleware::class,
                    LockingMiddleware::class,
                ]
            ),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getExtensions(): array
    {
        return [];
    }
}
