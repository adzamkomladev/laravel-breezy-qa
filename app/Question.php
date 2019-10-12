<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body'
    ];

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = $value;

        $this->attributes['slug'] = Str::slug($value);
    }

    public function getUrlAttribute(): string
    {
        return route('questions.show', $this->id);
    }

    public function getStatusAttribute(): string
    {
        if ($this->answers > 0) {
            if ($this->best_answer_id != null) {
                return "answered-accepted";
            }

            return "answered";
        }

        return "unanswered";
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
