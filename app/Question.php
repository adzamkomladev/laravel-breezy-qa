<?php

namespace App;

use App\Traits\Votable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Parsedown;

class Question extends Model
{
    use Votable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'votes_count'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'created_date', 'is_favoured', 'favourites_count'
    ];

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = $value;

        $this->attributes['slug'] = Str::slug($value);
    }

    public function getUrlAttribute(): string
    {
        return route('questions.show', $this->slug);
    }

    public function getStatusAttribute(): string
    {
        if ($this->answers_count > 0) {
            if ($this->best_answer_id != null) {
                return "answered-accepted";
            }

            return "answered";
        }

        return "unanswered";
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

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags(clean($this->bodyHtml())), 250);
    }

    public function getIsFavouredAttribute(): bool
    {
        return $this->isFavoured();
    }

    private function isFavoured(): bool
    {
        return $this->favourites()->where('user_id', Auth::id())->count() > 0;
    }

    public function favourites()
    {
        return $this->belongsToMany('App\User', 'favourites')->withTimestamps();
    }

    public function getFavouritesCountAttribute(): int
    {
        return $this->favourites()->count();
    }

    public function answers()
    {
        return $this->hasMany('App\Answer')->orderBy('votes_count', 'DESC');
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;

        $this->save();
    }

    public function toggleFavourite()
    {
        if ($this->isFavoured()) {
            $this->favourites()->detach(Auth::id());
        } else {
            $this->favourites()->attach(Auth::id());
        }
    }

}
