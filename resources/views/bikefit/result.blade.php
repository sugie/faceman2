@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>BikeFit リザルト</h1>

        <h2>診断 : {{\App\Services\Bikefit\BikefitService::getGenreName($best_genre_id)}}</h2>
        <p><img src="/images/bikefit/genres/{{$best_genre_id}}.png" alt="{{$best_genre_id}}"/></p>
        <div>
            <p>
                {!! nl2br($genre_descriptions) !!}
            </p>
        </div>
    </div>
@endsection

