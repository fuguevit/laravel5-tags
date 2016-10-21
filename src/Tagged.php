<?php

namespace Fuguevit\Tags;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tagged.
 */
class Tagged extends Model
{
    protected $table = 'tagged';

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}
