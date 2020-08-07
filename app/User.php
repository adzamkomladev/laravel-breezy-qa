<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar', 'url', 'is_logged_in'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUrlAttribute(): string
    {
        return '#';
    }

    public function getIsLoggedInAttribute(): bool
    {
        return auth()->check();
    }

    public function getAvatarAttribute(): string
    {
        $email = $this->email;
        $size = 32;

        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function favourites()
    {
        return $this->belongsToMany('App\Question', 'favourites')->withTimestamps();
    }

    public function voteQuestion(Question $question, int $vote)
    {
        $vote_question = $this->voteQuestions();

        $this->vote($vote_question, $question, $vote);
    }

    public function voteQuestions()
    {
        return $this->morphedByMany('App\Question', 'votable')->withTimestamps();
    }

    private function vote(MorphToMany $relationship, Model $model, int $vote)
    {
        if ($relationship->where('votable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        } else {
            $relationship->attach($model, ['vote' => $vote]);
        }

        $model->load('votes');

        $model->update([
            'votes_count' => (int)$model->votes()->pluck('vote')->sum()
        ]);
    }

    public function voteAnswer(Answer $answer, int $vote)
    {
        $vote_answer = $this->voteAnswers();

        $this->vote($vote_answer, $answer, $vote);
    }

    public function voteAnswers()
    {
        return $this->morphedByMany('App\Answer', 'votable')->withTimestamps();
    }
}
