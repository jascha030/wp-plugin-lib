### 1.1.0

* Added ability to configure Plugins through (array configurations in) config files.
* Changes to `.editorconfig` (still adhering to PSR-12).
* Started to work out all PHPUnit tests that should have been written during development :sweat_smile:
    * Configtests
    * Added Phpunit.xml
    * Added Cache and Coverage

* Current Coverage:

| **Type**   | **%** | **#/of** |
|---|---|---|
| Classes: | 11.76% | (2/17) |
| Methods: | 15.28% | (11/72) |
| Lines: | 18.77% | (55/293) |

### 1.0.5

* Fixed issue where `PluginApiRegistryAbstract` returns the hookable locator instead of the main Container object.

### 1.0.4

* Set ContainerInterface instead of Locator in `PluginApiRegistryAbstract`.

