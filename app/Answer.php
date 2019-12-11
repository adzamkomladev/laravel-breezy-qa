<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Parsedown;

class Answer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body', 'votes_count', 'user_id'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function (Answer $answer) {
            $answer->question()->increment('answers_count');
        });

        static::deleted(function (Answer $answer) {
            $answer->question()->decrement('answers_count');
        });
    }

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function votes()
    {
        return $this->morphToMany('App\User', 'votable')->withTimestamps();
    }

    public function getBodyHtmlAttribute(): string
    {
        return Parsedown::instance()->text($this->body);
    }

    public function getIsBestAttribute(): bool
    {
        return $this->isBest();
    }

    private function isBest(): bool
    {
        return $this->id === (int)$this->question->best_answer_id;
    }

    public function getStatusAttribute(): string
    {
        return $this->isBest() ? 'answer-accepted' : '';
    }
}
