<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Display {
    private $impl;
    function __construct(DisplayImpl $impl) {
        $this->impl = $impl;
    }
    public function open() {
        $this->impl->rawOpen();
    }
    public function printX() {
        $this->impl->rawPrint();
    }
    public function close() {
        $this->impl->rawClose();
    }
    public final function display() {
        $this->open();
        $this->printX();
        $this->close();
    }
}

class CountDisplay extends Display {
    function __construct(\DisplayImpl $impl) {
        parent::__construct($impl);
    }
    public function multiDisplay($times) {
        $this->open();
        for ($i = 0; $i < $times; $i++) {
            $this->printX();
        }
        $this->close();
    }
}

class IncreaseDisplay extends CountDisplay {
    private $step;
    function __construct(\DisplayImpl $impl, $step) {
        parent::__construct($impl);
        $this->step = $step;
    }
    public function increaseDisplay($level) {
        $count = 0;
        for ($i = 0; $i < $level; $i++) {
            $this->multiDisplay($count);
            $count += $this->step;
        }
    }

}

abstract class DisplayImpl {
    abstract public function rawOpen();
    abstract public function rawPrint();
    abstract public function rawClose();
}

class StringDisplayImpl extends DisplayImpl {
    private $string;
    private $width;
    function __construct($string) {
        $this->string = $string;
        $this->width = strlen($string);
    }
    public function rawOpen() {
        $this->printLine();
    }
    public function rawPrint() {
        echo "|".$this->string."|"."<br>";
    }
    public function rawClose() {
        $this->printLine();
    }
    private function printLine() {
        echo "+";
        for ($i = 0; $i < $this->width; $i++) {
            echo "-";
        }
        echo "+"."<br>";
    }
}

class CharDisplayImpl extends DisplayImpl{
    private $head;
    private $body;
    private $foot;
    function __construct($head, $body, $foot) {
        $this->head = $head;
        $this->body = $body;
        $this->foot = $foot;
    }
    public function rawOpen() {
        echo $this->head;
    }
    public function rawPrint() {
        echo $this->body;
    }
    public function rawClose() {
        echo $this->foot."<br>";
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $d1 = new Display(new StringDisplayImpl("Hello, Japan."));
        $d2 = new CountDisplay(new StringDisplayImpl("Hello, World."));
        $d3 = new CountDisplay(new StringDisplayImpl("Hello, Universe."));
        $d4 = new IncreaseDisplay(new CharDisplayImpl("<", "*", ">"), 2);
        $d1->display();
        $d2->display();
        $d3->display();
        $d3->multiDisplay(5);
        $d4->increaseDisplay(10);
    }
}

Main::main();
