# Changelog

### 1.4.0 - 20-09-2021

* Added SettingsSubMenu class.
* Ran formatter.

### 1.3.1 - 10-08-2021

* Minor (non bug)fixes in config example
* Added and ran new php cs fixer config; `.php-cs-fixer.dist.php` (replacing old `.php-cs-fixer.php`).
* Added `phpunit` and `format` composer script commands
* Minor formatting changes for README.md
* Updated README with new Testing, Formatting and license sections.

### 1.3.0 - 09-06-2021

* Added php-cs-fixer.
* Added config.php.example
* Fix bugs with `ConfigurablePluginApiRegistry` class.
* Fixed `LazyHookableTrait` accidentally being a class.

### 1.2.0 - 04-20-2021

* Added Wrapper classes for better post querying, wraps WP\_Post class.
* Added Activation class, for simplified plugin activation handling.

### 1.1.2 - 03-15-2021

* Forgot to implement 1.1.1 fix on `/includes/plugin-initialisation.php`

### 1.1.1 - 03-15-2021

* Removed a bug which caused an error, requesting `'plugin.postTypes'`
  from the container instead of just `'postTypes'`

### 1.1.0 - 03-15-2021

* Added ability to configure Plugins through (array configurations in)
  config files.
* Changes to `.editorconfig` (still adhering to PSR-12).
* Started to work out all PHPUnit tests that should have been written
  during development :sweat_smile:
* Added `ConfigTest`, 100% coverage for `ConfigAbstract`, 20%
  for `Config`
    * Added Phpunit.xml
    * Added Cache and Coverage

* Current Coverage:

| **Type**   | **%** | **#/of** |
|---|---|---|
| Classes: | 17.65% | (3/17) |
| Methods: | 25.00% | (18/72) |
| Lines: | 23.21% | (68/293) |

### 1.0.5 02-23-2021

* Fixed issue where `PluginApiRegistryAbstract` returns the hookable
  locator instead of the main Container object.

### 1.0.4 - 02-23-2021

* Set ContainerInterface instead of Locator
  in `PluginApiRegistryAbstract`.
