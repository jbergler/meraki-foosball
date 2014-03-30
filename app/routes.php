<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));
Route::resource('match', 'MatchController');


Route::get('/bbbb', function()
{
    // $users = array(
    //     array("name" => "Bastian Delfs", "email" => "bdelfs@cisco.com"),
    //     array("name" => "Francois Cerna", "email" => "francois.cerna@meraki.net"),
    //     array("name" => "Tom Piggott", "email" => "tpiggott@cisco.com"),
    //     array("name" => "Ruben Ruiz", "email" => "ruben.ruiz@meraki.com"),
    //     array("name" => "Adam Ulfers", "email" => "aulfers@cisco.com"),
    //     array("name" => "Yilmaz Bakay", "email" => "ybakay@cisco.com"),
    //     array("name" => "Johan Kapteijn", "email" => "jkapteij@cisco.com"),
    //     array("name" => "Edo Cannarsa", "email" => "ecannars@cisco.com"),
    //     array("name" => "Rob Wilson", "email" => "rob.wilson@meraki.net"),
    //     array("name" => "Matthew Illingworth", "email" => "matthew.illingworth@meraki.net"),
    //     array("name" => "Hani Braish", "email" => "hbraish@cisco.co"),
    //     array("name" => "Rino Mura", "email" => "rimura@cisco.com"),
    //     array("name" => "Ghazal Asif", "email" => "gasif@cisco.com"),
    //     array("name" => "Anjali Parikh", "email" => "anjparik@cisco.com"),
    //     array("name" => "Antoine Carnet", "email" => "ancarnet@cisco.com")
    // );

    // foreach ($users as $x) {
    //     $user = User::create($x);
    // }


	return View::make('master');
});