<?php

namespace Fuguevit\Tags\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TaggableTrait
{

    /**
     * The Eloquent tags model name.
     *
     * @var string
     */
    protected static $tagsModel = 'Fuguevit\Tags\Tag';

    /**
     * The tags delimiter.
     *
     * @var string
     */
    protected static $tagsDelimiter = ',';


    /**
     * {@inheritdoc}
     */
    public static function getModel()
    {
        return static::$tagsModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function setModel($model)
    {
        static::$tagsModel = $model;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDelimiter()
    {
        return static::$tagsDelimiter;
    }

    /**
     * {@inheritdoc}
     */
    public static function setDelimiter($delimiter)
    {
        static::$tagsDelimiter = $delimiter;
    }

    /**
     * {@inheritdoc}
     */
    public function tags()
    {
        return $this->morphToMany(static::$tagsModel, 'taggable', 'tagged', 'taggable_id', 'tag_id');
    }

    /**
     * {@inheritdoc}
     */
    public function tag($tags)
    {
        foreach ($this->prepareTags($tags) as $tag) {
            $this->addTag($tag);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function untag($tags = null)
    {
        $tags = $tags ?: $this->tags->pluck('name')->all();
        foreach ($this->prepareTags($tags) as $tag) {
            $this->removeTag($tag);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setTags($tags, $type = 'name')
    {
        $tags = $this->prepareTags($tags);
        $entityTags = $this->tags->pluck($type)->all();
        $tagsToAdd = array_diff($tags, $entityTags);
        $tagsToRemove = array_diff($entityTags, $tags);
        if (! empty($tagsToRemove)) {
            $this->untag($tagsToRemove);
        }
        if (! empty($tagsToAdd)) {
            $this->tag($tagsToAdd);
        }
        return true;
    }

    public function addTag($name)
    {
        $tag = $this->createTagsModel()->firstOrNew([
            'slug'      => $this->generateTagSlug($name),
            'namespace' => $this->getEntityClassName(),
        ]);
        if (! $tag->exists) {
            $tag->name = $name;
            $tag->save();
        }
        if (! $this->tags->contains($tag->id)) {
            $tag->update([ 'count' => $tag->count + 1 ]);
            $this->tags()->attach($tag);
        }
    }

    public function removeTag($name)
    {
        $namespace = $this->getEntityClassName();
        $tag = $this
            ->createTagsModel()
            ->whereNamespace($namespace)
            ->where(function ($query) use ($name) {
                $query
                    ->orWhere('name', $name)
                    ->orWhere('slug', $name)
                ;
            })
            ->first()
        ;
        if ($tag) {
            $tag->update([ 'count' => $tag->count - 1 ]);
            $this->tags()->detach($tag);
        }
    }

    /**
     * Prepare tags before using.
     *
     * @param $tags
     * @return array
     */
    public function prepareTags($tags)
    {
        if (is_null($tags)) {
            return [];
        }
        if (is_string($tags)) {
            $delimiter = preg_quote($this->getDelimiter(), '#');
            $tags = array_map('trim',
                preg_split("#[{$delimiter}]#", $tags, null, PREG_SPLIT_NO_EMPTY)
            );
        }
        return array_unique(array_filter($tags));
    }


}