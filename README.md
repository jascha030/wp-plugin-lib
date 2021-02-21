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

The name of the package might be deceiving, Plugin refers to the WP Plugin API, which means that it can be used for
either a plugin or a theme.

In this example we pretend to be building a plugin, but for a theme we would just write our code in the theme's
`functions.php` file instead of the main plugin file.
