<?php

namespace Jascha030\PluginLib\Exception\Psr11;

use Jascha030\PluginLib\Exception\DoesNotImplementInterfaceException;
use Jascha030\PluginLib\Service\Hookable\HookableInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class DoesNotImplementHookableInterfaceException
 * @package Jascha030\PluginLib\Exception\Psr11
 */
class DoesNotImplementHookableInterfaceException extends DoesNotImplementInterfaceException implements
    ContainerExceptionInterface
{
    /**
     * DoesNotImplementHookableInterfaceException constructor.
     *
     * @param $className
     */
    public function __construct($className)
    {
        parent::__construct($className, HookableInterface::class);
    }
}
