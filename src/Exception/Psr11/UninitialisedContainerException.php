<?php

namespace Jascha030\PluginLib\Exception\Psr11;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class UninitialisedContainerException extends Exception implements ContainerExceptionInterface
{
    public function __construct(string $class)
    {
        parent::__construct(
            sprintf(
                "%s not set, make sure %s is not called before initialisation.",
                "\"{$class}::\$container\"",
                "{$class}::get()"
            )
        );
    }
}
