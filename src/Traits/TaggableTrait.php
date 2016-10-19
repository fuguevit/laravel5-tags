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
     * Return the tags model name.
     *
     * @return string
     */
    public static function getModel()
    {
        return static::$tagsModel;
    }

    /**
     * Set the tags model name.
     *
     * @param  string  $model
     */
    public static function setModel($model)
    {
        static::$tagsModel = $model;
    }

    /**
     * Return the tags delimiter.
     *
     * @return string
     */
    public static function getDelimiter()
    {
        return static::$tagsDelimiter;
    }

    /**
     * Set the tags delimiter.
     *
     * @param $delimiter
     */
    public static function setDelimiter($delimiter)
    {
        static::$tagsDelimiter = $delimiter;
    }

    /**
     * Return MorphToMany Relation of the entity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(static::$tagsModel, 'taggable', 'tagged', 'taggable_id', 'tag_id');
    }

    /**
     * Attach tag|tags to the entity.
     * (zh) 向对象添加一个或一组标签
     *
     * @param  string|array $tags
     *
     * @return bool
     */
    public function tag($tags)
    {
        foreach ($this->prepareTags($tags) as $tag) {
            $this->addTag($tag);
        }
        return true;
    }

    /**
     * Detach tag\tags from the entity, if parameter passed is null, clear all
     * attached tags from the entity.
     * (zh) 从对象中移除给定标签，如传空值，移除该对象所有标签
     *
     * @param null $tags
     *
     * @return bool
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
     * Set the given tags to the entity (Clear the tags attached to the entity below).
     * (zh) 将对象的所有标签重置
     *
     * @param  string|array  $tags
     * @param  string  $type
     * @return bool
     */
    public function setTags($tags, $type = 'name')
    {
        // Prepare the tags
        $tags = $this->prepareTags($tags);
        // Get the current entity tags
        $entityTags = $this->tags->pluck($type)->all();
        // Prepare the tags to be added and removed
        $tagsToAdd = array_diff($tags, $entityTags);
        $tagsToDel = array_diff($entityTags, $tags);
        // Detach the tags
        if (! empty($tagsToDel)) {
            $this->untag($tagsToDel);
        }
        // Attach the tags
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