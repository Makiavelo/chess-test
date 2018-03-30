<?php

namespace App\Chess;

use App\Chess\Interfaces\Observer;
use App\Helpers\Translation;

class Log implements Observer
{
    protected $logs = [
        "logs" => [],
        "movements" => [],
        "results" => []
    ];
    protected $isDebugging = false;

    public function __construct()
    {
    }

    public function notify($text, $data = [], $debug = false)
    {
        if ($debug) {
            if ($this->isDebugging) {
                echo $text . "\n";
                if ($data) {
                    print_r($data);
                    echo "\n\n";
                }
            }
        } else {
            if (!$data) {
                $this->logs["logs"][] = $text;
            } else {
                $this->logs[$data["channel"]][] = $data["value"];
            }

        }
    }

    public function getLogs()
    {
        return $this->logs;
    }

    public function getInternalLogs()
    {
        return $this->logs["logs"];
    }

    public function getReport()
    {
        $report = [
            "turns" => $this->logs["results"][0]["turns"],
            "movements" => $this->logs["results"][0]["movements"],
            "kills" => $this->getKills()
        ];

        return $report;
    }

    public function getKills()
    {
        $result = [];
        foreach($this->logs["movements"] as $movement)
        {
            if($movement["status"] === "capture") {
                $result[] = $movement["piece"]." (".Translation::toAlpha($movement["position_from"]).") captured ".$movement["piece_captured"]." (".Translation::toAlpha($movement["position_to"]).") on turn ".$movement["turn"];
            }
        }
        return $result;
    }

    public function getMovements()
    {
        $movements = [];
        foreach($this->logs["movements"] as $movement) {
            $movements[] = Translation::toAlpha($movement["position_from"])."-".Translation::toAlpha($movement["position_to"]);
        }

        return $movements;
    }
}
