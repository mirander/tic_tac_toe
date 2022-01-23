<?php

namespace App\Services\v1;

use App\Entity\Game;
use App\Repositories\v1\{GameRepository, StateRepository};
use Illuminate\Support\Str;

/**
 * Class GameService
 * @package App\Services\v1
 */
class GameService
{
    /**
     * @var GameRepository
     */
    private GameRepository $gameRepository;
    /**
     * @var StateRepository
     */
    private StateRepository $stateRepository;

    /**
     * GameService constructor.
     * @param GameRepository $gameRepository
     * @param StateRepository $stateRepository
     */
    public function __construct(GameRepository $gameRepository, StateRepository $stateRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->stateRepository = $stateRepository;
    }


    /**
     * @return Game
     */
    public function initGame(): Game
    {
        $this->stateRepository->clearState(); // clear all last games

        $game = $this->gameRepository->setGame(
            Str::uuid()->toString(),
            Game::getEmptyBoard(),
            Game::STATUS_DRAW
        );

        $this->stateRepository->saveStateGame($game);

        return $game;
    }


    /**
     * @param $id
     * @return array
     */
    public function getGame($id): array
    {
        if ($board = $this->stateRepository->getBoard($id)) {
            $game = $this->gameRepository->setGame($id, $board, Game::STATUS_RUNNING);

            return [
                'game' => $game,
                'board' => $board
            ];
        }

        return [];
    }


    /**
     * @param $id
     * @param $key
     * @return Game|null
     */
    public function playerMove($id, $key): ?Game
    {
        if ($board = $this->stateRepository->getBoard($id)) {
            $board[$key] = Game::USER_PLAYER;
            $this->stateRepository->setBoardState($id, $board);
            $this->stateRepository->setCurrentPlayer(Game::USER_PLAYER);

            return $this->gameRepository->setGame($id, $board, Game::STATUS_RUNNING);
        }

        return null;
    }


    /**
     * @param $id
     * @return Game|null
     */
    public function pcMove($id): ?Game
    {
        if ($board = $this->stateRepository->getBoard($id)) {
            $freeOptions = $this->gameRepository->getFreeMove($board);

            if ($freeOptions) {
                $stepRand = array_rand($freeOptions);
                $board[$freeOptions[$stepRand]] = Game::PC_PLAYER;
                $this->stateRepository->setBoardState($id, $board);
                $this->stateRepository->setCurrentPlayer(Game::PC_PLAYER);

                return $this->gameRepository->setGame($id, $board, Game::STATUS_RUNNING);
            }
        }

        return null;
    }


    /**
     * @param $id
     * @return Game|null
     */
    public function winCheck($id): ?Game
    {
        if ($board = $this->stateRepository->getBoard($id)) {
            $winCheck = $this->gameRepository->checkWinStatus($board);
            $game = $this->gameRepository->setGame($id, $board, $winCheck['gameStatus']);

            if ($winCheck['win']) {
                $this->gameRepository->saveGameToFile($game);
                $this->stateRepository->clearState();
            }

            return $game;
        }

        return null;
    }
}
