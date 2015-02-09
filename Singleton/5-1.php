<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TicketMaker {
    private $ticket;
    private static $instance;
    private function __construct() {
        $this->ticket = 1000;
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new TicketMaker();
        }
        return self::$instance;
    }
    public function getNextTicketNumber() {
        return $this->ticket++;
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $TicketMaker1 = TicketMaker::getInstance();
        for ($i = 0; $i < 10; $i++) {
            echo $TicketMaker1->getNextTicketNumber()."<br>";
        }
        echo "--------"."<br>";
        $TicketMaker2 = TicketMaker::getInstance();
        for ($i = 0; $i < 20; $i++) {
            echo $TicketMaker2->getNextTicketNumber()."<br>";
        }
    }
}

Main::main();
