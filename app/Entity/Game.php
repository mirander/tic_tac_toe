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
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setBoard($board)
    {
        $this->board = $board;
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
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public static function getWinsOptions()
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
