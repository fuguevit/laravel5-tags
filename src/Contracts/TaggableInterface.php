<?php

namespace Fuguevit\Tags\Contracts;

interface TaggableInterface
{
    /**
     * Return all tags under the entity namespace.
     *
     * @return mixed
     */
    public static function allTags();

    /**
     * Return MorphToMany Relation of the entity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags();

    /**
     * Attach tag|tags to the entity.
     * (zh) 向对象添加一个或一组标签.
     *
     * @param string|array $tags
     *
     * @return bool
     */
    public function tag($tags);

    /**
     * Detach tag\tags from the entity, if parameter passed is null, clear all
     * attached tags from the entity.
     * (zh) 从对象中移除给定标签，如传空值，移除该对象所有标签.
     *
     * @param null $tags
     *
     * @return bool
     */
    public function untag($tags = null);

    /**
     * Set the given tags to the entity (Clear the tags attached to the entity below).
     * (zh) 将对象的所有标签重置.
     *
     * @param string|array $tags
     * @param string       $type
     *
     * @return bool
     */
    public function setTags($tags, $type = 'name');

    /**
     * Attach the given tag to the entity.
     * (zh) 向对象提添加一个标签.
     *
     * @param string $name
     *
     * @return void
     */
    public function addTag($name);

    /**
     * Detaches the given tag from the entity.
     * (zh) 从对象中移除一个标签.
     *
     * @param string $name
     *
     * @return void
     */
    public function removeTag($name);
}
