<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AcceptAnswerController extends Controller
{

    /**
     * @param Answer $answer
     * @return JsonResponse|RedirectResponse
     * @throws AuthorizationException
     */
    public function __invoke(Answer $answer)
    {
        $this->authorize('accept', $answer);

        $answer->question->acceptBestAnswer($answer);

        if (request()->wantsJson()) {
            return response()->json(['answer' => $answer]);
        }

        return back();
    }
}
