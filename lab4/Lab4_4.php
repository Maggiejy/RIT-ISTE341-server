<?php

require_once("DB_02.class.php");
 $db = new DB();

if(isset($_GET['id'])){
    $stringC = count($db->getSelectedPhone($_GET['id']));
    $string = "<h1>".$stringC." result(s) found</h1>";
    $string .= $db->getSelectedPhoneAsTable($_GET['id']);
    $string .= $db->getSelectedPhoneAsTable2($_GET['id']);
    if($stringC > 0){
        print_r($string);
    } else {
        header("Location:Lab4_3.php");
    }  
} else {
    header("Location:Lab4_3.php");
}
?>


