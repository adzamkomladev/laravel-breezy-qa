@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h3>Edit question</h3>
                            <a href="{{ route('questions.index') }}"
                               class="btn btn-outline-secondary">
                                Go back to questions
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('questions.update', $question->id) }}" method="POST">
                            @method('PUT')

                            @include('questions._form', ['buttonText' => 'Update the question'])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
