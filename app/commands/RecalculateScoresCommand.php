<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RecalculateScoresCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'foosball:recalculate-scores';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Reset everyones scores to a default and recalculate them";

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$users = User::all();

		foreach ($users as $user) {
			$user->rating_doubles = 750;
			$user->rating_singles = 750;
			$user->save();
		}

		$history = RatingHistory::all();

		foreach($history as $x) {
			$x->delete();
		}

		$games = Game::query()->orderBy('created_at', 'asc')->get();

		foreach ($games as $game) {
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
				$game->player1->save();
				$game->player3->rating_doubles = $game->player3->rating_doubles + $rating_team1_diff;
				$game->player3->save();

				// Update Team 2
				$game->player2->rating_doubles = $game->player2->rating_doubles + $rating_team2_diff;
				$game->player2->save();
				$game->player4->rating_doubles = $game->player4->rating_doubles + $rating_team2_diff;
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
				$game->player1->save();
				$game->player2->rating_singles = $newRatings['b'];
				$game->player2->save();
			}

		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('score', InputArgument::REQUIRED, 'Score to reset to'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
