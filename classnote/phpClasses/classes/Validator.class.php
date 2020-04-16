<?php

class Validator {
    static function numeric($value){
        $reg = "/^[0-9]+$/";
        return preg_match($reg,$value);
    }
}

?>