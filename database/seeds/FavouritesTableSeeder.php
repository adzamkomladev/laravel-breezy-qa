<?php

use App\Question;
use App\User;
use Illuminate\Database\Seeder;

class FavouritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Question::all()->each(function (Question $question) {
            $question->favourites()->attach(
                User::all()->random(
                    rand(1, User::all()->count())
                )->pluck('id')->toArray()
            );
        });
    }
}
