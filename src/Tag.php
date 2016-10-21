<?php

namespace Fuguevit\Tags;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class Tag.
 */
class Tag extends Model
{
    protected $table = 'tags';

    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['name', 'slug', 'count', 'namespace'];

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        if ($this->exists) {
            $this->tagged()->delete();
        }

        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tagged()
    {
        return $this->hasMany(Config::get('tag.pivotModel'), 'tag_id');
    }

    /**
     * Find a tag by its name.
     *
     * @param Builder $query
     * @param $name
     *
     * @return mixed
     */
    public function scopeName(Builder $query, $name)
    {
        return $query->whereName($name);
    }

    /**
     * Find a tag by its slug.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $slug
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSlug(Builder $query, $slug)
    {
        return $query->whereSlug($slug);
    }
}
