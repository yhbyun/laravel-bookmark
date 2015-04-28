<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->text('url');
            $table->smallInteger('public')->default(0);
            $table->smallInteger('thumbnail')->default(0);
            $table->integer('hit_cnt')->default(0);
            $table->smallInteger('pin')->default(0);
            $table->timestamps();
            $table->timestamp('thumbnail_request_at')->nullable();
            $table->integer('thumbnail_request_cnt')->default(0);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookmarks');
    }

}
