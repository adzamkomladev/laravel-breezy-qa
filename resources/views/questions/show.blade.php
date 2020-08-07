@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="d-flex justify-content-between">
                                <h3>{{ $question->title }}</h3>
                                <a href="{{ route('questions.index') }}"
                                   class="btn btn-outline-secondary">
                                    Go back to questions
                                </a>
                            </div>
                        </div>

                        <hr>

                        <div class="media">
                            <div class="d-flex flex-column vote-controls">
                                <vote-control :model="{{ $question }}" text="question">
                                </vote-control>
                            </div>

                            <div class="media-body">
                                {!! $question->body_html !!}
                                <div class="float-right">
                                    <user-info :model="{{  $question }}" text="Asked"></user-info>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <answers-view :question="{{ $question }}"></answers-view>
    </div>
@endsection
