<?php

namespace App\Chess\Traits;

use App\Chess\Interfaces\Observer;

trait Observable
{
    protected $observers = [];

    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function removeObserver(Observer $toRemove)
    {
        if(!$this->observers) return false;

        foreach($this->observers as $key => $observer) {
            if($observer === $toRemove) {
                array_splice($this->observers, $key, 1);
                return true;
            }
        }
        return false;
    }

    public function notifyObservers($text, $data = [], $debug = false)
    {
        if(!$this->observers) return false;

        foreach($this->observers as $observer) {
            $observer->notify($text, $data, $debug);
        }
    }

    public function clearObservers()
    {
        $this->observers = [];
    }

    public function debug($text, $data = [])
    {
        return $this->notifyObservers($text, $data, true);
    }
}