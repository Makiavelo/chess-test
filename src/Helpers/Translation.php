<?php

namespace App\Helpers;

use App\Chess\Knight;
use App\Chess\Bishop;

class Translation
{
    public static function getInstancesFromRequest($board)
    {
        $instances = [];
        foreach($board as $pos => $piece) {
            $color = $piece[0] === 'w' ? 'white' : 'black';
            $position = self::fromAlpha($pos);
            $class = $piece[1] === 'N' ? 'Knight' : 'Bishop';
            if($class === 'Knight') {
                $instances[] = new Knight($color, $position);
            } else {
                $instances[] = new Bishop($color, $position);
            }

        }
        return $instances;
    }

    public static function toAlpha($pos)
    {
        $letters = [0 => 'a', 1 => 'b', 2 => 'c', 3 => 'd', 4 => 'e', 5 => 'f', 6 => 'g', 7 => 'h'];
        $translated = $letters[$pos[0]].($pos[1]+1);
        return $translated;
    }

    public static function fromAlpha($str)
    {
        $numbers = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3, 'e' => 4, 'f' => 5, 'g' => 6, 'h' => 7];
        $translated = [$numbers[$str[0]], ($str[1]-1)];
        return $translated;
    }
}