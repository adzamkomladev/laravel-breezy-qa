<?php

namespace App\Traits;

trait Votable
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function votes()
    {
        return $this->morphToMany('App\User', 'votable')->withTimestamps();
    }
}
