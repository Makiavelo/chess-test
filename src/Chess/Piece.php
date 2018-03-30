<?php

namespace App\Chess;

abstract class Piece
{
    protected $position;
    protected $color;
    protected $status = 'alive';
    protected $name;

    public function __construct($color, $position)
    {
        $this->position = $position;
        $this->color = $color;
    }

    abstract public function getMovePattern();

    public function isEnemy(Piece $piece)
    {
        if($this->getColor() !== $piece->getColor())
        {
            return true;
        }
        return false;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function kill()
    {
        $this->status = 'dead';
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition(array $position)
    {
        $this->position = $position;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
