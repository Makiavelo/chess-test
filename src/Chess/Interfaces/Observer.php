<?php

namespace App\Chess\Interfaces;

interface Observer
{
    public function notify($text, $data = [], $debug = false);
}
