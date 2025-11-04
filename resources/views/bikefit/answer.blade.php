@extends('layouts.app')

@section('content')
<div class="container">
    <h1>BikeFit 質問</h1>

    @if(!$question)
        <p>質問が見つかりません。</p>
    @else
        <form method="POST" action="{{ route('bikefit.answer.store') }}">
            @csrf
            <input type="hidden" name="bf_progress" value="{{ $bf_progress }}" />
            <input type="hidden" name="question_id" value="{{ $question->id }}" />
            <p>{{ $question->body }}</p>
            <div>
                @foreach($question->options as $opt)
                    <div>
                        <label>
                            <input type="radio" name="option_id" value="{{ $opt->id }}" required>
                            {{ $opt->label }}
                        </label>
                    </div>
                @endforeach
            </div>
            <button type="submit">次へ</button>
        </form>
    @endif
</div>
@endsection

