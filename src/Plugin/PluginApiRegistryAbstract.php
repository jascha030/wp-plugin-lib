<?php

declare(strict_types=1);

namespace Jascha030\PluginLib\Plugin;

use Closure;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementHookableInterfaceException;
use Jascha030\PluginLib\Exception\Psr11\DoesNotImplementProviderInterfaceException;
use Jascha030\PluginLib\Service\Hookable\HookableAfterInitInterface;
use Jascha030\PluginLib\Service\Hookable\LazyHookableInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Class PluginApiRegistryAbstract
 *
 * A class that initializes and keeps track of services that are hooked into the Wordpress Plugin Common API.
 * Typically this is implemented in the form of either a Plugin or a Theme.
 *
 * In this OOP implementation they both essentially, serve the same purpose only with different end goals.
 * Where a Theme is the main implementation of a site's front-end, a Plugin would either implement Back-end
 * functionality and can, in some cases extend or customise, both front and back-end features.
 *
 * @package Jascha030\PluginLib\Plugin
 */
abstract class PluginApiRegistryAbstract implements FilterManagerInterface
{
    /**
     * add_filter
     */
    public const FILTER = 0;
    /**
     * add_action
     */
    public const ACTION = 1;
    /**
     * Prefixes for Wordpress filter methods
     */
    private const FILTER_TYPES = [
        self::ACTION => 'action', self::FILTER => 'filter'
    ];
    /**
     * See methods defined in LazyHookableInterface.
     */
    private const FILTER_RETRIEVAL_METHODS = [
        self::ACTION => 'getActions', self::FILTER => 'getFilters'
    ];

    /**
     * @var array
     */
    private array $afterInitHookables;

    /**
     * @var array|null
     */
    private array $hookableReference;

    /**
     * @var array
     */
    private array $hookedMethods = [];

    /**
     * @var array
     */
    private array $filterTags = [];

    /**
     * @var ContainerInterface|null
     */
    private ContainerInterface $container;

    /**
     * PluginApiRegistryAbstract constructor.
     *
     * @param  array               $hookableReference
     * @param  array               $afterInitHookables
     * @param  ContainerInterface  $container
     */
    public function __construct(array $hookableReference, array $afterInitHookables, ContainerInterface $container)
    {
        $this->hookableReference  = $hookableReference;
        $this->afterInitHookables = $afterInitHookables;
        $this->container          = $container;
    }

    /**
     * Hooks all hookables.
     *
     * @throws DoesNotImplementHookableInterfaceException
     * @throws DoesNotImplementProviderInterfaceException
     */
    final public function run(): void
    {
        $this->hookAfterInitHookables();

        $this->hookLazyReferences();
    }

    /**
     * Set the container responsible for injecting hookables upon
     *
     * @param  ContainerInterface  $container
     *
     * @return $this|FilterManagerInterface
     */
    final public function setContainer(ContainerInterface $container): FilterManagerInterface
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function addAction(
        string $tag,
        string $class,
        string $method,
        int $prio = 10,
        int $arguments = 1
    ): void {
        $uid     = $this->generateHookIdentifier();
        $closure = $this->wrap($class, $method, $uid);

        $this->storeFilterReference($uid, $tag, $class, $method, $prio, $arguments);

        \add_action($tag, $closure, $prio, $arguments);
    }

    /**
     * Generate a string to keep track of methods that are hooked as closures
     *
     * @return string
     */
    final public function generateHookIdentifier(): string
    {
        return Uuid::v4()->toRfc4122();
    }

    /**
     * @inheritDoc
     */
    final public function addFilter(
        string $tag,
        string $class,
        string $method,
        int $prio = 10,
        int $arguments = 1
    ): void {
        $uid     = $this->generateHookIdentifier();
        $closure = $this->wrap($class, $method, $uid);

        $this->storeFilterReference($uid, $tag, $class, $method, $prio, $arguments);

        \add_filter($tag, $closure, $prio, $arguments);
    }

