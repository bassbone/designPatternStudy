<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Builder {
    private $make_title_flag = false;
    public function makeTitle($title) {
        $this->buildTitle($title);
        $this->make_title_flag = true;
    }
    public function makeString($str) {
        if($this->make_title_flag) {
            $this->buildString($str);
        }
    }
    public function makeItems($items) {
        if($this->make_title_flag) {
            $this->buildItems($items);
        }
    }
    public function close() {
        if($this->make_title_flag) {
            $this->buildClose();
        }
    }
    abstract protected function buildTitle($title);
    abstract protected function buildString($str);
    abstract protected function buildItems($items);
    abstract protected function buildClose();
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
    public function buildClose() {
        $this->buffer[] = "================================="."<br>";
    }

    public function buildItems($items) {
        for ($i = 0; $i < count($items); $i++) {
            $this->buffer[] = "  ・".$items[$i]."<br>";
        }
        $this->buffer[] = "<br>";
    }

    public function buildString($str) {
        $this->buffer[] = "■".$str."<br>";
        $this->buffer[] = "<br>";
    }

    public function buildTitle($title) {
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
    
    public function buildClose() {
        $this->result .= "</body></html>";
    }

    public function buildItems($items) {
        $this->result .= "<ul>";
        for ($i = 0; $i < count($items); $i++) {
            $this->result .= "<li>".$items[$i]."</li>";
        }
        $this->result .= "</ul>";
    }

    public function buildString($str) {
        $this->result .= "<p>".$str."</p>";
    }

    public function buildTitle($title) {
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

