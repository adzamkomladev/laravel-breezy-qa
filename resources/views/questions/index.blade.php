@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h3>Question</h3>
                            <a href="{{ route('questions.create') }}"
                               class="btn btn-outline-secondary">
                                Ask question
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @include('layouts._alerts')

                        @each('questions._excerpt', $questions, 'question', 'questions._no-questions')

                        <div class="d-flex justify-content-center mt-4">
                            {{ $questions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
