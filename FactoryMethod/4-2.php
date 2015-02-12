<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Product {
    abstract public function my_use();
}

abstract class Factory {
    public final function create($owner) {
        $p = $this->createProduct($owner);
        $this->registerProduct($p);
        return $p;
    }
    abstract protected function createProduct($owner);
    abstract protected function registerProduct(Product $product);
}

class IDCard extends Product {
    private $owner;
    private $number;
    const BR = "<br>";

    function __construct($owner, $number) {
        echo $owner."のカード(No.".$number.")を作ります。".self::BR;
        $this->owner = $owner;
        $this->number = $number;
    }
    public function my_use() {
        echo $this->owner."のカード(No.".$this->number.")を使います。".self::BR;
    }
    public function getOwner() {
        return $this->owner;
    }
    public function getNumber() {
        return $this->number;
    }
}

class IDCardFactory extends Factory {
    private $owners;
    private $cnt;
    function __construct() {
        $this->owners = array();
        $this->cnt = 1;
    }
    protected function createProduct($owner) {
        return new IDCard($owner, $this->cnt++);
    }
    protected function registerProduct(Product $product) {
        $this->owners[$product->getNumber()] = $product->getOwner();
    }
    public function getOwners() {
        return $this->owners;
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $factory = new IDCardFactory();
        $card1 = $factory->create("結城浩");
        $card2 = $factory->create("とむら");
        $card3 = $factory->create("佐藤花子");
        $card4 = $factory->create("佐藤花子");
        $card1->my_use();
        $card2->my_use();
        $card3->my_use();
        $card1->my_use();
        $card1->my_use();
        $card1->my_use();
        $card4->my_use();
    }
}

Main::main();
