<?php

//initiate the session
session_name('maggie');
session_start();
?>
<html>
    <head>
        <title>Session Example Page 2</title>
    </head>
    <body>
        <?php
            //check where session variable 'username' is set
            if (isset($_SESSION['username'])){
                echo "Hi, {$_SESSION['username']} from {$_SESSION['school']}.
                    See I remember your name <br/>";
                unset($_SESSION['username']);

                //destroy the session
                //unset the whole session
                unset($_SESSION); //session_unset();

                //invalidate the session cookie
                if (isset($_COOKIE[session_name()])){
                    setcookie(session_name(),'',1,'/');
                }

                //destroy the session
                session_destroy();

                echo "<a href='session01.php'>Page 1</a>";

            } else {
                echo "Sorry, I don't know you.<br/>
                    <a href='session01.php'>Login</a>";
            }

        ?>
    </body>
</html>