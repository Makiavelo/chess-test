<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Chess\Game;
use App\Chess\Board;
use App\Chess\Player;
use App\Chess\Log;
use App\Chess\Knight;
use App\Chess\Bishop;
use App\Helpers\Translation;

$logger = new Log();

$board = new Board();
$board->addObserver($logger);

$pieces = Translation::getInstancesFromRequest($_POST['board']);

$board->addPieces($pieces);

$game = new Game();
$game->addObserver($logger);

$game->setBoard($board);
$game->setWhitePlayer(new Player("Botsie"));
$game->setBlackPlayer(new Player("Mr. Robot"));

$game->simulate();

$response = [
    "movements" => $logger->getMovements(),
    "report" => $logger->getReport()
];

echo json_encode($response);