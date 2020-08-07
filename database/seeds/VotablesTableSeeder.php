<?php

use App\Answer;
use App\Question;
use App\User;
use Illuminate\Database\Seeder;

class VotablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        App\User::all()->each(function (User $user) {
            $votes = [1, -1];

            $questions = Question::all()->random(
                rand(1, Question::all()->count())
            );

            foreach ($questions as $question) {
                $user->voteQuestion($question, $votes[array_rand($votes)]);
            }

            $answers = Answer::all()->random(
                rand(1, Answer::all()->count())
            );

            foreach ($answers as $answer) {
                $user->voteAnswer($answer, $votes[array_rand($votes)]);
            }
        });
    }
}
