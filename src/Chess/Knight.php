<?php

namespace App\Chess;

class Knight extends Piece
{
    public function __construct($color, $position)
    {
        parent::__construct($color, $position);
        $this->name = 'Knight';
    }

    public function getMovePattern()
    {
        $pattern = [
            'type' => 'knight',
            'value' => [
                [2,1],
                [2,-1],
                [-2,1],
                [-2,-1],
                [1, 2],
                [-1, 2],
                [1, -2],
                [-1, -2]
            ]
        ];

        return $pattern;
    }
}
