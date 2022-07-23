<?php


namespace App\Storage;

use App\Entity\Game;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\{CacheInterface, InvalidArgumentException};
use App\Interfaces\StateStorageInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class StateRepository
 * @package App\Repositories\v1
 */
class LocalStateStorage implements StateStorageInterface
{

    /**
     * @return Repository
     */
    public function getStore(): CacheInterface
    {
        return Cache::store('file');
    }

    /**
     * @param Game $game
     */
    public function saveStateGame(Game $game): void
    {
        try {
            $this->getStore()->set('gameId', $game->getId());
            $this->setBoardState($game->getId(), $game->getBoard());
        } catch (InvalidArgumentException $e) {
            Log::critical($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function getBoard($id): array
    {
        try {
            if ($data = $this->getStore()->get('board-' . $id)) {
                return unserialize($data);
            }
        } catch (InvalidArgumentException $e) {
            Log::critical($e->getMessage());
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
            $this->getStore()->set('board-' . $id, serialize($board));
        } catch (InvalidArgumentException $e) {
            Log::critical($e->getMessage());
        }
    }

    /**
     * @param $player
     */
    public function setCurrentPlayer($player): void
    {
        try {
            $this->getStore()->set('lastPlayer', $player);
        } catch (InvalidArgumentException $e) {
            Log::critical($e->getMessage());
        }
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getCurrentPlayer(): string
    {
        return $this->getStore()->get('lastPlayer');
    }

    /**
     *
     */
    public function clearState(): void
    {
        $this->getStore()->clear();
    }
}
