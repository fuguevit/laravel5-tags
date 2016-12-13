<?php

namespace Fuguevit\Tags\Tests;

use Fuguevit\Tags\Tests\Models\Article;

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

    /**
     * Test untag method.
     */
    public function test_it_can_untag_tags()
    {
        $article = $this->createArticle();

        $article->tag(['one', 'two', 'three']);
        $article->untag('one');
        $article = $article->fresh();

        $this->assertSame(['two', 'three'], $article->tags->pluck('slug')->toArray());

        $article->untag();
        $article = $article->fresh();
        $this->assertSame(0, count($article->tags));
    }

    /**
     * Test allTags method.
     */
    public function test_it_can_list_all_tags_in_an_entity_namespace()
    {
        $article1 = $this->createArticle();
        $article2 = $this->createArticle();

        $article1->tag(['one', 'two', 'three']);
        $article2->tag('four,five,six');

        $article1->fresh();
        $article2->fresh();

        $this->assertSame(['one', 'two', 'three', 'four', 'five', 'six'], Article::allTags()->get()->pluck('slug')->toArray());
    }

    /**
     * Test whereTag/withTag/withoutTag methods.
     */
    public function test()
    {
        $article1 = $this->createArticle();
        $article2 = $this->createArticle();

        $article1->tag('laravel,php,foo');
        $article1->fresh();
        $article2->tag(['foo', 'php', 'bar']);
        $article2->fresh();

        $this->assertSame(2, Article::whereTag('php,foo')->get()->count());
        $this->assertSame(1, Article::whereTag(['php', 'laravel'])->get()->count());
        $this->assertSame(0, Article::whereTag(['php', 'laravel', 'foo', 'bar'])->get()->count());
        $this->assertSame(2, Article::withTag(['php', 'laravel'])->get()->count());
        $this->assertSame(2, Article::withTag('php,laravel,foo,bar')->get()->count());
        $this->assertSame(1, Article::withoutTag('bar')->get()->count());
        $this->assertSame(0, Article::withoutTag('bar,foo')->get()->count());
    }
}
