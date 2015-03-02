<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Hand {
    const HANDVALUE_GUU = 0;
    const HANDVALUE_CHO = 1;
    const HANDVALUE_PAA = 2;
    private static $hand;
    private static $name = [
        "グー"
        , "チョキ"
        , "パー"
    ];
    private $handvalue;
    private function __construct($handvalue) {
        $this->handvalue = $handvalue;
    }
    public static function getHand($handvalue) {
        if (is_null(self::$hand)) {
            self::$hand = array(
                new self(self::HANDVALUE_GUU)
                , new self(self::HANDVALUE_CHO)
                , new self(self::HANDVALUE_PAA)
            );
        }
        return self::$hand[$handvalue];
    }
    public function isStrongerThan($h) {
        return $this->fight($h) == 1;
    }
    public function isWeakerThan($h) {
        return $this->fight($h) == -1;
    }
    private function fight(Hand $h) {
        if ($this == $h) {
            return 0;
        } elseif (($this->handvalue + 1) % 3 == $h->handvalue) {
            return 1;
        } else {
            return -1;
        }
    }
}

interface Strategy {
    public function nextHand();
    public function study($win);
}

class WinningStrategy implements Strategy {
    private $won = false;
    private $prevHand;
    function __construct() {}
    public function nextHand() {
        if (!$this->won) {
            $this->prevHand = Hand::getHand(rand(0, 2));
        }
        return $this->prevHand;
    }
    public function study($win) {
        $this->won = $win;
    }
}

class ProbStrategy implements Strategy {
    private $prevHandValue = 0;
    private $currentHandValue = 0;
    private $history = [
        [1, 1, 1],
        [1, 1, 1],
        [1, 1, 1]
    ];
    function __construct() {}
    public function nextHand() {
        $bet = rand(0, $this->getSum($this->currentHandValue));
        $handvalue = 0;
        if ($bet < $this->history[$this->currentHandValue][0]) {
            $handvalue = 0;
        } elseif ($bet < $this->history[$this->currentHandValue][0] + $this->history[$this->currentHandValue][1]) {
            $handvalue = 1;
        } else {
            $handvalue = 2;
        }
        $this->prevHandValue = $this->currentHandValue;
        $this->currentHandValue = $handvalue;
        return Hand::getHand($handvalue);
    }
    private function getSum($hv) {
        $sum = 0;
        for ($i = 0; $i < 3; $i++) {
            $sum += $this->history[$hv][$i];
        }
    }
    public function study($win) {
        if ($win) {
            $this->history[$this->prevHandValue][$this->currentHandValue]++;
        } else {
            $this->history[$this->prevHandValue][($this->currentHandValue + 1) % 3]++;
            $this->history[$this->prevHandValue][($this->currentHandValue + 2) % 3]++;
        }
    }
}

class Player {
    private $name;
    private $strategy;
    private $wincount;
    private $losecount;
    private $gamecount;
    function __construct($name, $strategy) {
        $this->name = $name;
        $this->strategy = $strategy;
    }
    public function nextHand() {
        return $this->strategy->nextHand();
    }
    public function win() {
        $this->strategy->study(true);
        $this->wincount++;
        $this->gamecount++;
    }
    public function lose() {
        $this->strategy->study(false);
        $this->losecount++;
        $this->gamecount++;
    }
    public function even() {
        $this->gamecount++;
    }
    public function toString() {
        return "[".$this->name.":".$this->gamecount." games,".$this->wincount." win, ".$this->losecount." lose"."]";
    }
}

class Main {
    function __construct() {}
    public static function main() {
        $player1 = new Player("Taro", new WinningStrategy());
        $player2 = new Player("Hana", new WinningStrategy());
        for ($i = 0; $i < 10000; $i++) {
            $nextHand1 = $player1->nextHand();
            $nextHand2 = $player2->nextHand();
            if ($nextHand1->isStrongerThan($nextHand2)) {
                echo "Winner:".$player1->toString()."<br>";
                $player1->win();
                $player2->lose();
            } elseif ($nextHand2->isStrongerThan($nextHand1)) {
                echo "Winner:".$player2->toString()."<br>";
                $player1->lose();
                $player2->win();
            } else {
                echo "even..."."<br>";
                $player1->even();
                $player2->even();
            }
        }
        echo "Total result:"."<br>";
        echo $player1->toString()."<br>";
        echo $player2->toString()."<br>";
    }
}

Main::main(1,2);
