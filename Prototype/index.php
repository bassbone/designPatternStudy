<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Product {
    abstract function my_use($s);
    public function createClone() {
        $p = null;
        try {
            $p = clone $this;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $p;
    }
}

class Manager {
    private $showcase = array();
    public function register($name, Product $proto) {
        $this->showcase[$name] = $proto;
    }
    public function create($protoname) {
        $p = $this->showcase[$protoname];
        return $p->createClone();
    } 
}

class MessageBox extends Product {
    private $decochar;
    function __construct($decochar) {
        $this->decochar = $decochar;
    }
    public function my_use($s) {
        $length = strlen($s);
        for ($i = 0; $i < $length + 4; $i++) {
            echo $this->decochar;
        }
        echo "<br>";
        echo $this->decochar." ".$s." ".$this->decochar."<br>";
        for ($i = 0; $i < $length + 4; $i++) {
            echo $this->decochar;
        }
        echo "<br>";
    }
}

class UnderlinePen extends Product {
    private $ulchar;
    function __construct($ulchar) {
        $this->ulchar = $ulchar;
    }
    public function my_use($s) {
        $length = strlen($s);
        echo "\"".$s."\""."<br>";
        echo " ";
        for ($i = 0; $i < $length; $i++) {
            echo $this->ulchar;
        }
        echo "<br>";
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $manager = new Manager();
        $upen = new UnderlinePen("~");
        $mbox = new MessageBox("*");
        $sbox = new MessageBox("/");
        $manager->register("strong message", $upen);
        $manager->register("warning box", $mbox);
        $manager->register("slash box", $sbox);
        //var_dump($manager);
        
        $p1 = $manager->create("strong message");
        $p1->my_use("Hello, world.");
        $p2 = $manager->create("warning box");
        $p2->my_use("Hello, world.");
        $p3 = $manager->create("slash box");
        $p3->my_use("Hello, world.");
    }
}

Main::main();
