game = {

    // API ROUTES
    GAMES_ROUTE:        '/api/v1/games',
    PC_MOVE_ROUTE:      '/api/v1/games/pc-move',
    PC_WIN_CHECK_ROUTE: '/api/v1/games/win-check',

    // STATUSES
    STATUS_RUNNING: 1,
    STATUS_X_WON: 2,
    STATUS_O_WON: 3,
    STATUS_DRAW: 4,

    startGame: () => {
        $.ajax({
            url: game.GAMES_ROUTE,
            type: 'POST',
            headers: game._headers(),
            success: (response) => {
                if (response.code === 201) {
                    $(location).prop('href', response.data.url);
                }
            }
        });
    },

    setMove: (key, id) => {
        if (!id)
            game.wrong();

        $.ajax({
            url: game.GAMES_ROUTE + '/' + id,
            data: {
                "key": key,
            },
            type: 'PUT',
            headers: game._headers(),
            success: (response) => {
                if (response.code === 200) {
                    game.reloadBoard(id);
                    game.winCheck(id);

                    setTimeout(() => {
                        game.pcMove(id);
                    }, 1500);
                }
            }
        });
    },

    pcMove: (id) => {
        if (!id)
            game.wrong();

        $.ajax({
            url: game.PC_MOVE_ROUTE + '/' + id,
            type: 'GET',
            headers: game._headers(),
            success: (response) => {
                if (response.code === 200) {
                    game.reloadBoard(id);
                    game.winCheck(id);
                }

                if (response.code === 400) {
                    alert(response.message);
                    game.startGame();
                }
            }
        });
    },

    winCheck: (id) => {
        if (!id)
            game.wrong();

        $.ajax({
            url: game.PC_WIN_CHECK_ROUTE + '/' + id,
            type: 'GET',
            headers: game._headers(),
            success: (response) => {
                if (response.code === 200) {
                    if (
                        response.data.gameStatus === game.STATUS_O_WON ||
                        response.data.gameStatus === game.STATUS_X_WON
                    ) {
                        let type = (response.data.gameStatus === game.STATUS_O_WON) ? "O" : "X";
                        alert(type + " wins!!! Congratulations! I will redirect you to first screen.");
                        $(location).prop('href', "/");
                    }
                }
            }
        });
    },

    reloadBoard: (id) => {
        $.ajax({
            url: game.GAMES_ROUTE + '/' + id,
            type: 'GET',
            headers: game._headers(),
            success: (response) => {
                if (response.code === 200 && response.data.board) {
                    $('.board').html(response.data.board);
                } else {
                    game.wrong();
                }
            }
        });
    },

    wrong: () => {
        alert("Something wrong :( . Start again.");
        game.startGame();
    },

    _headers: () => {
        return {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }
};
