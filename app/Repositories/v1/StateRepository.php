<?php


namespace App\Repositories\v1;


use App\Entity\Game;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class StateRepository
 * @package App\Repositories\v1
 */
class StateRepository
{
    /**
     * @param Game $game
     */
    public function saveStateGame(Game $game): void
    {
        try {
            Cache::store('file')->set('gameId', $game->getId());
            $this->setBoardState($game->getId(), $game->getBoard());
        } catch (InvalidArgumentException $e) {
        }
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function getBoard($id): array
    {
        try {
            if ($data = Cache::store('file')->get('board-' . $id)) {
                return unserialize($data);
            }
        } catch (InvalidArgumentException $e) {
        }

        return [];
    }

    /**
     * @param $id
     * @param $board
     */
    public function setBoardState($id, $board): void
    {
        try {
            Cache::store('file')->set('board-' . $id, serialize($board));
        } catch (InvalidArgumentException $e) {
        }
    }

    /**
     * @param $player
     */
    public function setCurrentPlayer($player): void
    {
        try {
            Cache::store('file')->set('lastPlayer', $player);
        } catch (InvalidArgumentException $e) {
        }
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getCurrentPlayer(): string
    {
        return Cache::store('file')->get('lastPlayer');
    }

    /**
     *
     */
    public function clearState(): void
    {
        Cache::store('file')->clear();
    }
}
