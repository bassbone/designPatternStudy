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
            'apple', 'grape', 'banana', 'orange'
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
        } elseif ($dice == 3) {
            $this->money += 1000;
            echo '所持金が増えました。<br>';            
        } elseif ($dice == 4) {
            $this->money *= 1.5;
            echo '所持金が増えました。<br>';            
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
            if (false !== strpos($f, 'good')) {
                $m->addFruit($f);
            }
            $it->next();
        }
        $store = serialize($m);
        file_put_contents('store', $store);
        return $m;
    }
    public function restoreMement() {
        $memento = new Memento($this->money);
        if ($store = @file_get_contents('store')) {
            $memento = unserialize($store);
        }
        $this->money = $memento->money;
        $this->fruits = $memento->getFruits();
        return $memento;
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
            $prefix = 'good ';
        }
        return $prefix.$this->fruitsname[rand(0, count($this->fruitsname) - 1)];
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $gamer = new Gamer(100);
        $memento = $gamer->restoreMement();
        for ($i = 0; $i < 100; $i++) {
            echo '==== '.$i.'<br>';
            echo '現状：'.$gamer->toString().'<br>';
            $gamer->bet();
            echo '所持金は'.$gamer->getMoney().'円になりました。';
            
            if ($gamer->getMoney() > $memento->getMoney() * 1.5) {
                echo '    （だいぶ増えたので、現在の状態を保存しておこう）';
                $memento = $gamer->createMemento();
            }
            
            try {
                @ob_flush();
                @flush();
                usleep(50000);
            } catch (Exception $ex) {

            }
            echo '<br>';
        }
    }
}

Main::main();
