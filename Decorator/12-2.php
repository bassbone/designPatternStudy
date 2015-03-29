<style type="text/css">
    body {font-family:"ＭＳ ゴシック";}
</style>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Display {
    abstract public function getColumns();
    abstract public function getRows();
    abstract public function getRowText($row);
    public final function show() {
        $rowCnt = $this->getRows();
        for ($i = 0; $i < $rowCnt; $i++) {
            echo $this->getRowText($i).'<br>';
        }
    }
}

class StringDisplay extends Display {
    private $string;
    function __construct($string) {
        $this->string = $string;
    }
    public function getColumns() {
        return strlen($this->string);
    }
    public function getRows() {
        return 1;
    }
    public function getRowText($row) {
        if ($row == 0) {
            return $this->string;
        } else {
            return null;
        }
    }
}

class MultiStringDisplay extends Display {
    private $arrStrings = array();
    function __construct() {
        
    }
    public function getColumns() {
        $maxColumns = 0;
        foreach ($this->arrStrings as $string) {
            if ($maxColumns < strlen($string)) {
                $maxColumns = strlen($string);
            }
        }
        return $maxColumns;
    }

    public function getRowText($row) {
        return $this->arrStrings[$row].str_repeat('&nbsp', $this->getColumns() - strlen($this->arrStrings[$row]));
    }

    public function getRows() {
        return count($this->arrStrings);
    }
    
    public function add($string) {
        $this->arrStrings[] = $string;
    }

}

abstract class Border extends Display {
    protected $display;
    function __construct(Display $display) {
        $this->display = $display;
    }
}

class SideBorder extends Border {
    private $borderChar;
    function __construct(Display $display, $ch) {
        parent::__construct($display);
        $this->borderChar = $ch;
    }
    public function getColumns() {
        return 1 + $this->display->getColumns() + 1;
    }
    public function getRows() {
        return $this->display->getRows();
    }
    public function getRowText($row) {
        return $this->borderChar.$this->display->getRowText($row).$this->borderChar;
    }
}

class FullBorder extends Border {
    function __construct(Display $display) {
        parent::__construct($display);
    }
    public function getColumns() {
        return 1 + $this->display->getColumns() + 1;
    }
    public function getRows() {
        return 1 + $this->display->getRows() + 1;
    }
    public function getRowText($row) {
        if ($row == 0) {
            return "+".$this->makeLine('-', $this->display->getColumns()).'+';
        } elseif ($row == $this->display->getRows() + 1) {
            return "+".$this->makeLine('-', $this->display->getColumns()).'+';
        } else {
            return '|'.$this->display->getRowText($row - 1).'|';
        }
    }
    private function makeLine($ch, $count) {
        $buf = array();
        for ($i = 0; $i < $count; $i++) {
            $buf[] = $ch;
        }
        return implode('', $buf);
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $md = new MultiStringDisplay();
        $md->add('Good Morning!');
        $md->add('Good Evening!!');
        $md->add('Good Night. See you next!!!');
        $md->show();
        
        $d1 = new SideBorder($md, '#');
        $d1->show();
        
        $d2 = new FullBorder($md);
        $d2->show();
    }
}

Main::main();
