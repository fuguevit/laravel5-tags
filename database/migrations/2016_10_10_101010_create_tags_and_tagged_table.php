<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsAndTaggedTable extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
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

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tagged');
        Schema::dropIfExists('tags');
    }
}
