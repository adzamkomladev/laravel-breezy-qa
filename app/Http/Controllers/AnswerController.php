<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Question $question
     * @return Factory|JsonResponse|RedirectResponse|Response|View
     */
    public function index(Question $question)
    {
        $answers = $question->answers()->with('user')->latest()->paginate(3);

        if (request()->wantsJson()) {
            return response()->json($answers);
        }

        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Question $question
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function store(Question $question, Request $request)
    {
        $answer = $question->answers()->create(
            $request->validate(['body' => 'required']) + ['user_id' => Auth::id()]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Your answer has been submitted successfully.',
                'answer' => $answer->load('user')
            ]);
        }

        return back()->with('success', 'Your answer has been submitted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @param Answer $answer
     * @return Factory|Response|View
     * @throws AuthorizationException
     */
    public function edit(Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);

        return view('answers.edit', compact('question', 'answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Question $question
     * @param Answer $answer
     * @return JsonResponse|RedirectResponse|Response
     * @throws AuthorizationException
     */
    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);

        $answer->update($request->validate([
            'body' => 'required'
        ]));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Your answer has been updated.',
                'answer' => $answer
            ]);
        }

        return redirect()
            ->route('questions.show', $question->slug)
            ->with('success', 'Your answer has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Answer $answer
     * @param Question $question
     * @return JsonResponse|RedirectResponse|Response
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Question $question, Answer $answer)
    {
        $this->authorize('delete', $answer);

        $answer->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Your answer has been removed.'
            ]);
        }

        return back()->with('success', 'Your answer has been removed.');
    }
}
