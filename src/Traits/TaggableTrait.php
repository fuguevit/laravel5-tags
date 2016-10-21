<?php

namespace Fuguevit\Tags\Traits;

use Illuminate\Support\Facades\Config;

trait TaggableTrait
{
    /**
     * {@inheritdoc}
     */
    public function tags()
    {
        return $this->morphToMany(Config::get('tag.tagModel'), 'taggable', 'tagged', 'taggable_id', 'tag_id');
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
        if (!empty($tagsToRemove)) {
            $this->untag($tagsToRemove);
        }
        if (!empty($tagsToAdd)) {
            $this->tag($tagsToAdd);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addTag($name)
    {
        $tag = $this->createTagsModel()->firstOrNew([
            'slug'      => $this->generateTagSlug($name),
            'namespace' => $this->getEntityClassName(),
        ]);
        if (!$tag->exists) {
            $tag->name = $name;
            $tag->save();
        }
        if (!$this->tags->contains($tag->id)) {
            $tag->update(['count' => $tag->count + 1]);
            $this->tags()->attach($tag);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag($name)
    {
        $namespace = $this->getEntityClassName();
        $tag = $this
            ->createTagsModel()
            ->whereNamespace($namespace)
            ->where(function ($query) use ($name) {
                $query
                    ->orWhere('name', $name)
                    ->orWhere('slug', $name);
            })
            ->first();
        if ($tag) {
            $tag->update(['count' => $tag->count - 1]);
            $this->tags()->detach($tag);
        }
    }

    /**
     * Prepare tags before using.
     *
     * @param $tags
     *
     * @return array
     */
    public function prepareTags($tags)
    {
        if (is_null($tags)) {
            return [];
        }
        if (is_string($tags)) {
            $delimiter = preg_quote(Config::get('tag.delimiter'), '#');
            $tags = array_map('trim',
                preg_split("#[{$delimiter}]#", $tags, null, PREG_SPLIT_NO_EMPTY)
            );
        }

        return array_unique(array_filter($tags));
    }

    /**
     * Create tags model.
     *
     * @return mixed
     */
    public static function createTagsModel()
    {
        return new Config('tag.tagModel');
    }

    /**
     * Generate the tag slug using the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function generateTagSlug($name)
    {
        return call_user_func(Config::get('tag.slugGenerator'), $name);
    }

    /**
     * Return the entity class name.
     *
     * @return string
     */
    protected function getEntityClassName()
    {
        if (isset(static::$entityNamespace)) {
            return static::$entityNamespace;
        }

        return $this->tags()->getMorphClass();
    }
}