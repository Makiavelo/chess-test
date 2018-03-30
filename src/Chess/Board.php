<?php

namespace App\Chess;

use App\Chess\Traits\Observable as ObservableTrait;
use App\Chess\Interfaces\Observable;

class Board implements Observable
{
    private $squares = [];
    private $pieces = [];

    use ObservableTrait;

    public function __construct()
    {
        $this->generate();
    }

    protected function generate()
    {
        $this->notifyObservers('generated board');
        for($i = 0; $i < 8; $i++) {
            $this->squares[$i] = array();
            for($j = 0; $j < 8; $j++) {
                $this->squares[$i][$j] = new Square();
            }
        }
    }

    public function addPieces($pieces)
    {
        $this->notifyObservers('adding pieces');
        if($pieces) {
            foreach($pieces as $piece) {
                $this->addPiece($piece);
                $this->notifyObservers('adding piece');
            }
        }
    }

    public function addPiece(Piece $piece)
    {
        $square = $this->getSquare($piece->getPosition());
        if($square) {
            if($square->isEmpty()) {
                $this->pieces[] = $piece;
                $square->addPiece($piece);
            } else {
                throw new \Exception('This square is in use!');
            }
        } else {
            throw new \Exception('Invalid square!');
        }
    }

    public function movePiece($from, $to, $turn)
    {
        $this->notifyObservers("Moving from [".$from[0].", ".$from[1]."] to [".$to[0].",".$to[1]."]");
        $squareFrom = $this->getSquare($from);
        $squareTo = $this->getSquare($to);

        $status = $this->verifyMovement($squareFrom->getPiece(), $to);
        $this->notifyObservers("Movement status: ".$status);
        if($status === 'valid') {
            $this->notifyHistory($from, $to, $status, $turn);
            $squareTo->addPiece($squareFrom->getPiece());
            $squareTo->getPiece()->setPosition($to);
            $squareFrom->clear();
        } elseif ($status === 'capture') {
            //capture the piece
            $this->notifyHistory($from, $to, $status, $turn);
            $squareTo->getPiece()->kill();
            $squareTo->addPiece($squareFrom->getPiece());
            $squareTo->getPiece()->setPosition($to);
            $squareFrom->clear();
        } else {
            //occupied by same player's piece, invalid move
        }
    }

    public function verifyMovement(Piece $piece, $to)
    {
        $squareTo = $this->getSquare($to);
        if(!$squareTo) return 'invalid';

        if($squareTo->isEmpty()) {
            return 'valid';
        } elseif ($squareTo->getPiece()->isEnemy($piece)) {
            return 'capture';
        } else {
            return 'invalid';
        }
    }

    public function getDistance($position1, $position2)
    {
        $dx = abs($position1[0] - $position2[0]);
        $dy = abs($position1[1] - $position2[1]);
        $distance = max($dx, $dy);

        return $distance;
    }

    public function getPossibleMoves($color)
    {
        $result = [];
        foreach($this->pieces as $piece) {
            if($piece->getColor() === $color and $piece->getStatus() === 'alive') {
                $pattern = $piece->getMovePattern();
                if($pattern["type"] == "knight") {
                    //Get knight valid moves
                    $pieceMoves = $this->getKnightPossibleMoves($piece);
                } elseif ($pattern["type"] == "diagonal") {
                    //Get bishop valid moves
                    $pieceMoves = $this->getDiagonalPossibleMoves($piece);
                }

                $pieceResult = [
                    "piece" => $piece,
                    "moves" => $pieceMoves
                ];
                $result[] = $pieceResult;
            }
        }
        return $result;
    }

    protected function getKnightPossibleMoves(Piece $piece)
    {
        $positions = [];
        $pattern = $piece->getMovePattern();
        $patternValues = $pattern["value"];

        //east
        foreach($patternValues as $value) {
            $x = $piece->getPosition()[0] + $value[0];
            $y = $piece->getPosition()[1] + $value[1];

            $status = $this->verifyMovement($piece, [$x, $y]);
            if($status === 'valid' or $status === 'capture') {
                $positions[] = [$x, $y, $status];
            }
        }
        return $positions;
    }

    protected function getDiagonalPossibleMoves(Piece $piece)
    {
        $positions = [];

        //each multiplier set is a direction of the diagonal.
        $multipliers = [
            ['x' =>  1, 'y' =>  1],
            ['x' => -1, 'y' =>  1],
            ['x' =>  1, 'y' => -1],
            ['x' => -1, 'y' => -1],
        ];

        foreach($multipliers as $multiplier) {
            //Max 8 moves on a diagonal.
            for($i = 1; $i <= 8; $i++) {
                $x = $piece->getPosition()[0] + ($i * $multiplier['x']);
                $y = $piece->getPosition()[1] + ($i * $multiplier['y']);

                $status = $this->verifyMovement($piece, [$x, $y]);
                if($status === 'valid' or $status === 'capture') {
                    $positions[] = [$x, $y, $status];
                }else{
                    break;
                }
            }
        }

        return $positions;
    }

    protected function notifyHistory($from, $to, $status, $turn)
    {
        $squareFrom = $this->getSquare($from);
        $squareTo = $this->getSquare($to);

        $value = [
            "position_from" => $from,
            "position_to" => $to,
            "piece" => $squareFrom->getPiece()->getName(),
            "piece_captured" => '',
            "status" => $status,
            "turn" => $turn
        ];

        if($status === 'capture') {
            $value["piece_captured"] = $squareTo->getPiece()->getName();
        }

        $this->notifyObservers(
            "logging movement",
            ["channel" => "movements", "value" => $value]
        );
    }

    public function getSquare($position)
    {
        if($this->validSquare($position)) {
            return $this->squares[$position[0]][$position[1]];
        }
        return null;
    }

    public function validSquare($position)
    {
        if(isset($this->squares[$position[0]]) and isset($this->squares[$position[0]][$position[1]])) {
            return true;
        }
        return false;
    }

    public function getPieces()
    {
        return $this->pieces;
    }

}
