<?php

namespace Jascha030\PluginLib\Hookable;

/**
 * Class HookableAbstract
 * Abstract for classes containing methods
 */
abstract class HookableAbstract
{
    /**
     * HookableAbstract constructor.
     *
     * Calls hookMethods
     */
    public function __construct()
    {
        $this->hookMethods();
    }

    /**
     * Abstract hookMethods
     *
     * Should contain logic concerning actions/filters:
     *
     * add_actions,
     * add_filters
     */
    abstract public function hookMethods(): void;
}