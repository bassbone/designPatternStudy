<?php

set_time_limit(120);

interface Observer {
    public function update(NumberGenerator $generator);
}

abstract class NumberGenerator {
    private $observers;
    public function __construct() {
        $this->observers = new ArrayObject();
    }
    public function addObserver(Observer $observer) {
        $this->observers->append($observer);
    }
    public function deleteObserver(Observer $observer) {
        // 処理無し
    }
    public function notifyObservers() {
        $it = $this->observers->getIterator();
        while ($it->valid()) {
            $o = $it->current();
            $o->update($this);
            $it->next();
        }
    }
    public abstract function getNumber();
    public abstract function execute();
}

class RandomeNumberGenerator extends NumberGenerator {
    private $random;
    private $number;
    public function __construct() {
        parent::__construct();
    }
    public function getNumber() {
        return $this->number;
    }
    public function execute() {
        for ($i = 0; $i < 20; $i++) {
            $this->number = rand(0, 49);
            $this->notifyObservers();
        }
    }
}

class DigitObserver implements Observer {
    public function update(NumberGenerator $generator) {
        echo 'DigitObserver:'.$generator->getNumber().'<br>';
        try {
            @ob_flush();
            @flush();
            usleep(500000);
        } catch (Exception $ex) {

        }
    }
}

class GraphObserver implements Observer {
    public function update(NumberGenerator $generator) {
        echo 'GraphObserver:';
        $count = $generator->getNumber();
        for ($i = 0; $i < $count; $i++) {
            echo '*';
            @ob_flush();
            @flush();
            usleep(100000);
        }
        echo '<br>';
        try {
            @ob_flush();
            @flush();
            usleep(500000);
        } catch (Exception $ex) {
            
        }
    }
}

class Graph2Observer implements Observer {
    public function update(\NumberGenerator $generator) {
        echo 'Graph2Observer:';
        $count = $generator->getNumber();
        for ($i = 0; $i < $count; $i++) {
            echo 'X';
            @ob_flush();
            @flush();
            usleep(100000);
        }
        echo '<br>';
        try {
            @ob_flush();
            @flush();
            usleep(500000);
        } catch (Exception $ex) {
            
        }        
    }
}

class Main {
    public function __construct() {
        
    }
    public static function main() {
        $generator = new RandomeNumberGenerator();
        $observer1 = new DigitObserver();
        $observer2 = new Graph2Observer();
        $generator->addObserver($observer1);
        $generator->addObserver($observer2);
        $generator->execute();
    }
}

Main::main();
