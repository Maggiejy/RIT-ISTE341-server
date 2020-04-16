<?php
    session_name("login");
    session_start();
    if (isset($_SESSION['username'])){
            echo "You logged in {$_COOKIE['loggedIn']}.";
        unset($_SESSION['user']); 
        unset($_COOKIE['loggedIn']); 
        session_destroy();
    } else {
        echo "<a href='login.php'>Invalid Login</a>";
    }
?>