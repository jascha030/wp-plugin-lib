<?php

namespace Jascha030\PluginLib\Exception;

/**
 * Class DoesNotImplementInterfaceException
 *
 * @package Jascha030\PluginLib\Exception
 */
class DoesNotImplementInterfaceException extends \Exception
{
    /**
     * DoesNotImplementInterfaceException constructor.
     *
     * @param string $className
     * @param string $interface
     */
    public function __construct(string $className, string $interface)
    {
        parent::__construct("Class \"{$className}\" does not implement \"{$interface}\"");
    }
}
