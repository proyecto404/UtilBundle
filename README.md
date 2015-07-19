README
======

Installation
------------

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

Contents
--------

- **Command**:
  - ExecuteSqlCommand: Symfony console command that executes SQL files in doctrine's default manager.
- **Controller**:
  - ControllerBase: Base class with convenient utility methods for controllers.
- **Form**:
  - FormUtil: Utility class for forms.
  - ValuedPasswordType: Password type that maintains value like other inputs.
- **Http**:
  - JsonResponse: Better Json response object.
- **Lib**:
  - Check: Implements Design by Contract. 
  - Enum: Base class for enum types.
  - GeopositionHelper: Helper class with geoposition utility methods.
  - PaginationInfo: Represents pagination information of set of data.
- **Model\Builder**:
  - EntityBuilder: Base class for entity builders.
- **Model\Form**:
  - EntityBuilderType: Base class for all EntityBuilder's form types.
- **Repository**:
  - EntityRepositoryInterface: Interface for domain entities repositories.
  - OrderDirections: Enum class with ordering directions.
  - RepositoryHelper: Helper class with utility methods for repositories.
- **Security\Encoder**:
  - Md5NoSaltEncoder: Encode a password in md5 without use the salt.  
  
License
-------

This bundle is under the MIT license. See the complete license in the bundle `LICENSE` file.
