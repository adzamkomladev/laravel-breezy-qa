@if($question->answers_count > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h2>
                            {{ $question->answers_count . " " .  \Illuminate\Support\Str::plural('answers', $question->answers_count)  }}
                        </h2>
                    </div>

                    <hr>

                    @include('layouts._alerts')

                    @foreach($question->answers as $answer)
                        <div class="media">
                            <div class="d-flex flex-column vote-controls">
                                @include('shared._vote_controls', ['model' => $answer, 'text' => 'answer', 'route_name' => 'vote.answer'])
                            </div>

                            <div class="media-body">
                                {!! $answer->body_html !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        @can('update', $answer)
                                            <a href="{{ route('questions.answers.edit', [$question->id, $answer->id]) }}"
                                               class="btn btn-sm btn-outline-info">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $answer)
                                            <form action="{{ route('questions.answers.destroy', [$question->id, $answer->id]) }}"
                                                  class="delete-form" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="alert('Delete?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        @include('shared._author', ['model' => $answer, 'text' => 'Answered'])
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
