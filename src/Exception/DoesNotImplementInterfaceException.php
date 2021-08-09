<?php

namespace Jascha030\PluginLib\Exception;

/**
 * Class DoesNotImplementInterfaceException.
 */
class DoesNotImplementInterfaceException extends \Exception
{
    /**
     * DoesNotImplementInterfaceException constructor.
     */
    public function __construct(string $className, string $interface)
    {
        parent::__construct("Class \"{$className}\" does not implement \"{$interface}\"");
    }
}
