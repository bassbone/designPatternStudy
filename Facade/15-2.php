<?php

error_reporting(E_ALL);

class Database {
	private function __construct() {}
	public static function getProperties($dbname) {
		
		$prop = parse_ini_file($dbname);
		return $prop;
	}
}

class FileWriter {
	private $filename;
	public function __construct($filename) {
		$this->filename = $filename;
	}
	public function write($msg) {
		file_put_contents($this->filename, $msg, FILE_APPEND);
	}
}

class HtmlWriter {
	private $writer;
	public function __construct($writer) {
		$this->writer = $writer;
	}
	public function title($title) {
		$this->writer->write('<html>');
		$this->writer->write('<head>');
		$this->writer->write('<title>'.$title.'</title>');
		$this->writer->write('</head>');
		$this->writer->write('<h1>'.$title.'</h1>');
	}
	public function paragraph($msg) {
		$this->writer->write('<p>'.$msg.'</p>');
	}
	public function link($href, $caption) {
		$this->paragraph("<a href=\"".$href."\">".$caption."</a>");
	}
	public function mailto($mailaddr, $username) {
		$this->link('mailto:'.$mailaddr, $username);
	}
	public function close() {
		$this->writer->write('</body>');
		$this->writer->write('</html>');
	}
}

class PageMaker {
	private function __construct() {}
	public static function makeWelcomePage($mailaddr, $filename) {
		$mailprop = Database::getProperties('maildata.ini');
		$username = $mailprop[$mailaddr];
		$writer = new HtmlWriter(new FileWriter($filename));
		$writer->title('Welcome to '.$username."'s page!");
		$writer->paragraph($username.'のページへようこそ。');
		$writer->paragraph('メールまっていますね。');
		$writer->mailto($mailaddr, $username);
		$writer->close();
	}
	public static function makeLinkPage($filename) {
		$mailprop = Database::getProperties('maildata.ini');
		$writer = new HtmlWriter(new FileWriter($filename));
		$writer->title('Link page');
		foreach ($mailprop as $mailaddr => $username) {
			$writer->mailto($mailaddr, $username);
		}
		$writer->close();
	}
}

class Main {
	public function __construct() {}
	public static function main() {
		PageMaker::makeLinkPage('test.html');
	}
}

Main::main();
