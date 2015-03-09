<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Entry {
    abstract public function getName();
    abstract public function getSize();
    public function add(Entry $entry) {
        throw new FileTreatmentException();
    }
    abstract protected function printList($prefix = "");
    
    public function toString() {
        return $this->getName()." (".$this->getSize().")";
    }
}

class MyFile extends Entry {
    private $name;
    private $size;
    function __construct($name, $size) {
        $this->name = $name;
        $this->size = $size;
    }
    public function getName() {
        return $this->name;
    }
    public function getSize() {
        return $this->size;
    }
    public function printList($prefix = "") {
        echo $prefix."/".$this->toString()."<br>";
    }
}

class MyDirectory extends Entry {
    private $name;
    private $directory;
    function __construct($name) {
        $this->name = $name;
        $this->directory = new ArrayObject();
    }
    public function getName() {
        return $this->name;
    }
    public function getSize() {
        $size = 0;
        $it = $this->directory->getIterator();
        while ($it->valid()) {
            $entry = $it->current();
            $size += $entry->getSize();
            $it->next();
        }
        return $size;
    }
    public function add(Entry $entry) {
        $this->directory->append($entry);
        return $this;
    }
    public function printList($prefix = "") {
        echo $prefix."/".$this->toString()."<br>";
        $it = $this->directory->getIterator();
        while ($it->valid()) {
            $entry = $it->current();
            $entry->printList($prefix."/".$this->name);
            $it->next();
        }
    }
}

class FileTreatmentException extends RuntimeException {
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        try {
            echo "Making root entries..."."<br>";
            $rootdir = new MyDirectory("root");
            $bindir = new MyDirectory("bin");
            $tmpdir = new MyDirectory("tmp");
            $usrdir = new MyDirectory("usr");
            $rootdir->add($bindir);
            $rootdir->add($tmpdir);
            $rootdir->add($usrdir);
            $bindir->add(new MyFile("vi", 10000));
            $bindir->add(new MyFile("latex", 20000));
            $rootdir->printList();
            
            echo "<br>";
            echo "Making user entries..."."<br>";
            $yuki = new MyDirectory("yuki");
            $hanako = new MyDirectory("hanako");
            $tomura = new MyDirectory("tomura");
            $usrdir->add($yuki);
            $usrdir->add($hanako);
            $usrdir->add($tomura);
            $yuki->add(new MyFile("diary.html", 100));
            $yuki->add(new MyFile("Composite.java", 200));
            $hanako->add(new MyFile("memo.txt", 300));
            $tomura->add(new MyFile("game.doc", 400));
            $tomura->add(new MyFile("junk.mail", 500));
            $rootdir->printList();
        } catch (Exception $ex) {

        }
    }
}

Main::main();
