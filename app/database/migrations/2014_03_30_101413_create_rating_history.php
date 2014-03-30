<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingHistory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rating_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();

			$table->integer('game_id')->unsigned();
	        $table->foreign('game_id')->references('id')->on('games');
	        $table->integer('user_id')->unsigned();
	        $table->foreign('user_id')->references('id')->on('users');

	       	$table->integer('rating_singles_new');
	       	$table->integer('rating_singles_old');
			$table->integer('rating_doubles_new');
			$table->integer('rating_doubles_old');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rating_history');
	}

}
