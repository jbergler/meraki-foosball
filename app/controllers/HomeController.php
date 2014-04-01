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
		$singles = User::query()->where('count_singles', '>', 0)->orderBy('rating_singles', 'desc')->orderBy('name', 'asc')->limit(20)->get();
		$doubles = User::query()->where('count_doubles', '>', 0)->orderBy('rating_doubles', 'desc')->orderBy('name', 'asc')->limit(20)->get();

		$games = Game::query()->orderBy('created_at', 'desc')->limit(200)->get();

		return View::make('users')
					->with('games', $games)
					->with('singles', $singles)
					->with('doubles', $doubles);
	}

}
