README
======

## Installation

You can install this bundle using composer

``` bash
$ php composer.phar require proyecto404/util-bundle "dev-master"
```

or add the package to your `composer.json` file directly.

Composer will install the bundle to your project's `vendor/proyecto404` directory.

After you have installed the package, you just need to add the bundle to your `AppKernel.php` file:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Proyecto404\UtilBundle\Proyecto404UtilBundle(),
    );
}
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle `LICENSE` file.