    /**
     * @param  string  $identifier
     */
    final public function removeHookedMethodByIdentifier(string $identifier): void
    {
        $hookReference = $this->hookedMethods[$identifier];
        $id            = $hookReference['id'];

        $this->hookableReference[$id][$identifier] = false;
    }

    /**
     * @inheritDoc
     */
    final public function removeHookable(string $id): void
    {
        foreach ($this->hookableReference[$id] as $identifier) {
            if (isset($this->hookedMethods[$identifier])) {
                unset($this->hookedMethods[$identifier]);
            }

            $this->hookableReference[$id][$identifier] = false;
        }
    }

    /**
     * Wraps class and method in a Closure that calls our container and checks if our hook identifier still exists,
     * In that case, the Class is retrieved from our container to execute hooked method,
     *
     * If not, the method  wil not be executed. This is a workaround for newer wordpress versions,
     * in which it is basically impossible to unhook a closure by reference. (It's possible, it's not failsafe)
     *
     * @param  string  $id
     * @param  string  $method
     * @param  string  $uid
     *
     * @return Closure
     *
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    private function wrap(string $id, string $method, string $uid): Closure
    {
        return function (...$args) use ($id, $method, $uid) {
            if (array_key_exists($uid, $this->hookedMethods)) {
                return $this->container->get($id)->{$method}(...$args);
            }
        };
    }

    /**
     * @param  string  $uid
     * @param  string  $tag
     * @param  string  $id
     * @param  string  $method
     * @param  int     $prio
     * @param  int     $arguments
     */
    private function storeFilterReference(
        string $uid,
        string $tag,
        string $id,
        string $method,
        int $prio,
        int $arguments
    ): void {
        // Store all info on the hooked method.
        $this->hookedMethods[$uid] = compact('tag', 'id', 'method', 'prio', 'arguments');
        // Add uid to array tracking all methods for a single id/class.
        $this->hookableReference[$id][$uid] = true;
        // Add uid to array tracking all methods for a wordpress (action/filter)tag.
        $this->filterTags[$tag][$uid] = true;
    }

    /**
     * Hooks the (non-lazy) hookable classes.
     *
     * @throws DoesNotImplementProviderInterfaceException
     */
    private function hookAfterInitHookables(): void
    {
        foreach ($this->afterInitHookables as $className) {
            if (! is_subclass_of($className, HookableAfterInitInterface::class)) {
                throw new DoesNotImplementProviderInterfaceException($className);
            }
            $this->container->get($className)->hookMethods();
        }
    }

    /**
     * Get predefined actions/filters statically
     * Based on this, wrap and hook all methods
     *
     * @throws DoesNotImplementHookableInterfaceException
     */
    private function hookLazyReferences(): void
    {
        foreach (array_keys($this->hookableReference) as $className) {
            if (! is_subclass_of($className, LazyHookableInterface::class)) {
                throw new DoesNotImplementHookableInterfaceException($className);
            }

            $this->hookClassMethods($className);
        }
    }

    /**
     * @param  string  $serviceClass
     */
    private function hookClassMethods(string $serviceClass): void
    {
        foreach (self::FILTER_RETRIEVAL_METHODS as $key => $method) {
            $addMethod = 'add'.ucfirst(self::FILTER_TYPES[$key]);
            // calls static::getAction() or static::getFilter() on lazy hookable.
            $hooks = $serviceClass::{$method}();
            // Iterates hook types and checks service for methods to hook.
            foreach ($hooks as $tag => $parameters) {
                // Check if single or multiple methods are added to hook.
                if (is_array($parameters) && is_array($parameters[0])) {
                    foreach ($parameters as $params) { // multiple
                        if (! is_array($params)) {
                            $params = [$params];
                        }

                        $this->{$addMethod}($tag, $serviceClass, ...$params);
                    }
                } else { // single
                    if (! is_array($parameters)) {
                        $parameters = [$parameters];
                    }

                    $this->{$addMethod}($tag, $serviceClass, ...$parameters);
                }
            }
        }
    }
}
