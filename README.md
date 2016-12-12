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

You can easily reset your Eloquent model like below:

Use the Fuguevit\Tags\Traits\TaggableTrait and implement the Fuguevit\Tags\Contracts\TaggableInterface interface.

```php
<?php
    use Fuguevit\Tags\Contracts\TaggableInterface;
    use Fuguevit\Tags\Traits\TaggableTrait;
    
    class User extends Model implements TaggableInterface
    {
        use TaggableTrait;
        // orign content ...
    }
?>
```

Then you can use :

```php
$user->tag("foo,bar,laravel");              // add three tags named foo,bar,laravel 
$user->tag(["foo","bar","laravel"]);        // the same
$user->untag("foo);                         // untag foo
$user->untag();                             // untag all
$user->tags;                                // all tags morphed
```

Total methods will be described in next section.

## Methods

The following methods are available:

**Fuguevit\Tags\Traits\TaggableTrait**

```php
    public static function allTags();
    public function tags();
    public function tag($tags);
    public function untag($tags = null);
    public function setTags($tags, $type = 'name');
    public function addTag($name);
    public function removeTag($name);
    public static function scopeWhereTag(Builder $query, $tags, $type = 'slug');
    public static function scopeWithTag(Builder $query, $tags, $type = 'slug');
    public static function scopeWithoutTag(Builder $query, $tags, $type = 'slug');
```