<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsAndTaggedTable extends Migration
{
    public function up()
    {
        Schema::create('tags', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('namespace');
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('count')->default(0);
        });

        Schema::create('tagged', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('taggable_type');
            $table->unsignedInteger('taggable_id');
            $table->unsignedInteger('tag_id');
        });
    }

    public function down()
    {
        Schema::drop('tagged');
        Schema::drop('tags');
    }
}