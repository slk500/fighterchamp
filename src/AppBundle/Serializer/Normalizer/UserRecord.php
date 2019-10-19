<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 1/28/18
 * Time: 9:58 PM
 */

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
