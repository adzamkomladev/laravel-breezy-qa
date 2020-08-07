@csrf

<div class="form-group">
    <label for="title">Question title</label>
    <input
            type="text"
            name="title"
            id="title"
            value="{{ old('title', $question->title ?? '') }}"
            class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
            placeholder="Enter the title of the question..."
            aria-describedby="questionHelpId">
    <small id="questionHelpId" class="text-muted">
        Enter a valid question title
    </small>
    @if ($errors->has('title'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('title') }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    <label for="body">Explain the question</label>
    <textarea
            name="body"
            id="body"
            rows="10"
            class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}"
            placeholder="Enter the body of the question..."
            aria-describedby="questionHelpId">{{ old('body', $question->body ?? '') }}</textarea>
    <small id="questionHelpId" class="text-muted">
        Explain the question entered above
    </small>
    @if ($errors->has('body'))
        <div class="invalid-feedback">
            <strong>{{ $errors->first('body') }}</strong>
        </div>
    @endif
</div>
<div class="form-group">
    <button type="submit" class="btn btn-lg btn-outline-success">
        {{ $buttonText }}
    </button>
</div>
