<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('games', function(Blueprint $table)
	    {
	        $table->increments('id');
	        $table->timestamps();
	        $table->boolean('doubles')->default(false);
	        $table->integer('player1')->unsigned(); //team1
	        $table->integer('player2')->unsigned(); //team2
	        $table->integer('player3')->unsigned()->nullable(); //team1
	        $table->integer('player4')->unsigned()->nullable(); //team2
	        $table->foreign('player1')->references('id')->on('users');
	        $table->foreign('player2')->references('id')->on('users');
	        $table->foreign('player3')->references('id')->on('users');
	        $table->foreign('player4')->references('id')->on('users');
    		$table->integer('score_player1')->default(0); //team1
			$table->integer('score_player2')->default(0); //team2
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    	Schema::drop('games');
	}

}