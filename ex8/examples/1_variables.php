<?php

require '../vendor/autoload.php';

$data = ['greeting' => 'Hello!',
         'employee' => new Employee()];

$latte = new Latte\Engine;
$latte->render('tpl/1_variables.latte', $data);


class Employee {
    public ?string $profilePicture = 'img/pic1.jpeg';
}
