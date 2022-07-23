<?php

namespace App\Repositories\v1;

use App\Entity\Game;
use App\Interfaces\StateStorageInterface;
use App\Storage\LocalStateStorage;
use Illuminate\Support\Facades\Storage;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class GameRepository
 * @package App\Repositories\v1
 */
class GameRepository
{

    /**
     * @var Game
     */
    private Game $game;

    /**
     * @var StateStorageInterface
     */
    private StateStorageInterface $storage;

    /**
     * GameRepository constructor.
     * @param LocalStateStorage $storage
     */
    public function __construct(LocalStateStorage $storage)
    {
        $this->storage = $storage;
        $this->game = new Game();
    }

    /**
     * @param $gameId
     * @param $board
     * @param $status
     * @return Game
     */
    public function setGame($gameId, $board, $status): Game
    {
        return $this->game
            ->setId($gameId)
            ->setBoard($board)
            ->setStatus($status);
    }

    public function saveGameToFile(): void
    {
        Storage::disk('local')
            ->put(
                'game_' . $this->game->getId() . '.txt',
                serialize($this->game)
            );
    }

    /**
     * @param $board
     * @return array
     */
    public function getFreeMove($board): array
    {
        $freeOptions = [];

        foreach ($board as $key => $b) {
            if ($b == '')
                array_push($freeOptions, $key);
        }

        return $freeOptions;
    }

    /**
     * @param $board
     * @return array
     * @throws InvalidArgumentException
     */
    public function checkWinStatus($board): array
    {
        $currentPlayer = $this->storage->getCurrentPlayer();
        $result = [$currentPlayer => []];
        $winOptions = Game::getWinsStrategy();

        foreach ($board as $key => $b) {
            if ($b == $currentPlayer) {
                array_push($result[$b], $key);
            }
        }

        foreach ($winOptions as $win) {
            foreach ($result as $key => $r) {
                $count = 0;

                foreach ($r as $n) {
                    if (in_array($n, $win)) {
                        $count++;
                    }
                    if ($count == 3) {
                        return [
                            'type' => $currentPlayer,
                            'win' => true,
                            'gameStatus' => $currentPlayer == Game::USER_PLAYER
                                ? Game::STATUS_X_WON
                                : Game::STATUS_O_WON
                        ];
                    }
                }
            }
        }

        return [
            'win' => false,
            'gameStatus' => Game::STATUS_RUNNING
        ];
    }
}
