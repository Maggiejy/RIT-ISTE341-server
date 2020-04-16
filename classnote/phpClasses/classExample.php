<?php
    
    function __autoload($class_name){
        require_once "./classes/$class_name.class.php";
    }

    //static first
    echo "<h2>Static Function Usage</h2>";
    $num1 = "one";
    $num2 = 23456;
    $num3 = "3";

    $yesNo = (Validator::numeric($num1)) ? "Yes" : "No"; 
    echo "<p>$num1 is numeric? $yesNo</p>";
    $yesNo = (Validator::numeric($num2)) ? "Yes" : "No"; 
    echo "<p>$num2 is numeric? $yesNo</p>";
    $yesNo = (Validator::numeric($num3)) ? "Yes" : "No"; 
    echo "<p>$num3 is numeric? $yesNo</p>";

    echo "<h2>Regular Class Usage</h2>";

    $person1 = new Person("Smith","Bob");
    $person2 = new Person();
    $person3 = new Person("Jones");

    echo "<p>Person 1: {$person1->sayHello()}</p>";
    echo "<p>Person 2: ".$person2->sayHello()."</p>";
    echo "<p>Person 3: ".$person3->getLastName()."</p>";

    var_dump($person3);

?>