<?php

namespace Fuguevit\Tags\Tests;

class TaggableTraitTest extends TestCase
{
    /**
     * Taggable can add tag.
     */
    public function test_it_can_add_tag()
    {
        $article = $this->createArticle();
        $article2 = $this->createArticle();

        $article->tag('chinese');
        $article2->tag(['chinese']);

        $article->fresh();
        $article2->fresh();

//        dd($article->tags->pluck('slug')->toArray());
        $this->assertSame(['chinese'], $article->tags->pluck('name')->toArray());
        $this->assertSame(['chinese'], $article2->tags->pluck('name')->toArray());
    }

}