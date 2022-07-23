<?php

namespace App\Interfaces;

use App\Entity\Game;
use Psr\SimpleCache\CacheInterface;

interface StateStorageInterface
{

    public function getStore(): CacheInterface;

    public function saveStateGame(Game $game): void;

    public function getBoard($id): array;

    public function setBoardState($id, $board): void;

    public function setCurrentPlayer($player): void;

    public function getCurrentPlayer(): string;

    public function clearState(): void;


}
