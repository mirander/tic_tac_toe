@extends('layout')

@section('content')
    <img style="width: 150px" src="{{ asset('images/game.png') }}" /><br><br>
    <div onclick="game.startGame();" class="btn btn-info">Start game</div>

@endsection
