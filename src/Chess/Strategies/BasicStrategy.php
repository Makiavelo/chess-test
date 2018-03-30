<?php

namespace App\Chess\Strategies;

use App\Chess\Interfaces\PlayStrategy;
use App\Chess\Board;
use App\Chess\Piece;

class BasicStrategy implements PlayStrategy
{
    protected $color;

    public function __construct($color)
    {
        $this->color = $color;
    }

    public function move(array $possibleMoves, Board $board)
    {
        $move = [];
        $captureMove = $this->captureMove($possibleMoves);

        if($captureMove)
        {
            $move = $captureMove;
        } else {
            $move = $this->closestMove($possibleMoves, $board);
        }

        return $move;
    }

    protected function captureMove($possibleMoves)
    {
        foreach($possibleMoves as $key => $value) {
            $piece = $value["piece"];
            $moves = $value["moves"];

            foreach($moves as $move)
            {
                if($move[2] === "capture") {
                    return [
                        "piece" => $piece,
                        "move" => $move
                    ];
                }
            }
        }
        return [];
    }

    protected function closestMove($possibleMoves, Board $board)
    {
        $shortestDistance = 10; //arbitrary long distance
        $result = [
            "piece" => null,
            "move" => []
        ];

        $enemyPieces = $this->getEnemyPieces($board);

        foreach($possibleMoves as $key => $value) {
            $dangerPositions = $this->getDangerPositions($board, $value["piece"]);
            foreach($value["moves"] as $move) {
                if(!in_array($move, $dangerPositions)) {
                    foreach($enemyPieces as $enemyPiece) {
                        $distance = $board->getDistance($move, $enemyPiece->getPosition());
                        if($distance < $shortestDistance) {
                            $shortestDistance = $distance;
                            $result = [
                                "piece" => $value["piece"],
                                "move"  => $move
                            ];
                        }
                    }
                }
            }
        }

        return $result;
    }

    protected function getDangerPositions(Board $board, Piece $piece)
    {
        //Remove current piece from board temporarily to evaluate enemy danger positions
        $currentSquare = $board->getSquare($piece->getPosition());
        $currentSquare->clear();

        $positions = [];
        $enemyColor = $this->getColor() === 'white' ? 'black' : 'white';
        $piecesMoves = $board->getPossibleMoves($enemyColor);
        foreach($piecesMoves as $pieceMoves) {
            foreach($pieceMoves["moves"] as $move) {
                $positions[] = $move;
            }
        }

        //Restore the piece
        $currentSquare->addPiece($piece);

        return $positions;
    }

    protected function getEnemyPieces(Board $board)
    {
        $pieces = [];
        foreach($board->getPieces() as $piece) {
            if($piece->getColor() !== $this->getColor()) {
                $pieces[] = $piece;
            }
        }

        return $pieces;
    }

    public function getColor()
    {
        return $this->color;
    }
}