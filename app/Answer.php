<?php

namespace App;

use App\Traits\Votable;
use Illuminate\Database\Eloquent\Model;
use Parsedown;

class Answer extends Model
{
    use Votable;
    /**
     * The attributes that are mass assignable.?
     *
     * @var array
     */
    protected $fillable = [
        'body', 'votes_count', 'user_id'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'created_date', 'body_html', 'status', 'is_best'
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

    public function getBodyHtmlAttribute(): string
    {
        return clean($this->bodyHtml());
    }

    private function bodyHtml(): string
    {
        return Parsedown::instance()->text($this->body);
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
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
