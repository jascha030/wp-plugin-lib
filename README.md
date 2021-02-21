WP Plugin Library for OOP
=========================

A backbone for utilising the [Wordpress Plugin API](https://codex.wordpress.org/Plugin_API#Introduction),
using `php@7.4` and OOP.

Requirements
============

* PHP 7.4.*
* Wordpress >= 5.5.0

Installation
============

```shell
composer require jascha030/wp-plugin-lib
```

Usage
=====
The easiest way to explain usage is with an example of the main plugin file.

The name of the package might be deceiving, Plugin refers to the WP Plugin API, which means that it can be used for
either a plugin or a theme.
In this example we pretend to be building a plugin, but for a theme we would just write our code in the theme's
`functions.php` file instead of the main plugin file.

`main-plugin-file.php`
```php
<?php
/**
 * Plugin jascha030/wp-plugin-lib
 *
 * @package   Jascha030
 * @author    Jascha van Aalst
 * @copyright 2021
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 *                  
 * Plugin Name: Package Tester
 * Plugin URI: https://github.com/jascha030/wp-plugin-lib
 * Description: Test plugin for package jascha030/wp-plugin-lib
 * Version: 1.0.0
 * Author: Jascha030
 * Author URI: https://github.com/jascha030.
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Jascha030;

use Exception;
use Jascha030\PackageTest\Hookable\TestingAfterInitHookable;
use Jascha030\PackageTest\Hookable\TestingHookable;
use Jascha030\PackageTest\PackageTestPlugin;
use Jascha030\PluginLib\Container\Config\Config;

use function Jascha030\PluginLib\Functions\buildPlugin;

/**
 * Check if Wordpress' ABSPATH const is loaded
 */
if (! defined('ABSPATH')) {
    die('Forbidden');
}

/**
 * Get autoloader
 */
$autoloaderPath = __DIR__.'/vendor/autoload.php';

if (! is_readable($autoloaderPath)) {
    throw new \RuntimeException(sprintf('Could not find file \'%s\'. It is generated by Composer. Use \'install --prefer-source\' or \'update --prefer-source\' Composer commands to move forward.',
        $autoloaderPath));
}

include $autoloaderPath;

add_action('plugins_loaded', function () {
    // Set the hookable classes, ServiceProvider and PostTypes. 
    $config = (new Config('Package test plugin', __FILE__))->setHookables([
        TestingHookable::class,
        TestingAfterInitHookable::class
    ]);
    
    // The main plugin class extends PluginApiRegistryInterface, which implements FilterManagerInterface.
    $plugin = buildPlugin($config, PackageTestPlugin::class);
    // Injected dependencies will be hooked after calling the run() method.
    $plugin->run();
});
```
### Hookables

The plugin revolves around classes implementing `HookableInterface` or one of it's extended interfaces. Hookables 
should only contain public methods that are hooked to wordpress' filters or actions.

I recommend using the `LazyHookableInterface`, classes implementing this will only be constructed upon the first 
call to a hook containing one of it's methods.

Here's an example of a class Implementing `LazyHookableInterface`:

`TestingHookable.php`
```php
<?php

namespace Jascha030\PackageTest\Hookable;

use Jascha030\PluginLib\Service\Hookable\LazyHookableInterface;

class TestingHookable implements LazyHookableInterface
{
    public static array $actions = [
        // 'hook' => ['method', priority, numberOfArguments]
        // If the prio and number are default you hook a method with call as `'hook' => 'method',`
        'init' => ['initMethod', 10, 1]
    ];

    public static array $filters = [];

    public static function getActions(): array
    {
        return static::$actions;
    }

    public static function getFilters(): array
    {
        return static::$filters;
    }
    
    final public function initMethod(): void
    {
        echo 'What\'s up, twitter world?';
        die();
    }
}
```

### Providers

The standard container used is the Psr11 wrapper from the `pimple/pimple` package.
Providers follow pimple's `ServiceProviderInterface`.
