# :zap: Plugin Action Links
> WordPress module to add the list of links to display on the plugins page beside the activate and deactivate links.

[![Build Status](https://travis-ci.com/mohamdio/wp-plugin-action-links.svg?token=bRxzrnGmzUbSj8ogmJVZ&branch=dist)](https://travis-ci.com/mohamdio/wp-plugin-action-links) [![License: GPL-2.0+](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)

## Installation

#### :heavy_exclamation_mark: Minimum requirements

**PHP:** 5.6

**WordPress:** 4.6

#### :heavy_minus_sign: Install with composer

Run the following in your terminal to install **wp-plugin-action-links** with [Composer](https://getcomposer.org/).

```
composer require jozoor/wp-plugin-action-links
```

As **wp-plugin-action-links** uses [PSR-4](http://www.php-fig.org/psr/psr-4/) autoloading you will need to use Composers autoloader. Below is a basic example of getting started with the class, though your setup maybe different depending on how you are using composer, this example below show you simple usage of create new post type:

```php
require_once __DIR__ . '/vendor/autoload.php';

use JO\Module\PluginActionLinks\Links;
```

See Composers [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working Composer and autoloading.

#### :heavy_minus_sign: Download the module and include it manually

If you don't using composer and want to to include the library manually, you can do that, this example below show you simple usage of create new post type:

```php
/**
 * load main file
 */
// for add Links
require_once __DIR__ . '/wp-plugin-action-links/src/Links.php';

use JO\Module\PluginActionLinks\Links;
```

## Usage

You can add list of links to display on the plugins page, beside the activate and deactivate links, for example:

```php

// use the module
use JO\Module\PluginActionLinks\Links;

// default example for add new links
// new Links($plugin_basename, $links_position)
// $plugin_basename: string
//     - plugin_basename(__FILE__) (if you use module in the main plugin file)
//     - if you using module inside nested folders, you should define
//     plugin_basename correctly to get the correct plugin folder and name, 
//     or you can add it manually like that: 'my-test-plugin/my-test-plugin.php'
// $links_position: string (optional)
//     - 'after': after default plugin links (default option)
//     - 'before': before default plugin links

// define which plugin for add links to it
$my_plugin_links = new Links(plugin_basename(__FILE__));

// you can also define new links before default links like that:
$my_plugin_links = new Links(plugin_basename(__FILE__), 'before');

// add new links for my plugin
$my_new_links = [
    [
        'settings' => '<a href="'. esc_url( get_admin_url(null, 'options-general.php') ) .'">' . __('Settings', 'text-domain') . '</a>'
    ],
    [
        'support' => '<a href="https://help.jozoor.com" target="_blank">' . __('Support', 'text-domain') . '</a>'
    ],
    [
        'more-plugins' => '<a href="https://plugins.jozoor.com" target="_blank">' . __('More plugins by Jozoor', 'text-domain') . '</a>'
    ],
];
$my_plugin_links-> add($my_new_links);

// so now you will get new links for your plugin

```

## Author

**Mohamed Abd Elhalim**

- [site](https://mohamd.io/)
- [twitter](https://twitter.com/mohamdio)
