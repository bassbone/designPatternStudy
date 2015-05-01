<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface Printable {
    public function setPrinterName($name);
    public function getPrinterName();
    public function myprint($string);
}

class Printer implements Printable {
    private $name;
    function __construct($name = null) {
        if (!$name) {
            $this->heavyJob('Printerのインスタンスを生成中');
        } else {
            $this->name = $name;
            $this->heavyJob('Printerのインスタンス（'.$this->name.'）を生成中');
        }
    }
    public function getPrinterName() {
        return $this->name;
    }

    public function myprint($string) {
        echo '=== '.$this->name.' ==='.'<br>';
        echo $string.'<br>';
    }

    public function setPrinterName($name) {
        $this->name = $name;
    }
    
    private function heavyJob($msg) {
        echo $msg;
        for ($i = 0; $i < 5; $i++) {
            try {
                @ob_flush();
                @flush();
                sleep(1);
            } catch (Exception $ex) {

            }
            echo '.';
        }
        echo '完了。'.'<br>';
    }

}

class PrinterProxy implements Printable {
    private $name;
    private $real;
    function __construct($name = null) {
        if (!$name) {
            
        } else {
            $this->name = $name;
        }
    }

    public function getPrinterName() {
        return $this->name;
    }

    public function myprint($string) {
        $this->realize();
        $this->real->myprint($string);
    }

    public function setPrinterName($name) {
        if ($this->real != null) {
            $this->real->setPrinterName($name);
        }
        $this->name = $name;
    }
    
    private function realize() {
        if ($this->real == null) {
            $this->real = new Printer($this->name);
        }
    }

}

class Main {
    function __construct() {
        
    }
    public static function main () {
        $p = new PrinterProxy('Alice');
        echo '名前は現在'.$p->getPrinterName().'です。'.'<br>';
        $p->setPrinterName('Bob');
        echo '名前は現在'.$p->getPrinterName().'です。'.'<br>';
        $p->myprint('Hello, world.');
    }
}

Main::main();
