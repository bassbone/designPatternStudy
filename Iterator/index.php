<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface Aggregate {
    public function iterator();
}
        
interface MyIterator {
    public function hasNext();
    public function next();
}

class Book {
    private $name;
    public function __construct($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
}

class BookShelf implements Aggregate {
    private $books;
    private $last = 0;
    public function __construct($maxsize) {
        $this->books = new ArrayObject();
    }
    public function getBookAt($index) {
        return $this->books[$index];
    }
    public function appendBook(Book $book) {
        $this->books->append($book);
        $this->last++;
    }
    public function getLength() {
        return $this->last;
    }
    public function iterator() {
        return new BookShelfIterator($this);
    }
}

class BookShelfIterator implements MyIterator {
    private $bookshelf;
    private $index;
    private $maxsize;
    
    public function __construct(BookShelf $bookShelf) {
        $this->bookshelf = $bookShelf;
        $this->index = 0;
    }
    public function hasNext() {
        if ($this->index < $this->bookshelf->getLength()) {
            return true;
        } else {
            return false;
        }
    }
    public function next() {
        $book = $this->bookshelf->getBookAt($this->index);
        $this->index++;
        return $book;
    }
}

class Main {
    
    public function __construct() {}
    
    public static function main() {
        $bookShelf = new BookShelf(4);
        $bookShelf->appendBook(new Book("aaaa"));
        $bookShelf->appendBook(new Book("bbbb"));
        $bookShelf->appendBook(new Book("cccc"));
        $bookShelf->appendBook(new Book("dddd"));
        $bookShelf->appendBook(new Book("dddd"));
        $bookShelf->appendBook(new Book("eeee"));
        $bookShelf->appendBook(new Book("ffff"));
        $it = $bookShelf->iterator();
        while ($it->hasNext()) {
            $book = $it->next();
            echo $book->getName()."<br>";
        }
    }
}

Main::main();
