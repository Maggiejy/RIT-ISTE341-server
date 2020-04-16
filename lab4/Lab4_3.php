<?php
    require_once("DB_02.class.php");
    $db = new DB();
	echo $db->getAllPeopleAsTable();
?>