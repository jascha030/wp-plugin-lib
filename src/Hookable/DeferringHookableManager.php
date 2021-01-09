<?php

namespace Jascha030\PluginLib\Hookable;

use Closure;
use Psr\Container\ContainerInterface;
use Symfony\Component\Uid\Uuid;

final class DeferringHookableManager implements HookableManagerInterface
{
    public const FILTER = 0;

    public const ACTION = 1;

    private const FILTER_TYPES = [
        'action',
        'filter'
    ];

    private $container;

    /**
     * @var array Track hooked method closures
     */
    private $hookedMethods = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    public function registerHookable(string $className, array $classArguments): void
    {
    }

    public function addAction(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $acceptedArguments = 1
    ): void {
        $closure = $this->wrapFilter($class, $method, $this->generateHookIdentifier());

        \add_action($tag, $closure, $priority, $acceptedArguments);
    }

    /**
     * Wraps class and method in a Closure
     *
     * @param  string  $service
     * @param  string  $method
     * @param  string  $identifier
     * @return Closure
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    private function wrapFilter(string $service, string $method, string $identifier): Closure
    {
        $this->hookedMethods[$identifier] = [$service, $method];

        return function (...$args) use ($service, $method, $identifier) {
            if (array_key_exists($identifier, $this->hookedMethods)) {
                return $this->get($service)->{$method}(...$args);
            }
        };
    }

    public function get(string $id)
    {
        return $this->container[$id];
    }

    /**
     * Generate a string to keep track of methods that are hooked as closures
     *
     * @return string
     */
    public function generateHookIdentifier(): string
    {
        return Uuid::v4()->toRfc4122();
    }

    public function addFilter(
        string $tag,
        string $class,
        string $method,
        int $priority = 10,
        int $acceptedArguments = 1
    ): void {
        $closure = $this->wrapFilter($class, $method, $this->generateHookIdentifier());

        \add_filter($tag, $closure, $priority, $acceptedArguments);
    }
}
