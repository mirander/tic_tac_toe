<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Entity\Game;
use App\Services\v1\GameService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class IndexController
 * @package App\Http\Controllers\v1
 */
class IndexController extends Controller
{
    /**
     * @var GameService
     */
    private GameService $gameService;

    /**
     * IndexController constructor.
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('/v1/index');
    }

    /**
     * @return Application|Factory|View
     */
    public function board()
    {
        $gameId = null;

        try {
            $gameId = Cache::store('file')->get('gameId');
        } catch (InvalidArgumentException $e) {
        }

        if ($gameId) {
            $data = $this->gameService->getGame($gameId);
            $board = $data['board'];
        } else {
            $board = Game::getEmptyBoard();
        }

        return view('/v1/game', [
            'board' => $board,
            'gameId' => $gameId
        ]);
    }
}
