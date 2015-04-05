<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Trouble {
    private $number;
    function __construct($number) {
        $this->number = $number;
    }
    public function getNumber() {
        return $this->number;
    }
    public function toString() {
        return '[Trouble '.$this->number.']';
    }
}

abstract class Support {
    private $name;
    private $next;
    function __construct($name) {
        $this->name = $name;
    }
    public function setNext(Support $next) {
        $this->next = $next;
        return $this->next;
    }
    public final function support(Trouble $trouble) {
        for ($obj = $this; true; $obj = $obj->next) {
            if ($obj->resolve($trouble)) {
                $obj->done($trouble);
                break;
            } elseif ($obj->next == null) {
                $obj->fail($trouble);
                break;
            }
        }
    }
    public function toString() {
        return '['.$this->name.']';
    }
    protected abstract function resolve(Trouble $trouble);
    protected function done(Trouble $trouble) {
        echo $trouble->toString().' is resolved by '.$this->toString().'.<br>';
    }
    protected function fail(Trouble $trouble) {
        echo $trouble->toString().' cannot be resolved.<br>';
    }
}

class NoSupport extends Support {
    function __construct($name) {
        parent::__construct($name);
    }
    protected function resolve(\Trouble $trouble) {
        return false;
    }
}

class LimitSupport extends Support {
    private $limit;
    function __construct($name, $limit) {
        parent::__construct($name);
        $this->limit = $limit;
    }
    protected function resolve(\Trouble $trouble) {
        if ($trouble->getNumber() < $this->limit) {
            return true;
        } else {
            return false;
        }
    }
}

class OddSupport extends Support {
    function __construct($name) {
        parent::__construct($name);
    }
    protected function resolve(\Trouble $trouble) {
        if ($trouble->getNumber() % 2 == 1) {
            return true;
        } else {
            return false;
        }
    }
}

class SpecialSupport extends Support {
    private $number;
    function __construct($name, $number) {
        parent::__construct($name);
        $this->number = $number;
    }
    protected function resolve(\Trouble $trouble) {
        if ($trouble->getNumber() == $this->number) {
            return true;
        } else {
            return false;
        }
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $alice = new NoSupport('Alice');
        $bob = new LimitSupport('Bob', 100);
        $charlie = new SpecialSupport('Charlie', 429);
        $diana = new LimitSupport('Diana', 200);
        $elmo = new OddSupport('Elmo');
        $fred = new LimitSupport('Fred', 300);
        $alice->setNext($bob)->setNext($charlie)->setNext($diana)->setNext($elmo)->setNext($fred);
        for ($i = 0; $i < 500; $i += 33) {
            $alice->support(new Trouble($i));
        }
    }
}

Main::main();
