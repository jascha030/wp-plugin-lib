<?php

namespace Jascha030\PluginLib\Service\Hookable;

interface HookableAfterInitInterface extends HookableInterface
{
    public function hookMethods(): void;
}
