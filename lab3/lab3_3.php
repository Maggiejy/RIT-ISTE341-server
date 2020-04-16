<?php
require_once("lab3_1.php");
class BritishPerson extends Person{
    function calculateBMI(){
        $weight = $this->getWeight() * 2.20462;
        $height = $this->getHeight() * 0.393701;
        $this->setWeight($weight);
        $this->setHeight($height);
        return parent::calculateBMI();
    }
} 

?>