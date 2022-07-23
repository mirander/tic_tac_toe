<?php

namespace App\Services\v1;

use App\Entity\Game;
use App\Repositories\v1\GameRepository;
use App\Storage\LocalStateStorage;
use Illuminate\Support\Str;
use Psr\SimpleCache\InvalidArgumentException;

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
     * @var LocalStateStorage
     */
    private LocalStateStorage $storage;

    /**
     * GameService constructor.
     * @param GameRepository $gameRepository
     * @param LocalStateStorage $storage
     */
    public function __construct(GameRepository $gameRepository, LocalStateStorage $storage)
    {
        $this->gameRepository = $gameRepository;
        $this->storage = $storage;
    }

    /**
     * @return Game
     */
    public function initGame(): Game
    {
        $this->storage->clearState(); // clear all last games

        $game = $this->gameRepository->setGame(
            Str::uuid()->toString(),
            Game::getEmptyBoard(),
            Game::STATUS_DRAW
        );

        $this->storage->saveStateGame($game);

        return $game;
    }

    /**
     * @param $id
     * @return array
     */
    public function getGame($id): array
    {
        if ($board = $this->storage->getBoard($id)) {
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
        if ($board = $this->storage->getBoard($id)) {
            $board[$key] = Game::USER_PLAYER;
            $this->storage->setBoardState($id, $board);
            $this->storage->setCurrentPlayer(Game::USER_PLAYER);

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
        if ($board = $this->storage->getBoard($id)) {
            $freeOptions = $this->gameRepository->getFreeMove($board);

            if ($freeOptions) {
                $stepRand = array_rand($freeOptions);
                $board[$freeOptions[$stepRand]] = Game::PC_PLAYER;
                $this->storage->setBoardState($id, $board);
                $this->storage->setCurrentPlayer(Game::PC_PLAYER);

                return $this->gameRepository->setGame($id, $board, Game::STATUS_RUNNING);
            }
        }

        return null;
    }


    /**
     * @param $id
     * @return Game|null
     * @throws InvalidArgumentException
     */
    public function winCheck($id): ?Game
    {
        if ($board = $this->storage->getBoard($id)) {
            $winCheck = $this->gameRepository->checkWinStatus($board);
            $game = $this->gameRepository->setGame($id, $board, $winCheck['gameStatus']);

            if ($winCheck['win']) {
                $this->gameRepository->saveGameToFile();
                $this->storage->clearState();
            }

            return $game;
        }

        return null;
    }
}
