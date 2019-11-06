<?php

namespace AppBundle\Serializer\Normalizer;

class UserRecord implements \JsonSerializable
{
    public $win = 0;

    public $draw = 0;

    public $lose = 0;

    public function addWin()
    {
        $this->win++;
    }

    public function addDraw()
    {
        $this->draw++;
    }

    public function addLose()
    {
        $this->lose++;
    }

    public function jsonSerialize()
    {
        return [
            'W' => $this->win,
            'D' => $this->draw,
            'L' => $this->lose
        ];
    }
}
