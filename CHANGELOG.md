### 1.1.0

* Added ability to configure Plugins through (array configurations in) config files.
* Changes to `.editorconfig` (still adhering to PSR-12).
* Started to work out all PHPUnit tests that should have been written during development :sweat_smile:
* Added `ConfigTest`, 100% coverage for `ConfigAbstract`, 20% for `Config`
    * Added Phpunit.xml
    * Added Cache and Coverage

* Current Coverage:

| **Type**   | **%** | **#/of** |
|---|---|---|
| Classes: | 17.65% | (3/17) |
| Methods: | 25.00% | (18/72) |
| Lines: | 23.21% | (68/293) |

### 1.0.5

* Fixed issue where `PluginApiRegistryAbstract` returns the hookable locator instead of the main Container object.

### 1.0.4

* Set ContainerInterface instead of Locator in `PluginApiRegistryAbstract`.

