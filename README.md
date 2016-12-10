# Laravel Tags

It is a tag package that allows user to easily add tags to the Eloquent entities.

If you are a Chinese user, you can see this documentation. [点击此处](https://github.com/fuguevit/tags/blob/master/README_ZH.md)

## Installation

#### Preparation

Run the following command from your terminal:

```php
composer require "fuguevit/tags: ^1.0.0"
```

or add this to require section in  your composer.json file:

```php
"fuguevit/tags": "^1.0.0"
```

then run `composer update`

#### Setup

Add the Fuguevit\Tags\Providers\TagsServiceProvider Service Provider into the providers array on your config/app.php file and run the following on your terminal:

```php
php artisan vendor:publish --provider="Fuguevit\Tags\Providers\TagsServiceProvider" --tag="config"
php artisan vendor:publish --provider="Fuguevit\Tags\Providers\TagsServiceProvider" --tag="migrations"
```

## Overview

## Function

