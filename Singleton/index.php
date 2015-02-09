<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Singleton {
    private static $singleton;
    private function __construct() {
        echo "インスタンスを生成しました。"."<br>";
    }
    public static function getInstance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new self;
        }
        return self::$singleton;
    }
}

class Main {
    function __construct() {}
    
    public static function main() {
        echo "Start."."<br>";
        $obj1 = Singleton::getInstance();
        $obj2 = Singleton::getInstance();
        if ($obj1 === $obj2) {
            echo "obj1とobj2は同じインスタンスです。"."<br>";
        } else {
            echo "obj1とobj2は同じインスタンスではありません。"."<br>";
        }
        echo "End."."<br>";
    }
}

Main::main();
