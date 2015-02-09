<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Triple {
    private static $instance = array();
    private $count;
    private $id;
    function __construct($id) {
        $this->id = $id;
        $this->count = 0;
    }
    public static function getInstance($id) {
        if ($id >= 3) {
            throw new Exception("インスタンスは3個までです");
        }
        if (!isset(self::$instance[$id])) {
            self::$instance[$id] = new Triple($id);
        }
        return self::$instance[$id];
    }
    public function printValue() {
        $this->count++;
        echo "[".$this->id."]".$this->count."<br>";
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        try {
            $instance0 = Triple::getInstance(0);
            $instance0->printValue();
            $instance1 = Triple::getInstance(1);
            $instance1->printValue();
            $instance6 = Triple::getInstance(3);
            $instance6->printValue();
            $instance2 = Triple::getInstance(2);        
            $instance2->printValue();
            $instance3 = Triple::getInstance(0);
            $instance3->printValue();
            $instance4 = Triple::getInstance(1);
            $instance4->printValue();
            $instance5 = Triple::getInstance(2);        
            $instance5->printValue();
            $instance2->printValue();
            $instance2->printValue();
            $instance2->printValue();
            $instance2->printValue();
        } catch (Exception $e) {
            echo $e->getMessage()."<br>";
        }
    }
}

Main::main();
