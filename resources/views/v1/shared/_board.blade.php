@foreach($board as $key => $b)
    <buttom
        @if ($b == '')
            onclick="game.setMove('{{$key}}', '{{$gameId}}');"
        @endif
    >{{$b}}</buttom>
@endforeach
