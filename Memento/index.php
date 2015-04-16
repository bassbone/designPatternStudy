<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Memento {
    public $money;
    public $fruits;
    public function getMoney() {
        return $this->money;
    }
    function __construct($money) {
        $this->money = $money;
        $this->fruits = new ArrayObject();
    }
    public function addFruit($fruit) {
        $this->fruits->append($fruit);
    }
    public function getFruits() {
        return clone $this->fruits;
    }
}

class Gamer {
    private $money;
    private $fruits;
    private $random;
    private $fruitsname;
    function __construct($money) {
        $this->money = $money;
        $this->fruits = new ArrayObject();
        $this->fruitsname = array(
            'リンゴ', 'ぶどう', 'バナナ', 'みかん'
        );
    }
    public function getMoney() {
        return $this->money;
    }
    public function bet() {
        $dice = rand(1, 6);
        if ($dice == 1) {
            $this->money += 100;
            echo '所持金が増えました。<br>';
        } elseif ($dice == 2) {
            $this->money /= 2;
            echo '所持金が半分になりました。<br>';
        } elseif ($dice == 6) {
            $f = $this->getFruit();
            echo 'フルーツ（'.$f.'）をもらいました。<br>';
            $this->fruits->append($f);
        } else {
            echo '何も起こりませんでした。';
        }
    }
    public function createMemento() {
        $m = new Memento($this->money);
        $it = $this->fruits->getIterator();
        while ($it->valid()) {
            $f = $it->current();
            if (false !== strpos('おいしい', $f)) {
                $m->addFruit($f);
            }
            $it->next();
        }
        return $m;
    }
    public function restoreMement(Memento $memento) {
        $this->money = $memento->money;
        $this->fruits = $memento->getFruits();
    }
    public function toString() {
        $fruits = array();
        $it = $this->fruits->getIterator();
        while ($it->valid()) {
            $fruits[] = $it->current();
            $it->next();
        }
        return '[money = '.$this->money.', fruits = '.implode(',', $fruits).']';
    }
    private function getFruit() {
        $prefix = '';
        if (rand(0, 1)) {
            $prefix = 'おいしい';
        }
        return $prefix.$this->fruitsname[rand(0, count($this->fruitsname) - 1)];
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $gamer = new Gamer(100);
        $memento = $gamer->createMemento();
        for ($i = 0; $i < 100; $i++) {
            echo '==== '.$i.'<br>';
            echo '現状：'.$gamer->toString().'<br>';
            $gamer->bet();
            echo '所持金は'.$gamer->getMoney().'円になりました。';
            
            if ($gamer->getMoney() > $memento->getMoney()) {
                echo '    （だいぶ増えたので、現在の状態を保存しておこう）';
                $memento = $gamer->createMemento();
            } elseif ($gamer->getMoney() < $memento->getMoney() / 2) {
                echo '    （だいぶ減ったので、以前の状態に復帰しよう）';
                $gamer->restoreMement($memento);
            }
            
            try {
                @ob_flush();
                @flush();
                usleep(100000);
            } catch (Exception $ex) {

            }
            echo '<br>';
        }
    }
}

Main::main();
