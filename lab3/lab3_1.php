<?php
class Person{
    private  $fName = '';
    private  $lName = '';
    private  $height;
    private  $weight;


   function __construct($lName="Spade", $fName="Sam"){
        $this->fName = $fName;
        $this->lName = $lName;
    }
    function getFirstName() {
        return $this->fName;
    }
    function setFirstName($value){
        $this->fName = $value;
    }
    function getLastName() {
        return $this->lName;
    }
    function setLastName($value){
        $this->lName = $value;
    }
    function getHeight() {
        return $this->height;
    }
    function setHeight($value){
        $this->height = $value;
    }
    function getWeight() {
        return $this->weight;
    }
    function setWeight($value){
        $this->weight = $value;
    }

    
    function calculateBMI(){
        while($this->getHeight() > 0){
            $bmi = (705* $this->getWeight()) / ($this->getHeight() * $this->getHeight());
            return round($bmi,2);
        }
    }
}

?>