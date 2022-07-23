<?php

namespace App\Http\Controllers\v1;

use App\Entity\Game;
use App\Interfaces\StateStorageInterface;
use App\Services\v1\GameService;
use App\Storage\LocalStateStorage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class IndexController
 * @package App\Http\Controllers\v1
 */
class IndexController extends BaseController
{
    /**
     * @var GameService
     */
    private GameService $gameService;

    /**
     * @var StateStorageInterface
     */
    private StateStorageInterface $localStateStore;

    /**
     * IndexController constructor.
     * @param GameService $gameService
     * @param LocalStateStorage $localStateStore
     */
    public function __construct(GameService $gameService, LocalStateStorage $localStateStore)
    {
        $this->gameService = $gameService;
        $this->localStateStore = $localStateStore;
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
            $gameId = $this->localStateStore->getStore()->get('gameId');
        } catch (InvalidArgumentException $e) {
            Log::critical($e->getMessage());
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
