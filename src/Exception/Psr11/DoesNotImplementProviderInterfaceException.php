<?php

namespace Jascha030\PluginLib\Exception\Psr11;

use Jascha030\PluginLib\Exception\DoesNotImplementInterfaceException;
use Pimple\ServiceProviderInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class DoesNotImplementProviderInterfaceException.
 */
class DoesNotImplementProviderInterfaceException extends DoesNotImplementInterfaceException implements ContainerExceptionInterface
{
    /**
     * DoesNotImplementProviderInterfaceException constructor.
     */
    public function __construct(string $className)
    {
        parent::__construct($className, ServiceProviderInterface::class);
    }
}
