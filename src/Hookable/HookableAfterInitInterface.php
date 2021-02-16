<?php

namespace Jascha030\PluginLib\Hookable;

interface HookableAfterInitInterface extends HookableInterface
{
    public function hookMethods(): void;
}