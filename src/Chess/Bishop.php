<?php

namespace App\Chess;

class Bishop extends Piece
{
    function __construct($color, $position)
    {
        parent::__construct($color, $position);
        $this->name = 'Bishop';
    }

    public function getMovePattern()
    {
        $pattern = [
            'type' => 'diagonal'
        ];

        return $pattern;
    }
}
