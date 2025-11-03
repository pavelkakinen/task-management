<?php

require '../vendor/autoload.php';
require_once 'Book.php';
require_once 'Author.php';

$book = new Book('Head First HTML and CSS', 3, true);
$book->addAuthor(new Author('Elisabeth', 'Robson'));
$book->addAuthor(new Author('Eric', 'Freeman'));

$book2 = new Book('Learning Web Design', 4, true);
$book2->addAuthor(new Author('Jennifer', 'Robbins'));

$data = [ 'books' => [$book, $book2] ];

$latte = new Latte\Engine;

$latte->render('list.latte', $data);
