<?php

namespace Jascha030\PluginLib\Exception\Psr11;

use Jascha030\PluginLib\Exception\DoesNotImplementInterfaceException;
use Pimple\ServiceProviderInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class DoesNotImplementProviderInterfaceException
 * @package Jascha030\PluginLib\Exception\Psr11
 */
class DoesNotImplementProviderInterfaceException extends DoesNotImplementInterfaceException implements
    ContainerExceptionInterface
{
    /**
     * DoesNotImplementProviderInterfaceException constructor.
     *
     * @param  string  $className
     */
    public function __construct(string $className)
    {
        parent::__construct($className, ServiceProviderInterface::class);
    }
}
