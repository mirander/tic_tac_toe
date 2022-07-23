<?php

namespace App\Entity;

/**
 * Class Game
 * @package App\Models
 */
class Game
{
    const STATUS_RUNNING = 1;
    const STATUS_X_WON = 2;
    const STATUS_O_WON = 3;
    const STATUS_DRAW = 4;

    const USER_PLAYER = "X";
    const PC_PLAYER   = "0";

    private $id;

    private $board;

    private $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Game
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @param mixed $board
     * @return Game
     */
    public function setBoard($board)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Game
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string[]
     */
    public static function getEmptyBoard()
    {
        return [
            '0,0' => '', '0,1' => '', '0,2' => '',
            '1,0' => '', '1,1' => '', '1,2' => '',
            '2,0' => '', '2,1' => '', '2,2' => '',
        ];
    }

    /**
     * @return string[][]
     */
    public static function getWinsStrategy()
    {
        return [
            ['0,0', '0,1', '0,2'],
            ['1,0', '1,1', '1,2'],
            ['2,0', '2,1', '2,2'],
            ['0,0', '1,0', '2,0'],
            ['0,1', '1,1', '2,1'],
            ['0,2', '1,2', '2,2'],
            ['0,0', '1,1', '2,2'],
            ['0,2', '1,1', '2,0'],
        ];
    }
}
