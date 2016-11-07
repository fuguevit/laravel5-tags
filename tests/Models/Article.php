<?php

namespace Fuguevit\Tags\Tests\Models;

use Fuguevit\Tags\Traits\TaggableTrait;
use Fuguevit\Tags\Contracts\TaggableInterface;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements TaggableInterface
{
    use TaggableTrait;

    protected $table = 'articles';

    protected $fillable = [
        'title',
        'body'
    ];
}