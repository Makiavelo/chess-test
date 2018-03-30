<?php

namespace App\Chess\Interfaces;

use App\Chess\Board;

interface PlayStrategy
{
    public function move(array $possibleMoves, Board $board);
}
