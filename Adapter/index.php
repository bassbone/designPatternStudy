<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Banner {
    private $string;
    function __construct($string) {
        $this->string = $string;
    }
    public function showWithParen() {
        echo "(".$this->string.")"."<br>";
    }
    public function showWithAster() {
        echo "*".$this->string."*"."<br>";
    }
}

interface MyPrint {
    public function printWeak();
    public function printStrong();
}

class PrintBanner extends Banner implements MyPrint {
    function __construct($string) {
        parent::__construct($string);
    }
    public function printWeak() {
        parent::showWithParen();
    }
    public function printStrong() {
        parent::showWithAster();
    }    
}

class Main {
    
    function __construct() {}
    
    public static function main() {
        $p = new PrintBanner("Hello");
        $p->printWeak();
        $p->printStrong();
    }
}

Main::main();
