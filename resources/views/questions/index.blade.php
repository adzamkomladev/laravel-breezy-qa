@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Questions</div>

                <div class="card-body">
                    @foreach ($questions as $question)
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mt-0">
                                <a href="{{ $question->url }}">{{ $question->title }}</a>
                            </h3>
                            <p class="lead">
                                <a href="{{ $question->user->url }}" >
                                    {{ $question->user->name }}
                                </a>
                                <small class="text-mute">
                                    {{ $question->created_at->diffForHumans() }}
                                </small>
                            </p>
                            {{ \Illuminate\Support\Str::limit($question->body, 250) }}
                        </div>
                    </div>
                    <hr>
                    @endforeach

                    <div class="d-flex justify-content-center">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
