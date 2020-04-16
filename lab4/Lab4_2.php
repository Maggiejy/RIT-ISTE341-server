<?php

require_once("DB.class.php");
 $db = new DB();
if(isset($_GET['id'])){
    $stringC = count($db->getSelectedPhone($_GET['id']));
    $string = "<h1>".$stringC." result(s) found</h1>";
    $string .= $db->getSelectedPhoneAsTable($_GET['id']);
    if($stringC > 0){
        print_r($string);
    } else {
        header("Location:Lab4_1.php");
    }  
} else {
    header("Location:Lab4_1.php");
}
?>

