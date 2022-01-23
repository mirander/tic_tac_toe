<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">
    <title>Tic Tac Toe - Acclaimed blockbuster</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        .board {
            margin: 0 auto;
            width: 286px;
            text-align: center;
            background: #fdfdfd;
            padding: 15px;
            height: 260px;
        }
        .board buttom {
            width: 75px;
            height: 75px;
            background: #dae0e5;
            margin: 5px;
            padding: 5px;
            display: block;
            border-radius: 5px;
            font-weight: bold;
            font-size: 50px;
            color: #17a2b8;
            line-height: 1.1;
            float: left;
        }

        .board buttom:hover {
            background: #4a5568;
            cursor: pointer;
        }
    </style>
</head>

<body class="text-center">
    <main role="main" style="margin-top: 5%">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
