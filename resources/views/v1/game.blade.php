@extends('layout')

@section('content')
    <h2>Tic Tac Toe</h2>
    <div class="board">
        @include('/v1/shared/_board')
    </div>
    <br>
    <div class="text-center">
        <div class="btn btn-info"><a style="color: #fff" href="/">Main page</a></div>
        <div onclick="game.startGame();" class="btn btn-info">Start new game</div>
    </div>
@endsection
