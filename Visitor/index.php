<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Visitor {
    public abstract function visit($obj);
}

interface Element {
    public function accept(Visitor $v);
}

abstract class Entry {
    public abstract function getName();
    public abstract function getSize();
    public function add($entry){
        
    }
    public function iterator() {
        
    }
    public function toString() {
        return $this->getName()." ---> [".$this->getSize()."]";
    }
}

class File extends Entry {
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
    public function accept(Visitor $v) {
        $v->visit($this);
    }
}

class MyDirectory extends Entry {
    private $name;
    private $dir;
    
    function __construct($name) {
        $this->name = $name;
        $this->dir = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getSize() {
        $size = 0;
        $it = $this->iterator();
        while ($it->valid()) {
            $entry = $it->current();
            $size += $entry->getSize();
            $it->next();
        }
        return $size;
    }
    
    public function add($entry) {
        $this->dir->append($entry);
        return $this;
    }
    
    public function iterator() {
        return $this->dir->getIterator();
    }
    
    public function accept(Visitor $v) {
        $v->visit($this);
    }
}

class ListVisitor extends Visitor {
    private $currentdir;
    
    function __construct() {
        $this->currentdir = "";
    }
        
    public function visit($obj) {
        $method = 'visit'.get_class($obj);
        $this->$method($obj);
    }
    
    private function visitFile(File $obj) {
        echo htmlentities($this->currentdir."/".$obj->toString())."<br>";
    }
    
    private function visitMyDirectory(MyDirectory $obj) {
        echo htmlentities($this->currentdir."/".$obj->toString())."<br>";
        $savedir = $this->currentdir;
        $this->currentdir = $this->currentdir."/".$obj->getName();
        $it = $obj->iterator();
        while ($it->valid()) {
            $entry = $it->current();
            //echo("<pre>");print_r($entry);echo("</pre>");
            $entry->accept($this);
            $it->next();
        }
        $this->currentdir = $savedir;
    }
}

class FileTreatmentException extends RuntimeException {
    function __construct($msg) {
        parent::__construct($msg, '', '');
    }
    
}

class Main {
    
    function __construct() {
        
    }
    
    public static function main() {
        try {
            echo "Making root entries..."."<br>";
            $rootdir = new MyDirectory("root");
            $binddir = new MyDirectory("bind");
            $tmpdir = new MyDirectory("tmp");
            $usrdir = new MyDirectory("usr");
            $rootdir->add($binddir);
            $rootdir->add($tmpdir);
            $rootdir->add($usrdir);
            $binddir->add(new File("vi", 10000));
            $binddir->add(new File("latex", 20000));
            $rootdir->accept(new ListVisitor());

            echo "<br>";
            echo "Making user entries..."."<br>";
            $yuki = new MyDirectory("yuki");
            $hanako = new MyDirectory("hanako");
            $tomura = new MyDirectory("tomura");
            $usrdir->add($yuki);
            $usrdir->add($hanako);
            $usrdir->add($tomura);
            $yuki->add(new File("diary1.html", 100));
            $yuki->add(new File("diary2.html", 200));
            $hanako->add(new File("diary3.html", 300));
            $tomura->add(new File("diary4.html", 400));
            $tomura->add(new File("diary5.html", 500));
            $rootdir->accept(new ListVisitor());
        } catch(Exception $e) {
            
        }
    }
}

Main::main();
