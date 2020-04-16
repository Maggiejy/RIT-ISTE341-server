<?php
require_once("lab3_1.php");
require_once("lab3_3.php");

$person = new Person();
$Bperson = new BritishPerson();
$Bperson->setWeight(50);
$Bperson->setHeight(164);

echo "{$person->getFirstName()} {$person->getLastName()} has a BMI of {$Bperson->calculateBMI()}";
?>