<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class AbstractDisplay {
    const CRLF = "<br>";
    abstract protected function open();
    abstract protected function printDisplay();
    abstract protected function close();
    final public function display() {
        $this->open();
        for ($i = 0; $i < 5; $i++) {
            $this->printDisplay();
        }
        $this->close();
    }
}

class CharDisplay extends AbstractDisplay {
    
    private $ch;
    
    function __construct($ch) {
        $this->ch = $ch;
    }
    
    public function open() {
        echo "＜＜";
    }
    
    public function printDisplay() {
        echo $this->ch;
    }
    
    public function close() {
        echo "＞＞".$this::CRLF;
    }
}

class StringDisplay extends AbstractDisplay {
    
    private $string;
    private $width;
    
    function __construct($string) {
        $this->string = $string;
        $this->width = strlen($string);
    }
    
    public function open() {
        $this->printLine();
    }
    
    public function printDisplay() {
        echo "|".$this->string."|".$this::CRLF;
    }
    
    public function close() {
        $this->printLine();
    }
    
    private function printLine() {
        echo "+";
        for ($i = 0; $i < $this->width; $i++) {
            echo "-";
        }
        echo "+".$this::CRLF;
    }
}

class HtmlDisplay extends AbstractDisplay {

    protected function close() {
        
    }

    protected function open() {
        
    }

    protected function printDisplay() {
        
    }

}

class Main {
    
    public function __construct() {
        
    }

    public static function main() {
        $d1 = new CharDisplay("H");
        $d2 = new StringDisplay("Hello, world");
        $d3 = new StringDisplay("こんにちは。");
        
        $d1->display();
        $d2->display();
        $d3->display();
    }
}

Main::main();
