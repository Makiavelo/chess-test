<?php

require __DIR__ . '/vendor/autoload.php';

use App\Chess\Game;
use App\Chess\Board;
use App\Chess\Player;
use App\Chess\Log;
use App\Chess\Knight;
use App\Chess\Bishop;

$logger = new Log();

$board = new Board();
$board->addObserver($logger);

$board->addPieces([
    new Knight('white', [2,2]),
    new Knight('white', [3,3]),
    new Bishop('black', [7,7]),
]);

$game = new Game();
$game->addObserver($logger);
//$game->maxTurns = 5;

$game->setBoard($board);
$game->setWhitePlayer(new Player("Botsie"));
$game->setBlackPlayer(new Player("Mr. Robot"));

$game->simulate();
print_r($logger->getReport());
print_r($logger->getMovements());
//print_r($logger->getInternalLogs());