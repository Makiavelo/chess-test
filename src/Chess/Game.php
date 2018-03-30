<?php

namespace App\Chess;

use App\Chess\Strategies\KnightStrategy;
use App\Chess\Strategies\BishopStrategy;
use App\Chess\Traits\Observable as ObservableTrait;
use App\Chess\Interfaces\Observable;


class Game implements Observable
{
    use ObservableTrait;

    protected $board;
    protected $players = [];
    protected $turn = 0;
    public $maxTurns = 100;

    public function __construct()
    {

    }

    public function setBoard(Board $board)
    {
        $this->notifyObservers('Board added');
        $this->board = $board;
        return $this;
    }

    public function setWhitePlayer(Player $player)
    {
        $this->notifyObservers('White player joined: '.$player->getName());
        $player->setStrategy(new KnightStrategy('white'));
        $player->setColor('white');
        $this->players[0] = $player;
        return $this;
    }

    public function setBlackPlayer(Player $player)
    {
        $this->notifyObservers('Black player joined: '.$player->getName());
        $player->setStrategy(new BishopStrategy('black'));
        $player->setColor('black');
        $this->players[1] = $player;
        return $this;
    }

    public function simulate()
    {
        $this->notifyObservers('simulating game!');
        $finished = false;

        while(!$finished and $this->turn < $this->maxTurns) {
            $this->turn++;
            $this->notifyObservers('turn: '.$this->turn);

            foreach($this->players as $key => $player) {
                $this->notifyObservers($this->players[$key]->getName()."'s turn");
                $this->players[$key]->addTurn();

                $this->players[$key]->move($this->board);
                if($this->checkStatus() === "finished") {
                    $finished = true;
                    break;
                }
            }
        }
        $this->notifyEndgame();
    }

    public function checkStatus()
    {
        //check for finishing rules
        $foundBlack = false;
        $foundWhite = false;
        foreach($this->board->getPieces() as $piece) {
            if($piece->getColor() === 'white' and $piece->getStatus() === 'alive') {
                $foundWhite = true;
            }

            if($piece->getColor() === 'black' and $piece->getStatus() === 'alive') {
                $foundBlack = true;
            }
        }

        //One of the teams lost all his pieces
        if(!$foundWhite or !$foundBlack) {
            return 'finished';
        }
    }

    protected function notifyEndgame()
    {
        $this->notifyObservers(
            "logging movement",
            [
                "channel" => "results",
                "value" => [
                    "turns" => $this->turn,
                    "movements" => $this->players[0]->getTurnsPlayed() + $this->players[1]->getTurnsPlayed()
                ]
            ]
        );
    }
}
