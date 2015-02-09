<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface FileIO {
    public function readFromParam($param);
    public function writeToView();
    public function setValue($key, $value);
    public function getValue($key);
}

class FileProperties implements FileIO {
    
    private $properties;
    
    function __construct() {
        $this->properties = array();
    }

    public function getValue($key) {
        return $this->properties[$key];
    }

    public function readFromParam($param) {
        $tmp = explode("_", $param);
        $this->properties[$tmp[0]] = $tmp[1];
    }

    public function setValue($key, $value) {
        $this->properties[$key] = $value;
    }

    public function writeToView() {
        foreach ($this->properties as $key => $value) {
            echo $key." : ".$value."<br>";
        }
    }

}

class Main {
    function __construct() {}
    
    public static function main () {
        $f = new FileProperties();
        try {
            $f->readFromParam(filter_input(INPUT_GET, "param"));
            $f->setValue("year", "2004");
            $f->setValue("month", "4");
            $f->setValue("day", "21");
            $f->writeToView();
        } catch (Exception $ex) {

        }
    }
}

Main::main();
