<div class="media post">
    <div class="d-flex flex-column counters">
        <div class="votes">
            <strong>{{ $question->votes_count }}</strong>
            {{ Str::plural('vote', $question->votes_count) }}
        </div>
        <div class="answers status {{ $question->status }}">
            <strong>{{ $question->answers_count }}</strong>
            {{ Str::plural('answer', $question->answers_count) }}
        </div>
        <div class="views">
            {{ $question->views . " " .  \Illuminate\Support\Str::plural('view', $question->views) }}
        </div>
    </div>
    <div class="media-body">
        <div class="d-flex justify-content-between">
            <h3 class="mt-0">
                <a href="{{ $question->url }}">
                    {{ $question->title }}
                </a>
            </h3>
            <div>
                @can('update', $question)
                    <a href="{{ route('questions.edit', $question->id) }}"
                       class="btn btn-sm btn-outline-info">
                        Edit
                    </a>
                @endcan

                @can('delete', $question)
                    <form action="{{ route('questions.destroy', $question->id) }}"
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
        </div>

        <p class="lead">
            <a href="{{ $question->user->url }}">
                {{ $question->user->name }}
            </a>
            <small class="text-mute">
                {{ $question->created_at->diffForHumans() }}
            </small>
        </p>
        {{ $question->excerpt }}
    </div>
</div>
