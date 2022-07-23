<?php

namespace App\Http\Controllers\v1\api;

use App\Http\Controllers\v1\IndexController;
use App\Services\v1\{GameService, JsonService};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\Controller as BaseController;
use Psr\SimpleCache\InvalidArgumentException;
use Illuminate\Http\{Request, Response};

/**
 * Class GamesController
 * @package App\Http\Controllers\v1\api
 */
class GamesController extends BaseController
{
    /**
     * @var GameService
     */
    private GameService $gameService;

    /**
     * @var JsonService
     */
    private JsonService $jsonService;

    /**
     * GamesController constructor.
     * @param GameService $gameService
     * @param JsonService $jsonService
     */
    public function __construct(GameService $gameService, JsonService $jsonService)
    {
        $this->gameService = $gameService;
        $this->jsonService = $jsonService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $this->gameService->initGame();

        return $this->jsonService->response(
            [
                "description" => "URL of the started game",
                'url' => action([IndexController::class, 'board'])
            ],
            'Game successfully started',
            201,
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data = $this->gameService->getGame($id);
        $game = $data['game'];

        $boardRender = view('v1.shared._board', [
            'board' => $data['board'],
            'gameId' => $game->getId()
        ])->render();

        return $this->jsonService->response(
            [
                "description" => "Get a game",
                'game' => (array)$game,
                'board' => $boardRender
            ],
            'Gate game response'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $key = $request->get('key');
        $game = $this->gameService->playerMove($id, $key);

        return $this->jsonService->response(
            [
                "description" => "Successful response, returns the game",
                'item' => (array)$game,
                'gameStatus' => $game->getStatus()
            ],
            'Move done'
        );
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws InvalidArgumentException
     */
    public function winCheck($id)
    {
        $game = $this->gameService->winCheck($id);

        return $this->jsonService->response(
            [
                "description" => "Win check",
                'item' => (array)$game,
                'gameStatus' => $game->getStatus()
            ],
            'Check done'
        );
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|Response
     */
    public function pcMove($id)
    {
        $game = $this->gameService->pcMove($id);

        if ($game) {
            return $this->jsonService->response(
                [
                    "description" => "PC move",
                    'item' => (array)$game,
                    'gameStatus' => $game->getStatus()
                ],
                'PC move done'
            );
        }

        return $this->jsonService->response(
            [
                "description" => "PC move",
                'error' => true
            ],
            'No steps available',
            400,
            200
        );
    }
}
