<?php

namespace Fuguevit\Tags\Tests;

class TaggableTraitTest extends TestCase
{
    /**
     * Test TaggableTrait can add tag.
     */
    public function test_it_can_add_single_tag()
    {
        $article = $this->createArticle();
        $article2 = $this->createArticle();

        $article->tag('chinese');
        $article2->tag(['chinese']);

        $article = $article->fresh();
        $article2 = $article2->fresh();

        $this->assertSame(['chinese'], $article->tags->pluck('slug')->toArray());
        $this->assertSame(['chinese'], $article2->tags->pluck('slug')->toArray());
    }

    /**
     * Test TaggableTrait can add multiple tags.
     */
    public function test_it_can_add_multiple_tags()
    {
        $article = $this->createArticle();

        $article->tag(['one', 'two', 'three']);
        $article = $article->fresh();

        $this->assertSame(['one', 'two', 'three'], $article->tags->pluck('slug')->toArray());
    }
}
