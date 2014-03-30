<?php

class HomeController extends \BaseController {

    protected $layout = 'layouts.master';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$singles = User::query()->orderBy('rating_singles', 'desc')->orderBy('name', 'asc')->limit(10)->get();
		$doubles = User::query()->orderBy('rating_doubles', 'desc')->orderBy('name', 'asc')->limit(10)->get();

		$games = Game::query()->orderBy('created_at', 'desc')->limit(10)->get();

		return View::make('users')
					->with('games', $games)
					->with('singles', $singles)
					->with('doubles', $doubles);	
	}

}