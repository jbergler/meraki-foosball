<?php

class RatingHistory extends Eloquent {
    protected $table = 'rating_history';
    protected $hidden = array();
    protected $guarded = array();

    public function user()
    {
        return $this->belongsTo('User');
    }
       
    public function game()
    {
        return $this->belongsTo('Game');
    }
}