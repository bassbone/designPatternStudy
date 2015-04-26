<style type="text/css">
    body {font-family:"ＭＳ ゴシック";}
</style>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BigChar {
    private $charname;
    private $fontdata;
    
    function __construct($charname) {
        $this->fontdata = '';
        $this->charname = $charname;
        
        try {
            $filename = 'big'.$charname.'.txt';
            $fp = fopen($filename, 'r');
            while (!feof($fp)) {
                $line = fgets($fp);
                $line = str_replace(' ', '-', $line);
                $this->fontdata .= $line.'<br>';
            }
        } catch (Exception $ex) {
            $this->fontdata = $charname.'?';
        }
    }
    
    public function myprint() {
        echo $this->fontdata;
    }
}

class BigCharFactory {
    private $pool;
    private static $singleton;
    
    private function __construct() {
        $this->pool = new ArrayObject();
    }
    
    public static function getInstance() {
        if (null === self::$singleton) {
            self::$singleton = new BigCharFactory;
        }
        return self::$singleton;
    }
    
    public function getBigChar($charname) {
        if (array_key_exists((string)$charname, $this->pool)) {
            $bc = $this->pool[(string)$charname];
        } else {
            $bc = new BigChar($charname);
            $this->pool[(string)$charname] = $bc;
        }
        return $bc;
    }
}

class BigString {
    private $bigchars;
    
    function __construct($string) {
        $this->bigchars = array();
        $factory = BigCharFactory::getInstance();
        for ($i = 0; $i < strlen($string); $i++) {
            $this->bigchars[$i] = $factory->getBigChar($string[$i]);
        }
    }
    
    public function myprint() {
        for ($i = 0; $i < count($this->bigchars); $i++) {
            $this->bigchars[$i]->myprint();
            echo '<br>';
        }
    }
}

class Main {
    function __construct() {
        
    }
    public static function main ($string) {
        $bs = new BigString($string);
        $bs->myprint();
    }
}

Main::main('123456789');
