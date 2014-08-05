<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 255);
			$table->string('password', 255);
			$table->string('email', 255)->nullable();
			$table->dateTime('last_login')->nullable();
			$table->text('prefers')->nullable();
			$table->timestamps();
			$table->unique('username');
			$table->unique('email');
            $table->text("remember_token")->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
