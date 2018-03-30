<?php

namespace App\Chess;

class Square
{
    protected $piece = null;

    public function __construct()
    {

    }

    public function addPiece(Piece $piece)
    {
        $this->piece = $piece;
    }

    public function getPiece()
    {
        return $this->piece;
    }

    public function isEmpty()
    {
        return ($this->piece ? false : true);
    }

    public function clear()
    {
        $this->piece = null;
    }
}
