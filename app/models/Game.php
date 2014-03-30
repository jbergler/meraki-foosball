<?php

class Game extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'games';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

    public function player1()
    {
        return $this->belongsTo('User');
    }
       
    public function player2()
    {
        return $this->belongsTo('User');
    }
	
	public function player3()
    {
        return $this->belongsTo('User');
    }

    public function player4()
    {
        return $this->belongsTo('User');
    }

    public function player1Won() {
    	return $this->score_player1 >= $this->score_player2;
    }

    public function winner() {    	
    	if ($this->player1Won())
    		return $this->doubles ? $this->player1->name . " & " . $this->player3->name : $this->player1->name;
    	else
    		return $this->doubles ? $this->player2->name . " & " . $this->player4->name : $this->player2->name;
    }
 
    public function loser() {
    	if (!$this->player1Won())
    		return $this->doubles ? $this->player1->name . " & " . $this->player3->name : $this->player1->name;
    	else
    		return $this->doubles ? $this->player2->name . " & " . $this->player4->name : $this->player2->name;
    }

    public function winnerScore() {
    	return $this->player1Won() ? $this->score_player1 : $this->score_player2;
    }

    public function loserScore() {
    	return !$this->player1Won() ? $this->score_player1 : $this->score_player2;
    }
}