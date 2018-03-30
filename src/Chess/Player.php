<?php

namespace App\Chess;

use App\Chess\Interfaces\PlayStrategy;

class Player
{
    protected $name;
    protected $turns_played = 0;
    protected $playStrategy;
    protected $color;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function move(Board $board)
    {
        $moves = $board->getPossibleMoves($this->getColor());
        $move = $this->getStrategy()->move($moves, $board);
        $piece = $move["piece"];
        $board->movePiece($piece->getPosition(), $move["move"]);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTurnsPlayed()
    {
        return $this->turns_played;
    }

    public function addTurn()
    {
        $this->turns_played++;
    }

    public function setStrategy(PlayStrategy $strategy)
    {
        $this->playStrategy = $strategy;
    }

    public function getStrategy()
    {
        return $this->playStrategy;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }
}
