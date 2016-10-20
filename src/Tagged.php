<?php

namespace Fuguevit\Tags;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tagged
 * @package Fuguevit\Tags
 */
class Tagged extends Model
{
    protected $table = 'tagged';

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

}