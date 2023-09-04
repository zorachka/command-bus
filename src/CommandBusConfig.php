<?php

declare(strict_types=1);

namespace Zorachka\CommandBus;

final class CommandBusConfig
{
    /**
     * @var array<class-string, class-string>
     */
    private array $handlersMap;
    /**
     * @var array<class-string>
     */
    private array $middlewares;

    private function __construct(array $handlersMap, array $middlewares)
    {
        $this->handlersMap = $handlersMap;
        $this->middlewares = $middlewares;
    }

    /**
     * @param array<class-string, class-string> $handlersMap
     * @param class-string[] $middlewares
     */
    public static function withDefaults(
        array $handlersMap = [],
        array $middlewares = [],
    ): self {
        return new self($handlersMap, $middlewares);
    }

    /**
     * @param class-string $commandClassName
     * @param class-string $handlerClassName
     * @return $this
     */
    public function withHandlerFor(string $commandClassName, string $handlerClassName): self
    {
        $new = clone $this;
        $new->handlersMap[$commandClassName] = $handlerClassName;

        return $new;
    }

    /**
     * @return array<class-string, class-string>
     */
    public function handlersMap(): array
    {
        return $this->handlersMap;
    }

    /**
     * @param class-string $middlewareClassName
     * @return $this
     */
    public function withMiddleware(string $middlewareClassName): self
    {
        $new = clone $this;
        $new->middlewares[] = $middlewareClassName;

        return $new;
    }

    /**
     * @return class-string[]
     */
    public function middlewares(): array
    {
        return $this->middlewares;
    }
}
