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
			$table->integer('user_id');
			$table->string('title', 255)->nullable();
			$table->string('description', 255)->nullable();
			$table->text('url');
			$table->smallInteger('public')->default(0);
			$table->smallInteger('thumbnail')->default(0);
			$table->integer('hit_cnt')->default(0);
			$table->smallInteger('pin')->default(0);
			$table->timestamps();
			$table->dateTime('thumbnail_request_at')->nullable();
			$table->integer('thumbnail_request_cnt')->default(0);
			$table->index('user_id');
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
