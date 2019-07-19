Country data for Yii2
=====================

The library provides access to Intl extension data for information about regions, sub-regions, countries, languages, locales, currencies and timezones. Also it has additional classes for information about continents, divisions, cities and measurement systems.

[![Latest Stable Version](https://poser.pugx.org/Tigrov/yii2-country/v/stable)](https://packagist.org/packages/Tigrov/yii2-country)
[![Build Status](https://travis-ci.org/Tigrov/yii2-country.svg?branch=master)](https://travis-ci.org/Tigrov/yii2-country)

The main classes:
- Continent
- Region
- Subregion
- Country (ActiveRecord)
- Division (ActiveRecord)
- City (ActiveRecord)
- Locale
- Language
- Currency
- Timezone
- MeasurementSystem

Limitation
------------

Since 1.1.0 requires Yii >= 2.0.13 and PHP >= 5.5

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tigrov/yii2-country
```

or add

```
"tigrov/yii2-country": "~1.0"
```

to the require section of your `composer.json` file.

Configuration
---------
Once the extension is installed, configure migrations in `config.php`:

```php
return [
    // ...
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrationController',
        ],
    ],
    // ...
];
```

and apply migrations:

```
yii migrate
```

Usage
-----

The classes have access to static methods of [Tigrov/intldata](https://github.com/Tigrov/intldata):
```php
// Get list of codes.
ClassName::codes();

// Get a boolean indicating whether data has a code.
ClassName::has($code);

// Get list of names.
ClassName::names();

// Get name by code.
ClassName::name($code);

// E.g.
Country::names();
Currency::name('USD');
Locale::codes();
Timezone::has('America/New_York');
```

And some of the classes have additional static methods to get more information.

Also each of the classes has follow methods and attributes (perhaps magic):
```php
// Create a model by code
ClassName::create($code);

// All models of a class
ClassName::all();

// Code of the model
$model->code;

// Name of the model
$model->name;
```

**For example:**
```php
$continents = Continent::all();
$europe = Continent::create('EU');
$europe->code; // 'EU'
$europe->name; // 'Europe'

// List of countries
$europe->countries;

$us = Country::create('US');
$us->code; // 'US'
$us->name; // 'United States' (depends of the current locale)

// List of divisions (states)
$us->divisions;

// List of cities
$us->cities;
```
	
Addition
--------

- For additional information about countries (flags, codes, borders and other) use a library  
https://github.com/rinvex/countries 
```$country->rinvex```  
or https://github.com/antonioribeiro/countries
- For more information about Intl extension data see  
https://github.com/Tigrov/intldata  
http://intl.rmcreative.ru/tables?locale=en_US  
http://php.net/manual/book.intl.php

License
-------

[MIT](LICENSE)
