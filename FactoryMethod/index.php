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
    const BR = "<br>";

    function __construct($owner) {
        echo $owner."のカードを作ります。".self::BR;
        $this->owner = $owner;
    }
    public function my_use() {
        echo $this->owner."のカードを使います。".self::BR;
    }
    public function getOwner() {
        return $this->owner;
    }
}

class IDCardFactory extends Factory {
    private $owners;
    function __construct() {
        $this->owners = array();
    }
    protected function createProduct($owner) {
        return new IDCard($owner);
    }
    protected function registerProduct(Product $product) {
        $this->owners[] = $product->getOwner();
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
        $card1->my_use();
        $card2->my_use();
        $card3->my_use();
        $card1->my_use();
        $card1->my_use();
        $card1->my_use();
    }
}

Main::main();
