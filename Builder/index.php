<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Builder {
    abstract public function makeTitle($title);
    abstract public function makeString($str);
    abstract public function makeItems($items);
    abstract public function close();
}

class Director {
    private $builder;
    function __construct(Builder $builder) {
        $this->builder = $builder;
    }
    public function construct() {
        $this->builder->makeTitle("Greeting");
        $this->builder->makeString("朝から昼にかけて");
        $this->builder->makeItems(array("おはようございます","こんにちは"));
        $this->builder->makeString("夜に");
        $this->builder->makeItems(array("こんばんは","おやすみなさい","さようなら"));
        $this->builder->close();
    }
}

class TextBuilder extends Builder {
    private $buffer = array();
    public function close() {
        $this->buffer[] = "================================="."<br>";
    }

    public function makeItems($items) {
        for ($i = 0; $i < count($items); $i++) {
            $this->buffer[] = "  ・".$items[$i]."<br>";
        }
        $this->buffer[] = "<br>";
    }

    public function makeString($str) {
        $this->buffer[] = "■".$str."<br>";
        $this->buffer[] = "<br>";
    }

    public function makeTitle($title) {
        $this->buffer[] = "================================="."<br>";
        $this->buffer[] = "「".$title."」"."<br>";
        $this->buffer[] = "<br>";
    }
    
    public function getResult() {
        return implode('', $this->buffer);
    }
}

class HtmlBuilder extends Builder {
    private $result = "";
    
    public function close() {
        $this->result .= "</body></html>";
    }

    public function makeItems($items) {
        $this->result .= "<ul>";
        for ($i = 0; $i < count($items); $i++) {
            $this->result .= "<li>".$items[$i]."</li>";
        }
        $this->result .= "</ul>";
    }

    public function makeString($str) {
        $this->result .= "<p>".$str."</p>";
    }

    public function makeTitle($title) {
        $this->result .= "<html><head><title>".$title."</title></head><body>";
        $this->result .= "<h1>".$title."</h1>";
    }
    
    public function getResult() {
        return $this->result;
    }

}

class Main {
    function __construct() {
        
    }
    public static function main($mode) {
        if ($mode == "Text" || $mode == "Html") {
            $classname = $mode."Builder";
            if (class_exists($classname)) {
                $builder = new $classname();
                $director = new Director($builder);
                $director->construct();
                $result = $builder->getResult();
                echo $result;
           }
        } else {
            self::usage();
            exit(0);
        }
    }
    public static function usage() {
        echo "Usage: XXXX"."<br>";
    }
}

Main::main("Text");
Main::main("Html");

