<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 3)->create()->each(function (User $user) {
            $user->questions()->saveMany(
                factory(App\Question::class, rand(1, 5))->make()
            )->each(function (App\Question $question) {
                $question->answers()->saveMany(
                    factory(App\Answer::class, rand(1, 5))->make()
                );
            });
        });
    }
}
