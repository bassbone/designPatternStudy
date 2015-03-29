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

abstract class Border extends Display {
    protected $display;
    function __construct(Display $display) {
        $this->display = $display;
    }
    protected function makeLine($ch, $count) {
        $buf = array();
        for ($i = 0; $i < $count; $i++) {
            $buf[] = $ch;
        }
        return implode('', $buf);
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
}

class UpdownBorder extends Border {
    private $ch;
    function __construct(\Display $display, $ch) {
        parent::__construct($display);
        $this->ch = $ch;
    }
    public function getColumns() {
        return $this->display->getColumns();
    }

    public function getRowText($row) {
        if ($row == 0) {
            return $this->makeLine($this->ch, $this->getColumns());
        } elseif ($row == $this->display->getRows() + 1) {
            return $this->makeLine($this->ch, $this->getColumns());
        } else {
            return $this->display->getRowText($row - 1);
        }
    }

    public function getRows() {
        return 1 + $this->display->getRows() + 1;        
    }
}

class Main {
    function __construct() {
        
    }
    public static function main() {
        $b1 = new StringDisplay('Hello, world.');
        $b2 = new UpdownBorder($b1, '-');
        $b3 = new SideBorder($b2, '*');
        $b1->show();
        $b2->show();
        $b3->show();
        $b4 = new FullBorder(
                new UpdownBorder(
                        new SideBorder(
                                new UpdownBorder(
                                        new SideBorder(new StringDisplay('こんにちは。'), '*'), '='
                                ), '|'
                        ), '/'
                )
        );
        $b4->show();
    }
}

Main::main();
