<?php
require_once("lab3_1.php");
$person = new Person();
$person->setWeight(111);
$person->setHeight(64);

echo "{$person->getFirstName()} {$person->getLastName()} has a BMI of {$person->calculateBMI()}";



?>