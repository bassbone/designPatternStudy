<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Item {
    protected $caption;
    function __construct($caption) {
        $this->caption = $caption;
    }
    abstract public function makeHTML();
}

abstract class Link extends Item {
    protected $url;
    function __construct($caption, $url) {
        parent::__construct($caption);
        $this->url = $url;
    }
}

abstract class Tray extends Item {
    protected $tray = array();
    function __construct($caption) {
        parent::__construct($caption);
    }
    public function add(Item $item) {
        $this->tray[] = $item;
    }
}

abstract class Page {
    protected $title;
    protected $author;
    protected $content = array();
    function __construct($title, $author) {
        $this->title = $title;
        $this->author = $author;
    }
    public function add(Item $item) {
        $this->content[] = $item;
    }
    public function output() {
        try {
            echo $this->makeHTML();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    abstract public function makeHTML();
}

abstract class Factory {
    public static function getFactory($classname) {
        $factory = null;
        try {
            $factory = new $classname();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $factory;
    }
    abstract public function createLink($caption, $url);
    abstract public function createTray($caption);
    abstract public function createPage($title, $author);
}

class Main {
    function __construct() {
        
    }
    public static function main($classname) {
        $factory = Factory::getFactory($classname);
        $asahi = $factory->createLink("朝日新聞", "http://www.asahi.com/");
        $yomiuri = $factory->createLink("読売新聞", "http://www.yomiuri.co.jp/");
        $us_yahoo = $factory->createLink("Yahoo!", "http://www.yahoo.com/");
        $jp_yahoo = $factory->createLink("Yahoo!Japan", "http://www.yahoo.co.jp/");
        $excite = $factory->createLink("Excite", "http://www.excite.com/");
        $google = $factory->createLink("Google", "http://www.google.com/");
        
        $traynews = $factory->createTray("新聞");
        $traynews->add($asahi);
        $traynews->add($yomiuri);
        
        $trayyahoo = $factory->createTray("Yahoo!");
        $trayyahoo->add($us_yahoo);
        $trayyahoo->add($jp_yahoo);
        
        $traysearch = $factory->createTray("サーチエンジン");
        $traysearch->add($trayyahoo);
        $traysearch->add($excite);
        $traysearch->add($google);
        
        $page = $factory->createPage("LinkPage", "結城 浩");
        $page->add($traynews);
        $page->add($traysearch);
        $page->output();
    }
}

Main::main("TableFactory");

class ListFactory extends Factory {
    public function createLink($caption, $url) {
        return new ListLink($caption, $url);
    }
    public function createTray($caption) {
        return new ListTray($caption);
    }
    public function createPage($title, $author) {
        return new ListPage($title, $author);
    }
}

class ListLink extends Link {
    function __construct($caption, $url) {
        parent::__construct($caption, $url);
    }
    public function makeHTML() {
        return "<li><a href=\"".$this->url."\">".$this->caption."</a></li>\n";
    }
}

class ListTray extends Tray {
    function __construct($caption) {
        parent::__construct($caption);
    }
    public function makeHTML() {
        $buffer = array();
        $buffer[] = "<li>\n";
        $buffer[] = $this->caption."\n";
        $buffer[] = "<ul>\n";
        $it = new ArrayIterator($this->tray);
        while ($it->valid()) {
            $item = $it->current();
            $buffer[] = $item->makeHTML();
            $it->next();
        }
        $buffer[] = "</ul>\n";
        $buffer[] = "</li>\n";
        return implode("", $buffer);
    }
}

class ListPage extends Page {
    function __construct($title, $author) {
        parent::__construct($title, $author);
    }
    public function makeHTML() {
        $buffer = array();
        $buffer[] = "<html><head><title>".$this->title."</title></head>\n";
        $buffer[] = "<body>";
        $buffer[] = "<h1>".$this->title."</h1>\n";
        $buffer[] = "<ul>\n";
        $it = new ArrayIterator($this->content);
        while ($it->valid()) {
            $item = $it->current();
            $buffer[] = $item->makeHTML();
            $it->next();
        }
        $buffer[] = "</ul>\n";
        $buffer[] = "<hr><address>".$this->author."</address>";
        $buffer[] = "</body></html>";
        return implode("", $buffer);
    }
}

class TableFactory extends Factory {
    public function createLink($caption, $url) {
        return new TableLink($caption, $url);
    }
    public function createTray($caption) {
        return new TableTray($caption);
    }
    public function createPage($title, $author) {
        return new TablePage($title, $author);
    }
}

class TableLink extends Link {
    function __construct($caption, $url) {
        parent::__construct($caption, $url);
    }
    public function makeHTML() {
        return "<td><a href=\"".$this->url."\">".$this->caption."</a></td>\n";
    }
}

class TableTray extends Tray {
    function __construct($caption) {
        parent::__construct($caption);
    }
    public function makeHTML() {
        $buffer = array();
        $buffer[] = "<td>";
        $buffer[] = "<table width=\"100%\" border=\"1\"><tr>";
        $buffer[] = "<td bgcolor=\"#cccccc\" align=\"center\" colspan=\"".count($this->tray)."\"><b>".$this->caption."</b></td>";
        $buffer[] = "</tr>\n";
        $buffer[] = "<tr>\n";
        $it = new ArrayIterator($this->tray);
        while ($it->valid()) {
            $item = $it->current();
            $buffer[] = $item->makeHTML();
            $it->next();
        }
        $buffer[] = "</tr></table>";
        $buffer[] = "</td>";
        return implode("", $buffer);
    }
}

class TablePage extends Page {
    function __construct($title, $author) {
        parent::__construct($title, $author);
    }
    public function makeHTML() {
        $buffer = array();
        $buffer[] = "<html><head><title>".$this->title."</title></head>\n";
        $buffer[] = "<body>";
        $buffer[] = "<h1>".$this->title."</h1>\n";
        $buffer[] = "<table width=\"80%\" border=\"3\">\n";
        $it = new ArrayIterator($this->content);
        while ($it->valid()) {
            $item = $it->current();
            $buffer[] = "<tr>".$item->makeHTML()."</tr>";
            $it->next();
        }
        $buffer[] = "</table>";
        $buffer[] = "<hr><address>".$this->author."</address>";
        $buffer[] = "</body></html>\n";
        return implode("", $buffer);
    }
}
