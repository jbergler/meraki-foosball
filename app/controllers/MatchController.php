<?php

class MatchController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$match = new Game;

		$players = User::query()->lists('name', 'id');
		asort($players);
		$players = array('null' => '-- none --') + $players;

		return View::make('match.create')
				   ->with('match', $match)
				   ->with('players', $players);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(
			Input::all(),
			array(
			    'player1' => 'required|exists:users,id',
			    'player2' => 'required|different:player1|exists:users,id',
			    'player3' => 'sometimes|different:player1|different:player2|exists:users,id',
			    'player4' => 'sometimes|different:player1|different:player2|different:player3|exists:users,id',
			    'score_player1' => 'required|integer|between:0,10',
			    'score_player2' => 'required|integer|between:0,10|different:score_player1'
			)
		);

		if($validator->fails())
		{
			return Redirect::route('match.create')
						   ->withInput()
						   ->withErrors($validator);
		}

		$game = new Game;
		$game->player1_id = Input::get('player1');
		$game->player2_id = Input::get('player2');
		$game->score_player1 = Input::get('score_player1');
		$game->score_player2 = Input::get('score_player2');

		if (Input::has('player3') && Input::has('player4')){
			$game->doubles = true;
			$game->player3_id = Input::get('player3');
			$game->player4_id = Input::get('player4');
		}
		$game->save();

		if ($game->doubles)
		{
			// Doubles ratings are based on average of both players
			$rating_team1 = round(($game->player1->rating_doubles + $game->player3->rating_doubles) / 2);
			$rating_team2 = round(($game->player2->rating_doubles + $game->player4->rating_doubles) / 2);

			// Calculate new doubles ratings
			$rating = new Rating($rating_team1, $rating_team2, $game->score_player1, $game->score_player2);
			$newRatings = $rating->getNewRatings();

			// Calculate rating changes
			$rating_team1_diff = $newRatings['a'] - $rating_team1;
			$rating_team2_diff = $newRatings['b'] - $rating_team2;

			// Create log for player 1
			RatingHistory::create(array(
				'user_id' => $game->player1->id,
				'game_id' => $game->id,
				'rating_singles_old' => $game->player1->rating_singles,
				'rating_singles_new' => $game->player1->rating_singles,
				'rating_doubles_old' => $game->player1->rating_doubles,
				'rating_doubles_new' => $game->player1->rating_doubles + $rating_team1_diff
			));

			// Create log for player 2
			RatingHistory::create(array(
				'user_id' => $game->player2->id,
				'game_id' => $game->id,
				'rating_singles_old' => $game->player2->rating_singles,
				'rating_singles_new' => $game->player2->rating_singles,
				'rating_doubles_old' => $game->player2->rating_doubles,
				'rating_doubles_new' => $game->player2->rating_doubles + $rating_team2_diff
			));

			// Create log for player 3
			RatingHistory::create(array(
				'user_id' => $game->player3->id,
				'game_id' => $game->id,
				'rating_singles_old' => $game->player3->rating_singles,
				'rating_singles_new' => $game->player3->rating_singles,
				'rating_doubles_old' => $game->player3->rating_doubles,
				'rating_doubles_new' => $game->player3->rating_doubles + $rating_team1_diff
			));

			// Create log for player 4
			RatingHistory::create(array(
				'user_id' => $game->player4->id,
				'game_id' => $game->id,
				'rating_singles_old' => $game->player4->rating_singles,
				'rating_singles_new' => $game->player4->rating_singles,
				'rating_doubles_old' => $game->player4->rating_doubles,
				'rating_doubles_new' => $game->player4->rating_doubles + $rating_team2_diff
			));

			// Update Team 1
			$game->player1->rating_doubles = $game->player1->rating_doubles + $rating_team1_diff;
			$game->player1->count_doubles++;
			$game->player1->save();
			$game->player3->rating_doubles = $game->player3->rating_doubles + $rating_team1_diff;
			$game->player3->count_doubles++;
			$game->player3->save();

			// Update Team 2
			$game->player2->rating_doubles = $game->player2->rating_doubles + $rating_team2_diff;
			$game->player2->count_doubles++;
			$game->player2->save();
			$game->player4->rating_doubles = $game->player4->rating_doubles + $rating_team2_diff;
			$game->player4->count_doubles++;
			$game->player4->save();
		}
		else
		{
			// Singles ratings are based on own ratings
			$rating = new Rating($game->player1->rating_singles, $game->player2->rating_singles, $game->score_player1, $game->score_player2);
			$newRatings = $rating->getNewRatings();

			// Create log for player 1
			RatingHistory::create(array(
				'user_id' => $game->player1->id,
				'game_id' => $game->id,
				'rating_singles_old' => $game->player1->rating_singles,
				'rating_singles_new' => $newRatings['a'],
				'rating_doubles_old' => $game->player1->rating_doubles,
				'rating_doubles_new' => $game->player1->rating_doubles
			));

			// Create log for player 2
			RatingHistory::create(array(
				'user_id' => $game->player2->id,
				'game_id' => $game->id,
				'rating_singles_old' => $game->player2->rating_singles,
				'rating_singles_new' => $newRatings['b'],
				'rating_doubles_old' => $game->player2->rating_doubles,
				'rating_doubles_new' => $game->player2->rating_doubles
			));

			// Update ratings
			$game->player1->rating_singles = $newRatings['a'];
			$game->player1->count_singles++;
			$game->player1->save();
			$game->player2->rating_singles = $newRatings['b'];
			$game->player2->count_singles++;
			$game->player2->save();
		}

		return Redirect::to('/');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

class PlayerValidator extends Illuminate\Validation\Validator {

	public function validatePlayers($attribute, $value, $parameters)
	{
		die();
		$this->requireParameterCount(4, $parameters, 'players');

		$player1 = array_get($this->data, $parameters[0]);
		$player2 = array_get($this->data, $parameters[1]);
		$player3 = array_get($this->data, $parameters[2]);
		$player4 = array_get($this->data, $parameters[3]);

		return true;

		if (!isset($player1) || !isset($player2))
		{
			return false;
		}
		if ((isset($player3) && !isset($player4)) || (isset($player4) && !isset($player3)))
		{
			return false;
		}
		if ($player1 == $player2 || $player1 == $player3 || $player1 == $player4 ||
			$player2 == $player3 || $player2 == $player4 ||
			$player3 == $player4)
		{
			return false;
		}
		return true;
	}

}
