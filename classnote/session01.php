<?php

//initiate the session
session_name('maggie');
session_start();

//check to see which visit and go to session02.php if second visit
if (!empty($_SESSION['username'])){
    //add some session variables
    $_SESSION['school'] = 'RIT';
    $_SESSION['count']++;
    header('Location: session02.php');
    exit; //needed so no other code will execute
}
?>
<html>
    <head>
        <title>Session Example Page 1</title>
    </head>
    <body>
        <?php
            //check to see if session varibale 'count' has been set
            if (isset($_SESSION['count'])){
                echo "<h1>Hi, you've been here {$_SESSION['count']} times.</h1>"; //no space within {}
            } else {
                echo "<h1>Hi, you haven't been here before.</h1>";
                $_SESSION['count'] = 0;
            }
            $_SESSION['username'] = 'Maggie';
        ?>
        <h2><a href="session01.php">Come Back!</a></h2>
    </body>
</html>